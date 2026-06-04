<?php

namespace Database\Seeders;

use App\Models\Tarifa;
use Illuminate\Database\Seeder;

class TarifaSeeder extends Seeder
{
    public function run(): void
    {
        $rates = [
            ['nombre' => 'Carro por hora', 'tipo_vehiculo' => 'carro', 'valor' => 5000],
            ['nombre' => 'Moto por hora', 'tipo_vehiculo' => 'moto', 'valor' => 3000],
            ['nombre' => 'Pickup por hora', 'tipo_vehiculo' => 'pickup', 'valor' => 7000],
        ];

        foreach ($rates as $rate) {
            Tarifa::query()->updateOrCreate(
                ['nombre' => $rate['nombre']],
                [
                    'tipo_vehiculo' => $rate['tipo_vehiculo'],
                    'tipo_cobro' => 'hora',
                    'valor' => $rate['valor'],
                    'activa' => true,
                    'descripcion' => 'Base parking rate seeded for Phase 2.',
                ]
            );
        }
    }
}
