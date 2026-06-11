<?php

namespace App\Services\Parqueadero;

use App\Models\CupoParqueadero;
use App\Models\MovimientoParqueadero;
use App\Models\Pago;
use App\Models\Tarifa;
use App\Models\Vehiculo;
use App\Services\Tarifas\CalculadoraTarifaService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class ParqueaderoService
{
    public function __construct(private CalculadoraTarifaService $calculadora)
    {
    }

    public function registrarEntrada(array $data, int $userId): MovimientoParqueadero
    {
        return DB::transaction(function () use ($data, $userId) {
            $vehiculo = Vehiculo::query()->lockForUpdate()->findOrFail($data['vehiculo_id']);

            $activo = MovimientoParqueadero::query()
                ->where('vehiculo_id', $vehiculo->id)
                ->where('estado', 'abierto')
                ->exists();

            if ($activo) {
                throw new \RuntimeException('Este vehiculo ya tiene un movimiento activo.');
            }

            $cupo = null;
            if (! empty($data['cupo_id'])) {
                $cupo = CupoParqueadero::query()->lockForUpdate()->findOrFail($data['cupo_id']);

                if ($cupo->estado !== 'disponible') {
                    throw new \RuntimeException('El cupo seleccionado no esta disponible.');
                }

                $cupo->update(['estado' => 'ocupado']);
            }

            return MovimientoParqueadero::create([
                'vehiculo_id' => $vehiculo->id,
                'cliente_id' => $data['cliente_id'] ?? $vehiculo->cliente_id,
                'cupo_id' => $cupo?->id,
                'tarifa_id' => $data['tarifa_id'],
                'registrado_por_id' => $userId,
                'entrada_at' => $data['entrada_at'] ?? now(),
                'estado' => 'abierto',
                'observaciones' => $data['observaciones'] ?? null,
            ])->load(['vehiculo', 'cliente', 'cupo', 'tarifa']);
        });
    }

    public function registrarSalida(MovimientoParqueadero $movimiento, array $data, int $userId): MovimientoParqueadero
    {
        return DB::transaction(function () use ($movimiento, $data, $userId) {
            $movimiento = MovimientoParqueadero::query()
                ->with(['tarifa', 'cupo'])
                ->lockForUpdate()
                ->findOrFail($movimiento->id);

            if ($movimiento->estado !== 'abierto') {
                throw new \RuntimeException('El movimiento ya fue cerrado.');
            }

            $tarifa = $movimiento->tarifa ?? Tarifa::query()->where('activa', true)->firstOrFail();
            $salidaAt = ! empty($data['salida_at']) ? CarbonImmutable::parse($data['salida_at']) : now();
            $calculo = $this->calculadora->calcular($tarifa, $movimiento->entrada_at, $salidaAt);
            $pagoSalida = (float) ($data['pago_salida'] ?? 0);

            $movimiento->update([
                'salida_at' => $salidaAt,
                'minutos' => $calculo['minutos'],
                'total' => $calculo['total'],
                'estado' => $pagoSalida >= $calculo['total'] ? 'pagado' : 'cerrado',
                'observaciones' => $data['observaciones'] ?? $movimiento->observaciones,
            ]);

            if ($movimiento->cupo) {
                $movimiento->cupo->update(['estado' => 'disponible']);
            }

            if ($pagoSalida > 0) {
                Pago::create([
                    'cliente_id' => $movimiento->cliente_id,
                    'movimiento_parqueadero_id' => $movimiento->id,
                    'recibido_por_id' => $userId,
                    'concepto' => 'parqueadero',
                    'metodo_pago' => $data['metodo_pago'] ?? 'efectivo',
                    'valor' => $pagoSalida,
                    'pagado_at' => now(),
                    'referencia' => $data['referencia'] ?? null,
                    'estado' => 'registrado',
                    'notas' => $data['notas_pago'] ?? null,
                ]);
            }

            return $movimiento->fresh(['vehiculo', 'cliente', 'cupo', 'tarifa', 'pagos']);
        });
    }
}
