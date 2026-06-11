<?php

namespace Database\Seeders;

use App\Models\ConfiguracionEmpresa;
use Illuminate\Database\Seeder;

class ConfiguracionEmpresaSeeder extends Seeder
{
    public function run(): void
    {
        ConfiguracionEmpresa::query()->updateOrCreate(
            ['id' => 1],
            [
                'nombre_empresa' => 'VehiPark',
                'nit' => null,
                'telefono' => null,
                'email' => null,
                'direccion' => null,
                'moneda' => 'COP',
                'parametros' => [
                    'horas_promocion_parqueadero' => 2,
                    'zona_horaria' => 'America/Bogota',
                ],
            ]
        );
    }
}
