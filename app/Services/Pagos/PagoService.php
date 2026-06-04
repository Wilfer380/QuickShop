<?php

namespace App\Services\Pagos;

use App\Models\MovimientoParqueadero;
use App\Models\Pago;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;

class PagoService
{
    public function registrar(array $data, int $userId): Pago
    {
        return DB::transaction(function () use ($data, $userId) {
            $venta = ! empty($data['venta_id']) ? Venta::query()->lockForUpdate()->findOrFail($data['venta_id']) : null;
            $movimiento = ! empty($data['movimiento_parqueadero_id']) ? MovimientoParqueadero::query()->lockForUpdate()->findOrFail($data['movimiento_parqueadero_id']) : null;

            $pago = Pago::create([
                'cliente_id' => $data['cliente_id'] ?? $venta?->cliente_id ?? $movimiento?->cliente_id,
                'venta_id' => $venta?->id,
                'movimiento_parqueadero_id' => $movimiento?->id,
                'recibido_por_id' => $userId,
                'concepto' => $data['concepto'],
                'metodo_pago' => $data['metodo_pago'],
                'valor' => $data['valor'],
                'pagado_at' => $data['pagado_at'] ?? now(),
                'referencia' => $data['referencia'] ?? null,
                'estado' => 'registrado',
                'notas' => $data['notas'] ?? null,
            ]);

            if ($venta) {
                $pagado = (float) $venta->pagos()->sum('valor');
                $venta->update(['estado' => $pagado >= (float) $venta->total ? 'pagada' : 'abono']);
            }

            if ($movimiento && $movimiento->total !== null) {
                $pagado = (float) $movimiento->pagos()->sum('valor');
                $movimiento->update(['estado' => $pagado >= (float) $movimiento->total ? 'pagado' : $movimiento->estado]);
            }

            return $pago->load(['cliente', 'venta', 'movimientoParqueadero', 'recibidoPor']);
        });
    }
}
