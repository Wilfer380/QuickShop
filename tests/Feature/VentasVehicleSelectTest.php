<?php

use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\User;

function createCliente(array $overrides = []): Cliente
{
    return Cliente::create(array_merge([
        'tipo_documento' => 'cc',
        'documento' => (string) fake()->unique()->numberBetween(1000000000, 1999999999),
        'nombres' => 'Cliente',
        'apellidos' => 'Prueba',
        'telefono' => '3000000000',
        'email' => fake()->unique()->safeEmail(),
        'direccion' => 'Calle 1',
        'ciudad' => 'Bogotá',
        'segmento' => 'particular',
        'estado' => 'activo',
    ], $overrides));
}

it('shows available vehicles and marks sold ones as agotado', function () {
    $employee = User::factory()->create(['role' => 'empleado']);
    $client = createCliente();

    Vehiculo::create([
        'cliente_id' => $client->id,
        'placa' => 'AAA-111',
        'tipo' => 'carro',
        'marca' => 'Renault',
        'modelo' => 'Sandero',
        'anio' => 2020,
        'color' => 'Blanco',
        'ubicacion' => 'inventario venta',
        'precio_compra' => 3000000,
        'precio_venta' => 5678000,
        'estado' => 'parqueado',
    ]);

    Vehiculo::create([
        'cliente_id' => $client->id,
        'placa' => 'BBB-222',
        'tipo' => 'carro',
        'marca' => 'Suzuki',
        'modelo' => 'Swift',
        'anio' => 2021,
        'color' => 'Rojo',
        'ubicacion' => 'inventario venta',
        'precio_compra' => 4000000,
        'precio_venta' => 6500000,
        'estado' => 'vendido',
    ]);

    $this->actingAs($employee)
        ->get(route('ventas.create'))
        ->assertOk()
        ->assertSee('Buscar por placa, marca o modelo', false)
        ->assertSee('AAA-111', false)
        ->assertSee('BBB-222', false)
        ->assertSee('Este vehículo ya está vendido', false)
        ->assertSee('Seleccionar vehículo', false);
});

it('shows agotado when no vehicles can be sold', function () {
    $employee = User::factory()->create(['role' => 'empleado']);
    $client = createCliente(['documento' => '1987654321']);

    Vehiculo::create([
        'cliente_id' => $client->id,
        'placa' => 'CCC-333',
        'tipo' => 'carro',
        'marca' => 'Nissan',
        'modelo' => 'Versa',
        'anio' => 2019,
        'color' => 'Negro',
        'ubicacion' => 'inventario venta',
        'precio_compra' => 2000000,
        'precio_venta' => 3500000,
        'estado' => 'vendido',
    ]);

    $this->actingAs($employee)
        ->get(route('ventas.create'))
        ->assertOk()
        ->assertSee('No hay vehículos habilitados para venta en este momento.', false);
});

it('rejects a sold vehicle when saving a sale', function () {
    $employee = User::factory()->create(['role' => 'empleado']);
    $client = createCliente();
    $soldVehicle = Vehiculo::create([
        'cliente_id' => $client->id,
        'placa' => 'DDD-444',
        'tipo' => 'carro',
        'marca' => 'Kia',
        'modelo' => 'Picanto',
        'anio' => 2022,
        'color' => 'Gris',
        'ubicacion' => 'inventario venta',
        'precio_compra' => 3500000,
        'precio_venta' => 7200000,
        'estado' => 'vendido',
    ]);

    $this->actingAs($employee)
        ->post(route('ventas.store'), [
            'cliente_id' => $client->id,
            'vehiculo_id' => $soldVehicle->id,
            'fecha_venta' => now()->toDateString(),
            'precio_base' => '7.200.000',
            'descuento' => '0',
            'impuestos' => '0',
            'pago_inicial' => '0',
            'metodo_pago' => 'efectivo',
        ])
        ->assertSessionHasErrors(['vehiculo_id' => 'Este vehículo ya está vendido.']);
});

it('rejects decimal-looking sale prices', function () {
    $employee = User::factory()->create(['role' => 'empleado']);
    $client = createCliente();
    $vehicle = Vehiculo::create([
        'cliente_id' => $client->id,
        'placa' => 'EEE-555',
        'tipo' => 'carro',
        'marca' => 'Hyundai',
        'modelo' => 'Accent',
        'anio' => 2022,
        'color' => 'Blanco',
        'ubicacion' => 'inventario venta',
        'precio_compra' => 4500000,
        'precio_venta' => 8500000,
        'estado' => 'disponible',
    ]);

    $this->actingAs($employee)
        ->post(route('ventas.store'), [
            'cliente_id' => $client->id,
            'vehiculo_id' => $vehicle->id,
            'fecha_venta' => now()->toDateString(),
            'precio_base' => '0,455',
            'descuento' => '0',
            'impuestos' => '0',
            'pago_inicial' => '0',
            'metodo_pago' => 'efectivo',
        ])
        ->assertSessionHasErrors(['precio_base']);
});
