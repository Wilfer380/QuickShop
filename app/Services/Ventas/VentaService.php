<?php

namespace App\Services\Ventas;

use App\Models\Pago;
use App\Models\Vehiculo;
use App\Models\Venta;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class VentaService
{
    public function crear(array $data, int $vendedorId): Venta
    {
        return DB::transaction(function () use ($data, $vendedorId) {
            $vehiculo = Vehiculo::query()->lockForUpdate()->findOrFail($data['vehiculo_id']);

            if ($vehiculo->estado !== 'disponible') {
                throw new \RuntimeException('Solo se pueden vender vehiculos disponibles.');
            }

            $precioBase = (float) $data['precio_base'];
            $descuento = (float) ($data['descuento'] ?? 0);
            $impuestos = (float) ($data['impuestos'] ?? 0);
            $total = max(0, $precioBase - $descuento + $impuestos);
            $pagoInicial = (float) ($data['pago_inicial'] ?? 0);

            $venta = Venta::create([
                ...Arr::only($data, ['cliente_id', 'vehiculo_id', 'fecha_venta', 'notas']),
                'vendedor_id' => $vendedorId,
                'precio_base' => $precioBase,
                'descuento' => $descuento,
                'impuestos' => $impuestos,
                'total' => $total,
                'estado' => $this->estadoPorPago($total, $pagoInicial),
            ]);

            $vehiculo->update([
                'cliente_id' => $data['cliente_id'],
                'estado' => 'vendido',
            ]);

            if ($pagoInicial > 0) {
                Pago::create([
                    'cliente_id' => $data['cliente_id'],
                    'venta_id' => $venta->id,
                    'recibido_por_id' => $vendedorId,
                    'concepto' => 'venta',
                    'metodo_pago' => $data['metodo_pago'] ?? 'efectivo',
                    'valor' => $pagoInicial,
                    'pagado_at' => now(),
                    'referencia' => $data['referencia'] ?? null,
                    'estado' => 'registrado',
                    'notas' => $data['notas_pago'] ?? null,
                ]);
            }

            return $venta->load(['cliente', 'vehiculo', 'vendedor', 'pagos']);
        });
    }

    private function estadoPorPago(float $total, float $pago): string
    {
        if ($pago <= 0) {
            return 'pendiente';
        }

        return $pago >= $total ? 'pagada' : 'abono';
    }
}
