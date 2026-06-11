<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $responsible = User::query()->where('email', 'supervisor@vehipark.test')->first();

        if (! $responsible) {
            return;
        }

        $inventory = [
            ['category' => 'Sedan', 'name' => 'Toyota Corolla Operativo', 'price' => 72000, 'stock' => 4, 'description' => 'Unidad sedan para turnos administrativos y traslados internos.'],
            ['category' => 'Hatchback', 'name' => 'Renault Sandero Urbano', 'price' => 48000, 'stock' => 6, 'description' => 'Vehiculo compacto para rotacion diaria y desplazamientos urbanos.'],
            ['category' => 'SUV', 'name' => 'Mazda CX-5 Supervisoria', 'price' => 98000, 'stock' => 3, 'description' => 'SUV asignada a supervision operativa y recorridos de control.'],
            ['category' => 'Pickup', 'name' => 'Toyota Hilux Logistica', 'price' => 112000, 'stock' => 2, 'description' => 'Pickup para apoyo logistico, mantenimiento y transporte liviano.'],
            ['category' => 'Urbana', 'name' => 'Yamaha NMAX Mensajeria', 'price' => 18000, 'stock' => 8, 'description' => 'Moto urbana para recorridos rapidos y soporte entre sedes.'],
            ['category' => 'Cubierto', 'name' => 'Cupo Cubierto Premium', 'price' => 220, 'stock' => 18, 'description' => 'Cupo cubierto para asignacion mensual o vehiculos preferenciales.'],
            ['category' => 'Descubierto', 'name' => 'Cupo Descubierto Rotativo', 'price' => 140, 'stock' => 36, 'description' => 'Cupo descubierto para rotacion diaria y operacion general.'],
            ['category' => 'Lavado', 'name' => 'Servicio Lavado Express', 'price' => 35, 'stock' => 20, 'description' => 'Servicio rapido de lavado para unidades en salida operativa.'],
            ['category' => 'Mantenimiento', 'name' => 'Revision Preventiva Basica', 'price' => 95, 'stock' => 12, 'description' => 'Revision preventiva para mantener disponibilidad de flota.'],
        ];

        $directory = storage_path('app/public/vehicle_inventory/demo');

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        foreach ($inventory as $item) {
            $category = Category::query()->where('name', $item['category'])->first();

            if (! $category) {
                continue;
            }

            $vehicle = Product::query()->updateOrCreate(
                ['name' => $item['name']],
                [
                    'user_id' => $responsible->id,
                    'category_id' => $category->id,
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                ]
            );

            $fileName = Str::slug($item['name']) . '.svg';
            $relativePath = 'vehicle_inventory/demo/' . $fileName;
            $absolutePath = storage_path('app/public/' . $relativePath);

            File::put($absolutePath, $this->buildSvg($item['name'], $item['category']));

            ProductImage::query()->updateOrCreate(
                ['product_id' => $vehicle->id],
                ['image_path' => $relativePath]
            );
        }
    }

    private function buildSvg(string $vehicleName, string $segmentName): string
    {
        $safeVehicle = htmlspecialchars($vehicleName, ENT_QUOTES, 'UTF-8');
        $safeSegment = htmlspecialchars($segmentName, ENT_QUOTES, 'UTF-8');

        return <<<SVG
<svg width="1200" height="900" viewBox="0 0 1200 900" fill="none" xmlns="http://www.w3.org/2000/svg">
  <rect width="1200" height="900" rx="48" fill="#020617"/>
  <rect x="72" y="72" width="1056" height="756" rx="40" fill="url(#paint0_linear)" stroke="rgba(255,255,255,0.18)" stroke-width="2"/>
  <circle cx="950" cy="190" r="170" fill="rgba(56,189,248,0.22)"/>
  <circle cx="210" cy="720" r="180" fill="rgba(249,115,22,0.16)"/>
  <text x="120" y="178" fill="#E0F2FE" font-family="Arial, Helvetica, sans-serif" font-size="32" font-weight="800" letter-spacing="4">VEHIPARK</text>
  <text x="120" y="275" fill="#FFFFFF" font-family="Arial, Helvetica, sans-serif" font-size="68" font-weight="900">{$safeVehicle}</text>
  <text x="120" y="344" fill="#CBD5E1" font-family="Arial, Helvetica, sans-serif" font-size="30" font-weight="700">Segmento: {$safeSegment}</text>
  <rect x="120" y="430" width="560" height="170" rx="34" fill="rgba(15,23,42,0.58)" stroke="rgba(255,255,255,0.16)"/>
  <text x="158" y="506" fill="#FFFFFF" font-family="Arial, Helvetica, sans-serif" font-size="34" font-weight="800">Inventario operativo</text>
  <text x="158" y="560" fill="#E2E8F0" font-family="Arial, Helvetica, sans-serif" font-size="25" font-weight="600">Unidad administrada desde el panel de flota.</text>
  <rect x="770" y="424" width="250" height="140" rx="34" fill="rgba(255,255,255,0.14)"/>
  <text x="895" y="516" text-anchor="middle" fill="#FFFFFF" font-family="Arial, Helvetica, sans-serif" font-size="72" font-weight="900">VHP</text>
  <defs>
    <linearGradient id="paint0_linear" x1="80" y1="80" x2="1120" y2="820" gradientUnits="userSpaceOnUse">
      <stop stop-color="#0F172A"/>
      <stop offset="0.55" stop-color="#1D4ED8"/>
      <stop offset="1" stop-color="#F97316"/>
    </linearGradient>
  </defs>
</svg>
SVG;
    }
}
