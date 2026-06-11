<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Autos', 'description' => 'Vehiculos livianos para administracion diaria.', 'sort_order' => 10],
            ['name' => 'Camionetas', 'description' => 'Unidades utilitarias, SUV y pickups.', 'sort_order' => 20],
            ['name' => 'Motos', 'description' => 'Motocicletas y movilidad de baja ocupacion.', 'sort_order' => 30],
            ['name' => 'Cupos operativos', 'description' => 'Zonas internas y espacios administrados.', 'sort_order' => 40],
            ['name' => 'Servicios', 'description' => 'Servicios asociados a parqueadero y operacion.', 'sort_order' => 50],
        ];

        $subcategories = [
            ['parent' => 'Autos', 'name' => 'Sedan', 'description' => 'Autos sedan de uso ejecutivo.', 'sort_order' => 10],
            ['parent' => 'Autos', 'name' => 'Hatchback', 'description' => 'Autos compactos para rotacion urbana.', 'sort_order' => 20],
            ['parent' => 'Camionetas', 'name' => 'SUV', 'description' => 'Camionetas SUV para flota mixta.', 'sort_order' => 10],
            ['parent' => 'Camionetas', 'name' => 'Pickup', 'description' => 'Pickups y unidades de carga liviana.', 'sort_order' => 20],
            ['parent' => 'Motos', 'name' => 'Urbana', 'description' => 'Motos para desplazamiento urbano.', 'sort_order' => 10],
            ['parent' => 'Cupos operativos', 'name' => 'Cubierto', 'description' => 'Cupos cubiertos para asignacion interna.', 'sort_order' => 10],
            ['parent' => 'Cupos operativos', 'name' => 'Descubierto', 'description' => 'Cupos descubiertos para rotacion diaria.', 'sort_order' => 20],
            ['parent' => 'Servicios', 'name' => 'Lavado', 'description' => 'Servicio de lavado y alistamiento.', 'sort_order' => 10],
            ['parent' => 'Servicios', 'name' => 'Mantenimiento', 'description' => 'Mantenimiento preventivo y operativo.', 'sort_order' => 20],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['name' => $category['name']],
                [
                    'description' => $category['description'],
                    'sort_order' => $category['sort_order'],
                    'parent_id' => null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        foreach ($subcategories as $subcategory) {
            $parentId = DB::table('categories')
                ->where('name', $subcategory['parent'])
                ->value('id');

            if (! $parentId) {
                continue;
            }

            DB::table('categories')->updateOrInsert(
                ['name' => $subcategory['name'], 'parent_id' => $parentId],
                [
                    'description' => $subcategory['description'],
                    'sort_order' => $subcategory['sort_order'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
