<?php

use App\Models\Cliente;
use App\Models\CupoParqueadero;
use App\Models\MovimientoParqueadero;
use App\Models\Tarifa;
use App\Models\Vehiculo;
use App\Models\User;

it('renders the parqueadero dashboard with the new operational layout', function () {
    $employee = User::factory()->create(['role' => 'empleado']);

    $cliente = Cliente::query()->create([
        'tipo_documento' => 'CC',
        'documento' => '100200300',
        'nombres' => 'Carlos',
        'apellidos' => 'Andrés Díaz',
        'telefono' => '3001234567',
        'email' => 'carlos@example.com',
    ]);

    $vehiculo = Vehiculo::query()->create([
        'cliente_id' => $cliente->id,
        'placa' => 'FZX-482',
        'tipo' => 'carro',
        'marca' => 'Toyota',
        'modelo' => 'Corolla',
        'anio' => 2020,
        'color' => 'Negro',
        'imagen' => 'vehiculos/test-fzx-482.jpg',
        'estado' => 'parqueado',
        'ubicacion' => 'parqueadero',
    ]);

    $cupo = CupoParqueadero::query()->create([
        'codigo' => 'A01',
        'zona' => 'A',
        'tipo_vehiculo' => 'carro',
        'estado' => 'ocupado',
        'observaciones' => null,
    ]);

    $tarifa = Tarifa::query()->create([
        'nombre' => 'Carro por hora test',
        'tipo_vehiculo' => 'carro',
        'tipo_cobro' => 'hora',
        'valor' => 5000,
        'activa' => true,
        'descripcion' => 'Test tariff',
    ]);

    MovimientoParqueadero::query()->create([
        'vehiculo_id' => $vehiculo->id,
        'cliente_id' => $cliente->id,
        'cupo_id' => $cupo->id,
        'tarifa_id' => $tarifa->id,
        'registrado_por_id' => $employee->id,
        'entrada_at' => now(),
        'estado' => 'abierto',
        'observaciones' => 'Ingreso test',
    ]);

    $this->actingAs($employee)
        ->get(route('parqueadero.index'))
        ->assertOk()
        ->assertSee('Parqueaderos', false)
        ->assertSee('Mapa de parqueaderos', false)
        ->assertSee('Estado general', false)
        ->assertSee('Ingresos recientes', false)
        ->assertSee('Próximas salidas', false)
        ->assertSee('Vehículos actualmente en parqueadero', false)
        ->assertSee('FZX-482', false)
        ->assertSee('Ocupado', false)
        ->assertSee(route('vehiculos.imagen', $vehiculo), false);
});
