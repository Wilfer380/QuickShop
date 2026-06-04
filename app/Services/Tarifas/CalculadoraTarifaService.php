<?php

namespace App\Services\Tarifas;

use App\Models\Tarifa;
use Carbon\CarbonInterface;

class CalculadoraTarifaService
{
    public function calcular(Tarifa $tarifa, CarbonInterface $entrada, CarbonInterface $salida): array
    {
        if ($salida->lessThan($entrada)) {
            throw new \RuntimeException('La salida no puede ser anterior a la entrada.');
        }

        $minutos = max(1, (int) ceil($entrada->diffInMinutes($salida)));
        $valor = (float) $tarifa->valor;

        $total = match ($tarifa->tipo_cobro) {
            'minuto' => $minutos * $valor,
            'dia' => max(1, (int) ceil($minutos / 1440)) * $valor,
            default => max(1, (int) ceil($minutos / 60)) * $valor,
        };

        return [
            'minutos' => $minutos,
            'total' => round($total, 2),
        ];
    }
}
