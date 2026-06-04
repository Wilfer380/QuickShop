<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin VehiPark',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'operador@vehipark.test'],
            [
                'name' => 'Operador VehiPark',
                'password' => Hash::make('operador123'),
                'role' => 'empleado',
                'status' => 'active',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'supervisor@vehipark.test'],
            [
                'name' => 'Supervisor VehiPark',
                'password' => Hash::make('supervisor123'),
                'role' => 'supervisor',
                'status' => 'active',
            ]
        );

        if (Schema::hasTable('roles')) {
            $role = Rol::query()->where('name', 'administrador')->first();

            if ($role) {
                $admin->roles()->syncWithoutDetaching([$role->id]);
            }
        }
    }
}
