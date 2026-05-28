<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $seller = User::query()->where('email', 'seller@gmail.com')->first();

        if (! $seller) {
            return;
        }

        $products = [
            [
                'category_id' => 12,
                'name' => 'Auriculares NovaSound Pro',
                'description' => 'Auriculares inalámbricos con cancelación de ruido, batería de larga duración y almohadillas cómodas para trabajo, gaming y música diaria.',
                'price' => 129.99,
                'stock' => 12,
                'image' => 'product_images/nova-headphones.svg',
            ],
            [
                'category_id' => 1,
                'name' => 'Smartwatch Pulse One',
                'description' => 'Reloj inteligente con monitoreo de salud, notificaciones en tiempo real y resistencia al agua para un estilo de vida activo.',
                'price' => 89.50,
                'stock' => 18,
                'image' => 'product_images/pulse-watch.svg',
            ],
            [
                'category_id' => 4,
                'name' => 'Lámpara Aura Desk',
                'description' => 'Lámpara LED minimalista con luz regulable y acabado premium para escritorio, estudio o espacio creativo.',
                'price' => 54.90,
                'stock' => 9,
                'image' => 'product_images/aura-lamp.svg',
            ],
            [
                'category_id' => 3,
                'name' => 'Botella ThermalFit 1L',
                'description' => 'Botella térmica de acero inoxidable que conserva la temperatura y acompaña entrenamientos, oficina o viajes.',
                'price' => 24.90,
                'stock' => 30,
                'image' => 'product_images/thermal-bottle.svg',
            ],
            [
                'category_id' => 2,
                'name' => 'Hoodie Urban Cloud',
                'description' => 'Buzo oversize con interior suave, tela de alto gramaje y corte moderno pensado para comodidad diaria.',
                'price' => 49.90,
                'stock' => 16,
                'image' => 'product_images/urban-hoodie.svg',
            ],
            [
                'category_id' => 6,
                'name' => 'Clean Architecture Playbook',
                'description' => 'Guía práctica para diseñar software mantenible, enfocada en arquitectura, testing y decisiones técnicas claras.',
                'price' => 31.00,
                'stock' => 22,
                'image' => 'product_images/architecture-book.svg',
            ],
            [
                'category_id' => 10,
                'name' => 'Kit Zen Balance',
                'description' => 'Set de bienestar con roller facial, vela aromática y accesorios para una rutina de autocuidado más completa.',
                'price' => 37.80,
                'stock' => 14,
                'image' => 'product_images/zen-kit.svg',
            ],
            [
                'category_id' => 16,
                'name' => 'Mochila WorkFlow Tech',
                'description' => 'Mochila elegante con compartimento acolchado para laptop, bolsillos internos y diseño pensado para trabajo híbrido.',
                'price' => 74.99,
                'stock' => 11,
                'image' => 'product_images/workflow-backpack.svg',
            ],
        ];

        foreach ($products as $data) {
            $category = Category::query()->find($data['category_id']);

            if (! $category) {
                continue;
            }

            $product = Product::query()->updateOrCreate(
                ['name' => $data['name']],
                [
                    'user_id' => $seller->id,
                    'category_id' => $category->id,
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'stock' => $data['stock'],
                ]
            );

            ProductImage::query()->updateOrCreate(
                ['product_id' => $product->id],
                ['image_path' => $data['image']]
            );
        }
    }
}
