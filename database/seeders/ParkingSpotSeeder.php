<?php

namespace Database\Seeders;

use App\Models\CupoParqueadero;
use Illuminate\Database\Seeder;

class ParkingSpotSeeder extends Seeder
{
    public function run(): void
    {
        $spots = [];

        foreach (['A', 'B', 'C'] as $zona) {
            foreach (range(1, 10) as $numero) {
                $codigo = sprintf('%s%02d', $zona, $numero);
                $tipoVehiculo = match ($zona) {
                    'A' => in_array($numero, [7], true) ? 'moto' : 'carro',
                    'B' => in_array($numero, [1, 2, 5, 9], true) ? 'camioneta' : 'carro',
                    default => in_array($numero, [3, 7], true) ? 'moto' : 'carro',
                };

                $spots[] = [
                    'codigo' => $codigo,
                    'zona' => $zona,
                    'tipo_vehiculo' => $tipoVehiculo === 'mantenimiento' ? 'carro' : $tipoVehiculo,
                    'estado' => 'disponible',
                ];
            }
        }

        foreach ($spots as $spot) {
                CupoParqueadero::query()->updateOrCreate(
                    ['codigo' => $spot['codigo']],
                    [
                        'zona' => $spot['zona'],
                        'tipo_vehiculo' => $spot['tipo_vehiculo'],
                        'estado' => $spot['estado'],
                        'observaciones' => null,
                    ]
                );
        }
    }
}
