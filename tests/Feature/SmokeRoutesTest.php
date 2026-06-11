<?php

use App\Models\User;

it('renders the fleet operations smoke page', function () {
    $employee = User::factory()->create(['role' => 'empleado']);

    $this->actingAs($employee)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Dashboard', false)
        ->assertSee('Resumen general del negocio', false)
        ->assertSee('Vehículos', false);
});

it('renders the login page smoke test', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('Iniciar sesión', false)
        ->assertSee('Bienvenido, inicia sesión para continuar', false)
        ->assertSee('Recordarme', false);
});

it('renders the register page smoke test', function () {
    $this->get(route('register'))
        ->assertOk()
        ->assertSee('Crea tu cuenta para empezar', false)
        ->assertSee('Crear cuenta', false)
        ->assertSee('Rol', false)
        ->assertSee('Términos y Condiciones', false);
});
