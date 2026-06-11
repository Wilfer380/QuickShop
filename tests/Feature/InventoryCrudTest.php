<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('allows an employee to create a vehicle inventory item with image', function () {
    Storage::fake('public');

    $employee = User::factory()->create(['role' => 'empleado']);
    $category = Category::create([
        'name' => 'Sedan',
        'description' => 'Autos sedan',
        'sort_order' => 10,
    ]);

    $response = $this->actingAs($employee)->post(route('vehicle-publications.store'), [
        'category_id' => $category->id,
        'name' => 'Toyota Corolla Operativo',
        'description' => 'Unidad sedan para turnos administrativos.',
        'price' => 72000,
        'stock' => 4,
        'image' => UploadedFile::fake()->image('corolla.jpg'),
    ]);

    $response->assertRedirect(route('vehicle-publications.index'));

    expect(Product::count())->toBe(1);
    expect(ProductImage::count())->toBe(1);
    Storage::disk('public')->assertExists(ProductImage::first()->image_path);
});

it('accepts formatted money values when creating an inventory item', function () {
    Storage::fake('public');

    $employee = User::factory()->create(['role' => 'empleado']);
    $category = Category::create([
        'name' => 'Sedan',
        'description' => 'Autos sedan',
        'sort_order' => 10,
    ]);

    $this->actingAs($employee)->post(route('vehicle-publications.store'), [
        'category_id' => $category->id,
        'name' => 'Toyota Corolla Operativo',
        'description' => 'Unidad sedan para turnos administrativos.',
        'price' => '72.000',
        'stock' => 4,
        'image' => UploadedFile::fake()->image('corolla.jpg'),
    ])->assertRedirect(route('vehicle-publications.index'));

    expect(Product::first()->price)->toBe(72000.0);
});

it('allows authenticated employees to manage vehicle inventory', function () {
    $employee = User::factory()->create(['role' => 'empleado']);

    $this->actingAs($employee)
        ->get(route('vehicle-publications.index'))
        ->assertOk();
});

it('renders the inventory edit page for owned vehicles', function () {
    $employee = User::factory()->create(['role' => 'empleado']);
    $category = Category::create([
        'name' => 'Sedan',
        'description' => 'Autos sedan',
        'sort_order' => 10,
    ]);
    $product = Product::create([
        'user_id' => $employee->id,
        'category_id' => $category->id,
        'name' => 'Toyota Corolla Operativo',
        'description' => 'Unidad sedan para turnos administrativos.',
        'price' => 72000,
        'stock' => 4,
    ]);

    $this->actingAs($employee)
        ->get(route('vehicle-publications.edit', $product))
        ->assertOk()
        ->assertSee('Editar vehiculo', false)
        ->assertSee('Toyota Corolla Operativo', false);
});

it('allows an employee to update and delete an inventory item', function () {
    Storage::fake('public');

    $employee = User::factory()->create(['role' => 'empleado']);
    $category = Category::create([
        'name' => 'Sedan',
        'description' => 'Autos sedan',
        'sort_order' => 10,
    ]);
    $product = Product::create([
        'user_id' => $employee->id,
        'category_id' => $category->id,
        'name' => 'Toyota Corolla Operativo',
        'description' => 'Unidad sedan para turnos administrativos.',
        'price' => 72000,
        'stock' => 4,
    ]);

    $this->actingAs($employee)
        ->put(route('vehicle-publications.update', $product), [
            'category_id' => $category->id,
            'name' => 'Toyota Corolla Administrativo',
            'description' => 'Unidad sedan actualizada.',
            'price' => 76000,
            'stock' => 3,
        ])
        ->assertRedirect(route('vehicle-publications.index'));

    expect($product->fresh()->name)->toBe('Toyota Corolla Administrativo');
    expect($product->fresh()->price)->toBe(76000.0);

    $this->actingAs($employee)
        ->delete(route('vehicle-publications.delete', $product))
        ->assertRedirect(route('vehicle-publications.index'));

    expect(Product::count())->toBe(0);
});
