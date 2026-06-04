<?php

use App\Models\User;

it('renders the fleet operations smoke page', function () {
    $employee = User::factory()->create(['role' => 'empleado']);

    $this->actingAs($employee)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Panel principal VehiPark', false)
        ->assertSee('Operación diaria', false)
        ->assertSee('Vehiculos', false);
});

it('renders the login page smoke test', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('Sistema de Venta de Vehículos y Gestión de Parqueadero', false)
        ->assertSee('Iniciar sesión', false)
        ->assertSee('Google', false)
        ->assertSee('Microsoft', false);
});

it('renders the register page smoke test', function () {
    $this->get(route('register'))
        ->assertOk()
        ->assertSee('Crea tu cuenta para empezar', false)
        ->assertSee('Crear cuenta', false)
        ->assertSee('Rol', false)
        ->assertSee('Términos y Condiciones', false);
});
