<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

it('completes a purchase and updates balances stock and order data', function () {
    $buyer = User::factory()->create([
        'money' => 200,
    ]);
    $seller = User::factory()->create([
        'money' => 10,
        'role' => 'seller',
    ]);
    $category = Category::create([
        'name' => 'Electronics',
        'description' => 'Devices',
    ]);
    $product = Product::create([
        'user_id' => $seller->id,
        'category_id' => $category->id,
        'name' => 'Headphones',
        'description' => 'Noise cancelling',
        'price' => 75,
        'stock' => 3,
    ]);

    $response = $this
        ->actingAs($buyer)
        ->withSession(['List_products_shop' => [$product->id]])
        ->get(route('cart.shop'));

    $response->assertRedirect(route('buyers.index'));

    expect((float) $buyer->fresh()->money)->toBe(125.0);
    expect((float) $seller->fresh()->money)->toBe(85.0);
    expect($product->fresh()->stock)->toBe(2);
    expect(Order::count())->toBe(1);
    expect(OrderItem::count())->toBe(1);
    expect(session('List_products_shop'))->toBe([]);
});

it('does not create an order when the buyer has insufficient funds', function () {
    $buyer = User::factory()->create([
        'money' => 20,
    ]);
    $seller = User::factory()->create([
        'money' => 10,
        'role' => 'seller',
    ]);
    $category = Category::create([
        'name' => 'Books',
        'description' => 'Reading',
    ]);
    $product = Product::create([
        'user_id' => $seller->id,
        'category_id' => $category->id,
        'name' => 'Laravel Book',
        'description' => 'Guide',
        'price' => 75,
        'stock' => 3,
    ]);

    $response = $this
        ->actingAs($buyer)
        ->withSession(['List_products_shop' => [$product->id]])
        ->get(route('cart.shop'));

    $response->assertRedirect(route('buyers.index'));

    expect((float) $buyer->fresh()->money)->toBe(20.0);
    expect((float) $seller->fresh()->money)->toBe(10.0);
    expect($product->fresh()->stock)->toBe(3);
    expect(Order::count())->toBe(0);
    expect(OrderItem::count())->toBe(0);
});
