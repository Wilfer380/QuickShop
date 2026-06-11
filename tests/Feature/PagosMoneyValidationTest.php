<?php

use App\Models\Cliente;
use App\Models\User;
use App\Models\Vehiculo;
use App\Models\Venta;

function createPagoCliente(array $overrides = []): Cliente
{
    return Cliente::create(array_merge([
        'tipo_documento' => 'cc',
        'documento' => (string) fake()->unique()->numberBetween(1000000000, 1999999999),
        'nombres' => 'Cliente',
        'apellidos' => 'Pago',
        'telefono' => '3000000000',
        'email' => fake()->unique()->safeEmail(),
        'direccion' => 'Calle 1',
        'ciudad' => 'Bogotá',
        'segmento' => 'particular',
        'estado' => 'activo',
    ], $overrides));
}

it('accepts formatted money values when registering a payment', function () {
    $employee = User::factory()->create(['role' => 'empleado']);
    $client = createPagoCliente();
    $vehicle = Vehiculo::create([
        'cliente_id' => $client->id,
        'placa' => 'PAG-001',
        'tipo' => 'carro',
        'marca' => 'Kia',
        'modelo' => 'Rio',
        'anio' => 2021,
        'color' => 'Blanco',
        'ubicacion' => 'inventario venta',
        'precio_compra' => 4000000,
        'precio_venta' => 7000000,
        'estado' => 'vendido',
    ]);
    $venta = Venta::create([
        'cliente_id' => $client->id,
        'vehiculo_id' => $vehicle->id,
        'vendedor_id' => $employee->id,
        'fecha_venta' => now()->toDateString(),
        'precio_base' => 7000000,
        'descuento' => 0,
        'impuestos' => 0,
        'total' => 7000000,
        'estado' => 'pendiente',
    ]);

    $this->actingAs($employee)
        ->post(route('pagos.store'), [
            'cliente_id' => $client->id,
            'venta_id' => $venta->id,
            'concepto' => 'venta',
            'metodo_pago' => 'efectivo',
            'valor' => '12.000',
            'pagado_at' => now()->format('Y-m-d\TH:i'),
        ])
        ->assertRedirect();
});

it('rejects decimal-looking money values when registering a payment', function () {
    $employee = User::factory()->create(['role' => 'empleado']);
    $client = createPagoCliente();
    $vehicle = Vehiculo::create([
        'cliente_id' => $client->id,
        'placa' => 'PAG-002',
        'tipo' => 'carro',
        'marca' => 'Kia',
        'modelo' => 'Rio',
        'anio' => 2021,
        'color' => 'Blanco',
        'ubicacion' => 'inventario venta',
        'precio_compra' => 4000000,
        'precio_venta' => 7000000,
        'estado' => 'vendido',
    ]);
    $venta = Venta::create([
        'cliente_id' => $client->id,
        'vehiculo_id' => $vehicle->id,
        'vendedor_id' => $employee->id,
        'fecha_venta' => now()->toDateString(),
        'precio_base' => 7000000,
        'descuento' => 0,
        'impuestos' => 0,
        'total' => 7000000,
        'estado' => 'pendiente',
    ]);

    $this->actingAs($employee)
        ->post(route('pagos.store'), [
            'cliente_id' => $client->id,
            'venta_id' => $venta->id,
            'concepto' => 'venta',
            'metodo_pago' => 'efectivo',
            'valor' => '0,455',
            'pagado_at' => now()->format('Y-m-d\TH:i'),
        ])
        ->assertSessionHasErrors(['valor']);
});
