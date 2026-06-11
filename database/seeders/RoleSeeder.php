<?php

namespace Database\Seeders;

use App\Models\Permiso;
use App\Models\Rol;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = collect([
            ['name' => 'clientes.gestionar', 'display_name' => 'Gestionar clientes'],
            ['name' => 'vehiculos.gestionar', 'display_name' => 'Gestionar vehiculos'],
            ['name' => 'ventas.gestionar', 'display_name' => 'Gestionar ventas'],
            ['name' => 'parqueadero.gestionar', 'display_name' => 'Gestionar parqueadero'],
            ['name' => 'tarifas.gestionar', 'display_name' => 'Gestionar tarifas'],
            ['name' => 'pagos.gestionar', 'display_name' => 'Gestionar pagos'],
            ['name' => 'configuracion.gestionar', 'display_name' => 'Gestionar configuracion'],
            ['name' => 'auditoria.ver', 'display_name' => 'Ver auditoria'],
        ])->mapWithKeys(function (array $permission) {
            $model = Permiso::query()->updateOrCreate(
                ['name' => $permission['name']],
                [
                    'display_name' => $permission['display_name'],
                    'description' => null,
                ]
            );

            return [$permission['name'] => $model];
        });

        $roles = [
            'administrador' => [
                'display_name' => 'Administrador',
                'description' => 'Acceso completo al sistema VehiPark.',
                'permissions' => $permissions->keys()->all(),
            ],
            'ventas' => [
                'display_name' => 'Ventas',
                'description' => 'Gestiona clientes, vehiculos disponibles, ventas y pagos.',
                'permissions' => ['clientes.gestionar', 'vehiculos.gestionar', 'ventas.gestionar', 'pagos.gestionar'],
            ],
            'parqueadero' => [
                'display_name' => 'Parqueadero',
                'description' => 'Gestiona cupos, movimientos, tarifas y cobros de parqueadero.',
                'permissions' => ['clientes.gestionar', 'vehiculos.gestionar', 'parqueadero.gestionar', 'tarifas.gestionar', 'pagos.gestionar'],
            ],
            'auditor' => [
                'display_name' => 'Auditor',
                'description' => 'Consulta configuracion, pagos y trazabilidad del sistema.',
                'permissions' => ['pagos.gestionar', 'auditoria.ver'],
            ],
        ];

        foreach ($roles as $name => $roleData) {
            $role = Rol::query()->updateOrCreate(
                ['name' => $name],
                [
                    'display_name' => $roleData['display_name'],
                    'description' => $roleData['description'],
                ]
            );

            $role->permisos()->sync(
                collect($roleData['permissions'])->map(fn (string $permission) => $permissions[$permission]->id)->all()
            );
        }
    }
}
