<?php

namespace Database\Seeders;

use App\Models\CupoParqueadero;
use Illuminate\Database\Seeder;

class ParkingSpotSeeder extends Seeder
{
    public function run(): void
    {
        $spots = [
            ['codigo' => 'A-01', 'zona' => 'A', 'tipo_vehiculo' => 'carro'],
            ['codigo' => 'A-02', 'zona' => 'A', 'tipo_vehiculo' => 'carro'],
            ['codigo' => 'A-03', 'zona' => 'A', 'tipo_vehiculo' => 'carro'],
            ['codigo' => 'M-01', 'zona' => 'Motos', 'tipo_vehiculo' => 'moto'],
            ['codigo' => 'M-02', 'zona' => 'Motos', 'tipo_vehiculo' => 'moto'],
            ['codigo' => 'P-01', 'zona' => 'Pickup', 'tipo_vehiculo' => 'pickup'],
        ];

        foreach ($spots as $spot) {
            CupoParqueadero::query()->updateOrCreate(
                ['codigo' => $spot['codigo']],
                [
                    'zona' => $spot['zona'],
                    'tipo_vehiculo' => $spot['tipo_vehiculo'],
                    'estado' => 'disponible',
                    'observaciones' => null,
                ]
            );
        }
    }
}
