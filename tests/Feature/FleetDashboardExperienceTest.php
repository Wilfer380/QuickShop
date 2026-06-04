<?php

use App\Models\Category;
use App\Models\User;

it('renders the login and register experiences with distinct copy', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('Iniciar sesión', false)
        ->assertSee('Sistema de Venta de Vehículos y Gestión de Parqueadero', false)
        ->assertSee('Google', false)
        ->assertSee('Microsoft', false);

    $this->get('/register')
        ->assertOk()
        ->assertSee('Crear cuenta', false)
        ->assertSee('Crea tu cuenta para empezar', false)
        ->assertSee('Rol', false)
        ->assertSee('Términos y Condiciones', false);
});

it('shows the internal dashboard hero and quick links', function () {
    $employee = User::factory()->create(['role' => 'empleado']);

    $this->actingAs($employee)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Panel principal VehiPark', false)
        ->assertSee('Operación diaria', false)
        ->assertSee(route('vehiculos.index'), false)
        ->assertSee(route('parqueadero.index'), false);
});

it('hides the removed legacy inventory layout copy', function () {
    $employee = User::factory()->create(['role' => 'empleado']);

    $this->actingAs($employee)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertDontSee('Tabla de inventario', false)
        ->assertDontSee('Unidades bajo administracion', false)
        ->assertDontSee('Agregar unidad', false);
});

it('orders segments by operational sort order instead of alphabetically', function () {
    Category::create([
        'name' => 'Motos',
        'description' => 'Motocicletas',
        'sort_order' => 90,
    ]);

    Category::create([
        'name' => 'Autos',
        'description' => 'Vehiculos livianos',
        'sort_order' => 10,
    ]);

    Category::create([
        'name' => 'Camionetas',
        'description' => 'Unidades utilitarias',
        'sort_order' => 30,
    ]);

    expect(Category::whereNull('parent_id')->ordered()->pluck('name')->all())->toEqual([
        'Autos',
        'Camionetas',
        'Motos',
    ]);
});
