<?php

use App\Models\User;

it('accepts formatted money values when creating a tarifa', function () {
    $employee = User::factory()->create(['role' => 'empleado']);

    $this->actingAs($employee)
        ->post(route('tarifas.store'), [
            'nombre' => 'Carro por hora especial',
            'tipo_vehiculo' => 'automovil',
            'tipo_cobro' => 'hora',
            'valor' => '5.000',
            'activa' => true,
            'descripcion' => 'Tarifa base para prueba.',
        ])
        ->assertRedirect();
});

it('rejects decimal-looking money values when creating a tarifa', function () {
    $employee = User::factory()->create(['role' => 'empleado']);

    $this->actingAs($employee)
        ->post(route('tarifas.store'), [
            'nombre' => 'Carro por hora especial 2',
            'tipo_vehiculo' => 'automovil',
            'tipo_cobro' => 'hora',
            'valor' => '0,455',
            'activa' => true,
            'descripcion' => 'Tarifa base para prueba.',
        ])
        ->assertSessionHasErrors(['valor']);
});
