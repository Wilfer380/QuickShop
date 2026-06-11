<?php

use App\Models\Vehiculo;
use App\Models\User;

it('allows formatted money values when creating a vehicle', function () {
    $employee = User::factory()->create(['role' => 'empleado']);

    $response = $this->actingAs($employee)->post(route('vehiculos.store'), [
        'placa' => 'ABC-999',
        'tipo' => 'carro',
        'marca' => 'Nissan',
        'modelo' => 'Sentra',
        'anio' => 2020,
        'color' => 'Rojo',
        'ubicacion' => 'parqueadero',
        'vin' => 'VIN-ABC-999',
        'kilometraje' => 12000,
        'precio_compra' => '3.000.000',
        'precio_venta' => '5.678.000',
        'estado' => 'disponible',
    ]);

    $response->assertRedirect();

    $vehiculo = Vehiculo::where('placa', 'ABC-999')->firstOrFail();

    expect((int) $vehiculo->precio_compra)->toBe(3000000);
    expect((int) $vehiculo->precio_venta)->toBe(5678000);
});

it('rejects decimal-looking money values when creating a vehicle', function () {
    $employee = User::factory()->create(['role' => 'empleado']);

    $this->actingAs($employee)
        ->post(route('vehiculos.store'), [
            'placa' => 'ABC-998',
            'tipo' => 'carro',
            'marca' => 'Nissan',
            'modelo' => 'Sentra',
            'anio' => 2020,
            'color' => 'Rojo',
            'ubicacion' => 'parqueadero',
            'vin' => 'VIN-ABC-998',
            'kilometraje' => 12000,
            'precio_compra' => '0,455',
            'precio_venta' => '5.678.000',
            'estado' => 'disponible',
        ])
        ->assertSessionHasErrors(['precio_compra']);
});
