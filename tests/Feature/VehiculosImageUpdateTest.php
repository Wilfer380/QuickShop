<?php

use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('renders vehicle images with cache busting and no-store headers', function () {
    Storage::fake('public');

    $employee = User::factory()->create(['role' => 'empleado']);
    $client = Cliente::create([
        'tipo_documento' => 'cc',
        'documento' => '1002854792',
        'nombres' => 'Maicol',
        'apellidos' => 'Olivares',
        'telefono' => '3000000000',
        'email' => 'maicol@example.com',
        'direccion' => 'Calle 1',
        'ciudad' => 'Bogotá',
        'segmento' => 'particular',
        'estado' => 'activo',
    ]);

    $vehicle = Vehiculo::create([
        'cliente_id' => $client->id,
        'placa' => 'XYZ-123',
        'tipo' => 'carro',
        'marca' => 'Suzuki',
        'modelo' => 'Swift',
        'anio' => 2021,
        'color' => 'Rojo',
        'imagen' => UploadedFile::fake()->image('old.jpg')->store('vehiculos', 'public'),
        'ubicacion' => 'parqueadero',
        'precio_compra' => 3000000,
        'precio_venta' => 5678000,
        'estado' => 'disponible',
    ]);

    $this->actingAs($employee)
        ->get(route('vehiculos.edit', $vehicle))
        ->assertOk()
        ->assertSee('?v=', false);

    $this->actingAs($employee)
        ->get(route('vehiculos.index'))
        ->assertOk()
        ->assertSee('?v=', false);

    $response = $this->actingAs($employee)
        ->get(route('vehiculos.imagen', $vehicle));

    $response->assertOk();
    expect((string) $response->headers->get('Cache-Control'))->toContain('no-store');
    expect((string) $response->headers->get('Cache-Control'))->toContain('max-age=0');
});

it('replaces the stored vehicle image on update', function () {
    Storage::fake('public');

    $employee = User::factory()->create(['role' => 'empleado']);
    $client = Cliente::create([
        'tipo_documento' => 'cc',
        'documento' => '1002854793',
        'nombres' => 'Maicol',
        'apellidos' => 'Olivares',
        'telefono' => '3000000001',
        'email' => 'maicol2@example.com',
        'direccion' => 'Calle 2',
        'ciudad' => 'Bogotá',
        'segmento' => 'particular',
        'estado' => 'activo',
    ]);

    $vehicle = Vehiculo::create([
        'cliente_id' => $client->id,
        'placa' => 'ABC-777',
        'tipo' => 'carro',
        'marca' => 'Nissan',
        'modelo' => 'Sentra',
        'anio' => 2020,
        'color' => 'Azul',
        'imagen' => UploadedFile::fake()->image('old.jpg')->store('vehiculos', 'public'),
        'ubicacion' => 'parqueadero',
        'precio_compra' => 3000000,
        'precio_venta' => 5678000,
        'estado' => 'disponible',
    ]);

    $oldPath = $vehicle->imagen;

    $this->actingAs($employee)
        ->put(route('vehiculos.update', $vehicle), [
            'cliente_id' => $client->id,
            'placa' => 'ABC-777',
            'tipo' => 'carro',
            'marca' => 'Nissan',
            'modelo' => 'Sentra',
            'anio' => 2020,
            'color' => 'Azul',
            'ubicacion' => 'parqueadero',
            'vin' => 'VIN-777',
            'kilometraje' => 12000,
            'precio_compra' => '3.000.000',
            'precio_venta' => '5.678.000',
            'estado' => 'disponible',
            'imagen' => UploadedFile::fake()->image('new.jpg'),
        ])
        ->assertRedirect(route('vehiculos.show', $vehicle));

    expect($vehicle->fresh()->imagen)->not->toBe($oldPath);
    Storage::disk('public')->assertMissing($oldPath);
});
