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
        $seller = User::query()->where('email', 'seller@gmail.com')->first();

        if (! $seller) {
            return;
        }

        $catalog = [
            ['category' => 'Tecnología', 'name' => 'Laptop Stand Orbit', 'price' => 89.50, 'stock' => 18, 'description' => 'Soporte premium para laptop con diseño moderno, ventilación y postura ergonómica para trabajo diario.'],
            ['category' => 'Ropa', 'name' => 'Hoodie Urban Cloud', 'price' => 49.90, 'stock' => 16, 'description' => 'Buzo oversize con interior suave, tela de alto gramaje y corte moderno pensado para comodidad diaria.'],
            ['category' => 'Deportes', 'name' => 'Botella ThermalFit 1L', 'price' => 24.90, 'stock' => 30, 'description' => 'Botella térmica de acero inoxidable que conserva la temperatura y acompaña entrenamientos, oficina o viajes.'],
            ['category' => 'Hogar y Decoración', 'name' => 'Lámpara Aura Desk', 'price' => 54.90, 'stock' => 9, 'description' => 'Lámpara LED minimalista con luz regulable y acabado premium para escritorio, estudio o espacio creativo.'],
            ['category' => 'Belleza', 'name' => 'Serum Glow Ritual', 'price' => 29.90, 'stock' => 20, 'description' => 'Sérum facial hidratante con textura ligera, ideal para rutinas de cuidado diario con acabado luminoso.'],
            ['category' => 'Libros', 'name' => 'Clean Architecture Playbook', 'price' => 31.00, 'stock' => 22, 'description' => 'Guía práctica para diseñar software mantenible, enfocada en arquitectura, testing y decisiones técnicas claras.'],
            ['category' => 'Juguetes', 'name' => 'Robot Explorer Mini', 'price' => 42.00, 'stock' => 13, 'description' => 'Juguete interactivo con luces, sonidos y piezas móviles para estimular curiosidad y juego creativo.'],
            ['category' => 'Alimentos', 'name' => 'Snack Box Daily Mix', 'price' => 18.50, 'stock' => 26, 'description' => 'Caja surtida de snacks para oficina, estudio o casa con mezcla de opciones dulces y saladas.'],
            ['category' => 'Automotriz', 'name' => 'Kit RoadSafe Essential', 'price' => 39.99, 'stock' => 12, 'description' => 'Kit básico para carretera con accesorios útiles para orden, seguridad y mantenimiento rápido del vehículo.'],
            ['category' => 'Salud y Bienestar', 'name' => 'Relief Care Essentials', 'price' => 37.80, 'stock' => 14, 'description' => 'Kit de bienestar con organizador de medicamentos, termómetro y accesorios pensados para una rutina de cuidado.'],
            ['category' => 'Bebé y Niños', 'name' => 'Baby Care Comfort Set', 'price' => 34.75, 'stock' => 15, 'description' => 'Set pensado para cuidado diario con artículos suaves, prácticos y cómodos para bebés y primera infancia.'],
            ['category' => 'Electrónica', 'name' => 'Auriculares NovaSound Pro', 'price' => 129.99, 'stock' => 12, 'description' => 'Auriculares inalámbricos con cancelación de ruido, batería de larga duración y almohadillas cómodas para trabajo, gaming y música diaria.'],
            ['category' => 'Joyería y Relojes', 'name' => 'Reloj Silver Classic', 'price' => 64.90, 'stock' => 10, 'description' => 'Reloj de diseño elegante con acabado plateado y estilo versátil para uso diario o eventos especiales.'],
            ['category' => 'Música y Películas', 'name' => 'Vinyl Session Collection', 'price' => 27.40, 'stock' => 11, 'description' => 'Selección de discos y contenido audiovisual pensada para coleccionistas y amantes del entretenimiento en casa.'],
            ['category' => 'Alimentos y Bebidas', 'name' => 'Coffee Break Premium Pack', 'price' => 21.80, 'stock' => 24, 'description' => 'Pack gourmet con café, acompañamientos y detalles ideales para pausa de oficina o regalo especial.'],
            ['category' => 'Material de Oficina', 'name' => 'DeskFlow Organizer Set', 'price' => 34.99, 'stock' => 11, 'description' => 'Organizador de escritorio con bandejas, portalápices y accesorios para mantener orden y productividad.'],
            ['category' => 'Mascotas', 'name' => 'Pet Play Comfort Kit', 'price' => 28.60, 'stock' => 19, 'description' => 'Kit con accesorios de juego y confort para mascotas, pensado para rutina diaria, descanso y entretenimiento.'],
            ['category' => 'Papelería', 'name' => 'Notebook Studio Pack', 'price' => 19.20, 'stock' => 21, 'description' => 'Set de cuadernos y accesorios de escritura con look limpio y funcional para estudio o trabajo.'],
            ['category' => 'Jardín y Exterior', 'name' => 'Garden Ease Starter Set', 'price' => 33.50, 'stock' => 14, 'description' => 'Set inicial para jardín y exterior con accesorios útiles para mantenimiento ligero y organización del espacio.'],
            ['category' => 'Instrumentos Musicales', 'name' => 'Acoustic Jam Starter', 'price' => 119.00, 'stock' => 8, 'description' => 'Kit básico para músicos con enfoque práctico, estilo y uso cotidiano desde el primer día.'],
            ['category' => 'Viajes y Equipaje', 'name' => 'Voyager Cabin Bag', 'price' => 58.90, 'stock' => 17, 'description' => 'Bolso de viaje compacto con compartimentos funcionales y diseño listo para escapadas o trabajo.'],
            ['category' => 'Arte y Manualidades', 'name' => 'Creative Brush Box', 'price' => 26.30, 'stock' => 18, 'description' => 'Caja creativa con herramientas y materiales ideales para dibujo, pintura o proyectos hechos a mano.'],
            ['category' => 'Vintage', 'name' => 'Retro Deco Select', 'price' => 47.00, 'stock' => 7, 'description' => 'Pieza con estética retro pensada para dar personalidad y estilo a espacios o colecciones personales.'],
            ['category' => 'Muebles', 'name' => 'Nordic Side Table', 'price' => 79.50, 'stock' => 9, 'description' => 'Mesa auxiliar compacta con diseño moderno y funcional para sala, estudio o dormitorio.'],
            ['category' => 'Deportes al Aire Libre', 'name' => 'Outdoor Trail Companion', 'price' => 44.80, 'stock' => 13, 'description' => 'Accesorio listo para actividades al aire libre con foco en resistencia, practicidad y movimiento.'],
            ['category' => 'Lujo', 'name' => 'Gold Signature Edition', 'price' => 159.00, 'stock' => 6, 'description' => 'Producto premium con presencia elegante, materiales visualmente refinados y propuesta de alto valor percibido.'],
            ['category' => 'Regalos y Ocasiones', 'name' => 'Celebration Gift Box', 'price' => 35.90, 'stock' => 16, 'description' => 'Caja pensada para regalar con presentación cuidada y combinación versátil para distintas ocasiones.'],
            ['category' => 'Cervezas Artesanales y Licores', 'name' => 'Craft Reserve Tasting Set', 'price' => 46.70, 'stock' => 10, 'description' => 'Set de degustación con presentación atractiva, ideal para ocasiones especiales y amantes de sabores intensos.'],
            ['category' => 'Coleccionables', 'name' => 'Collector Display Piece', 'price' => 52.40, 'stock' => 8, 'description' => 'Artículo pensado para colección y exhibición, con identidad fuerte y presencia visual destacada.'],
        ];

        $directory = public_path('storage/product_images/demo');

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        foreach ($catalog as $item) {
            $category = Category::query()->where('name', $item['category'])->first();

            if (! $category) {
                continue;
            }

            $product = Product::query()->updateOrCreate(
                ['name' => $item['name']],
                [
                    'user_id' => $seller->id,
                    'category_id' => $category->id,
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                ]
            );

            $fileName = Str::slug($item['name']) . '.svg';
            $relativePath = 'product_images/demo/' . $fileName;
            $absolutePath = public_path('storage/' . $relativePath);

            File::put($absolutePath, $this->buildSvg($item['name'], $item['category']));

            ProductImage::query()->updateOrCreate(
                ['product_id' => $product->id],
                ['image_path' => $relativePath]
            );
        }
    }

    private function buildSvg(string $productName, string $categoryName): string
    {
        $visual = $this->getCategoryVisual($categoryName);
        $safeProduct = htmlspecialchars($productName, ENT_QUOTES, 'UTF-8');
        $safeCategory = htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8');
        $safeLabel = htmlspecialchars($visual['label'], ENT_QUOTES, 'UTF-8');
        $safeIcon = htmlspecialchars($visual['icon'], ENT_QUOTES, 'UTF-8');
        $start = $visual['start'];
        $end = $visual['end'];
        $accent = $visual['accent'];
        $soft = $visual['soft'];

        return <<<SVG
<svg width="1200" height="900" viewBox="0 0 1200 900" fill="none" xmlns="http://www.w3.org/2000/svg">
  <rect width="1200" height="900" rx="48" fill="#0F172A"/>
  <rect x="44" y="44" width="1112" height="812" rx="36" fill="url(#paint0_linear)"/>
  <circle cx="1000" cy="150" r="150" fill="rgba(255,255,255,0.14)"/>
  <circle cx="170" cy="760" r="180" fill="rgba(255,255,255,0.08)"/>
  <rect x="96" y="96" width="1008" height="708" rx="28" fill="rgba(15,23,42,0.22)" stroke="rgba(255,255,255,0.18)" stroke-width="2"/>
  <text x="120" y="180" fill="#FFF7ED" font-family="Arial, Helvetica, sans-serif" font-size="30" font-weight="700" letter-spacing="3">QUICKSHOP</text>
  <text x="120" y="250" fill="white" font-family="Arial, Helvetica, sans-serif" font-size="64" font-weight="800">{$safeProduct}</text>
  <text x="120" y="314" fill="#F8FAFC" font-family="Arial, Helvetica, sans-serif" font-size="28" font-weight="600">Categoría: {$safeCategory}</text>
  <rect x="120" y="360" width="280" height="64" rx="32" fill="rgba(15,23,42,0.32)" stroke="rgba(255,255,255,0.16)"/>
  <text x="160" y="401" fill="{$accent}" font-family="Arial, Helvetica, sans-serif" font-size="28" font-weight="700">{$safeLabel}</text>
  <rect x="720" y="206" width="250" height="250" rx="60" fill="rgba(255,255,255,0.16)"/>
  <circle cx="845" cy="331" r="94" fill="{$soft}"/>
  <text x="845" y="365" text-anchor="middle" fill="white" font-family="'Segoe UI Emoji','Apple Color Emoji','Noto Color Emoji',Arial,sans-serif" font-size="120">{$safeIcon}</text>
  <rect x="120" y="498" width="528" height="204" rx="28" fill="rgba(255,255,255,0.12)"/>
  <text x="156" y="578" fill="#FFFFFF" font-family="Arial, Helvetica, sans-serif" font-size="34" font-weight="700">Imagen representativa de {$safeCategory}</text>
  <text x="156" y="630" fill="#E2E8F0" font-family="Arial, Helvetica, sans-serif" font-size="24" font-weight="500">Cada categoría usa una visual distinta acorde al producto.</text>
  <text x="156" y="670" fill="#E2E8F0" font-family="Arial, Helvetica, sans-serif" font-size="24" font-weight="500">Así el catálogo se entiende mucho mejor a simple vista.</text>
  <rect x="720" y="520" width="284" height="120" rx="26" fill="rgba(15,23,42,0.22)" stroke="rgba(255,255,255,0.16)"/>
  <text x="760" y="575" fill="#FFFFFF" font-family="Arial, Helvetica, sans-serif" font-size="26" font-weight="700">Visual category match</text>
  <text x="760" y="615" fill="#E2E8F0" font-family="Arial, Helvetica, sans-serif" font-size="22" font-weight="500">{$safeCategory}</text>
  <defs>
    <linearGradient id="paint0_linear" x1="100" y1="80" x2="1100" y2="820" gradientUnits="userSpaceOnUse">
      <stop stop-color="{$start}"/>
      <stop offset="0.55" stop-color="{$end}"/>
      <stop offset="1" stop-color="#1D4ED8"/>
    </linearGradient>
  </defs>
</svg>
SVG;
    }

    private function getCategoryVisual(string $categoryName): array
    {
        return match ($categoryName) {
            'Tecnología' => ['icon' => '💻', 'label' => 'Tecnología', 'start' => '#0EA5E9', 'end' => '#2563EB', 'accent' => '#BAE6FD', 'soft' => 'rgba(14,165,233,0.28)'],
            'Ropa' => ['icon' => '👕', 'label' => 'Moda', 'start' => '#EC4899', 'end' => '#F97316', 'accent' => '#FBCFE8', 'soft' => 'rgba(236,72,153,0.28)'],
            'Deportes' => ['icon' => '🏋️', 'label' => 'Fitness', 'start' => '#16A34A', 'end' => '#22C55E', 'accent' => '#DCFCE7', 'soft' => 'rgba(34,197,94,0.28)'],
            'Hogar y Decoración' => ['icon' => '🛋️', 'label' => 'Hogar', 'start' => '#F59E0B', 'end' => '#D97706', 'accent' => '#FEF3C7', 'soft' => 'rgba(245,158,11,0.28)'],
            'Belleza' => ['icon' => '💄', 'label' => 'Beauty', 'start' => '#FB7185', 'end' => '#E11D48', 'accent' => '#FFE4E6', 'soft' => 'rgba(251,113,133,0.28)'],
            'Libros' => ['icon' => '📚', 'label' => 'Lectura', 'start' => '#7C3AED', 'end' => '#4F46E5', 'accent' => '#E9D5FF', 'soft' => 'rgba(124,58,237,0.28)'],
            'Juguetes' => ['icon' => '🧸', 'label' => 'Kids Play', 'start' => '#F97316', 'end' => '#FB7185', 'accent' => '#FFEDD5', 'soft' => 'rgba(249,115,22,0.28)'],
            'Alimentos' => ['icon' => '🍎', 'label' => 'Food', 'start' => '#F97316', 'end' => '#EF4444', 'accent' => '#FED7AA', 'soft' => 'rgba(239,68,68,0.24)'],
            'Automotriz' => ['icon' => '🚗', 'label' => 'Auto', 'start' => '#334155', 'end' => '#475569', 'accent' => '#CBD5E1', 'soft' => 'rgba(100,116,139,0.24)'],
            'Salud y Bienestar' => ['icon' => '💊', 'label' => 'Health', 'start' => '#14B8A6', 'end' => '#0EA5E9', 'accent' => '#CCFBF1', 'soft' => 'rgba(20,184,166,0.24)'],
            'Bebé y Niños' => ['icon' => '🍼', 'label' => 'Baby Care', 'start' => '#38BDF8', 'end' => '#818CF8', 'accent' => '#DBEAFE', 'soft' => 'rgba(129,140,248,0.24)'],
            'Electrónica' => ['icon' => '🎧', 'label' => 'Audio Tech', 'start' => '#111827', 'end' => '#2563EB', 'accent' => '#BFDBFE', 'soft' => 'rgba(37,99,235,0.24)'],
            'Joyería y Relojes' => ['icon' => '⌚', 'label' => 'Jewelry', 'start' => '#A855F7', 'end' => '#EC4899', 'accent' => '#F3E8FF', 'soft' => 'rgba(168,85,247,0.24)'],
            'Música y Películas' => ['icon' => '🎬', 'label' => 'Media', 'start' => '#6366F1', 'end' => '#8B5CF6', 'accent' => '#E0E7FF', 'soft' => 'rgba(99,102,241,0.24)'],
            'Alimentos y Bebidas' => ['icon' => '☕', 'label' => 'Coffee & Snacks', 'start' => '#92400E', 'end' => '#F97316', 'accent' => '#FED7AA', 'soft' => 'rgba(146,64,14,0.24)'],
            'Material de Oficina' => ['icon' => '🗂️', 'label' => 'Office', 'start' => '#0F766E', 'end' => '#14B8A6', 'accent' => '#CCFBF1', 'soft' => 'rgba(20,184,166,0.24)'],
            'Mascotas' => ['icon' => '🐾', 'label' => 'Pets', 'start' => '#F97316', 'end' => '#A16207', 'accent' => '#FEF3C7', 'soft' => 'rgba(161,98,7,0.24)'],
            'Papelería' => ['icon' => '✏️', 'label' => 'Stationery', 'start' => '#2563EB', 'end' => '#06B6D4', 'accent' => '#DBEAFE', 'soft' => 'rgba(6,182,212,0.24)'],
            'Jardín y Exterior' => ['icon' => '🪴', 'label' => 'Garden', 'start' => '#15803D', 'end' => '#65A30D', 'accent' => '#DCFCE7', 'soft' => 'rgba(101,163,13,0.24)'],
            'Instrumentos Musicales' => ['icon' => '🎸', 'label' => 'Music', 'start' => '#7C2D12', 'end' => '#F97316', 'accent' => '#FFEDD5', 'soft' => 'rgba(249,115,22,0.24)'],
            'Viajes y Equipaje' => ['icon' => '🧳', 'label' => 'Travel', 'start' => '#0F172A', 'end' => '#334155', 'accent' => '#E2E8F0', 'soft' => 'rgba(148,163,184,0.24)'],
            'Arte y Manualidades' => ['icon' => '🎨', 'label' => 'Creative', 'start' => '#9333EA', 'end' => '#F43F5E', 'accent' => '#F5D0FE', 'soft' => 'rgba(244,63,94,0.24)'],
            'Vintage' => ['icon' => '📻', 'label' => 'Retro', 'start' => '#A16207', 'end' => '#D97706', 'accent' => '#FEF3C7', 'soft' => 'rgba(217,119,6,0.24)'],
            'Muebles' => ['icon' => '🪑', 'label' => 'Furniture', 'start' => '#78716C', 'end' => '#A8A29E', 'accent' => '#E7E5E4', 'soft' => 'rgba(168,162,158,0.24)'],
            'Deportes al Aire Libre' => ['icon' => '🥾', 'label' => 'Outdoor', 'start' => '#166534', 'end' => '#0F766E', 'accent' => '#DCFCE7', 'soft' => 'rgba(15,118,110,0.24)'],
            'Lujo' => ['icon' => '💎', 'label' => 'Luxury', 'start' => '#0F172A', 'end' => '#6D28D9', 'accent' => '#EDE9FE', 'soft' => 'rgba(109,40,217,0.24)'],
            'Regalos y Ocasiones' => ['icon' => '🎁', 'label' => 'Gifts', 'start' => '#DC2626', 'end' => '#F97316', 'accent' => '#FEE2E2', 'soft' => 'rgba(220,38,38,0.24)'],
            'Cervezas Artesanales y Licores' => ['icon' => '🍺', 'label' => 'Drinks', 'start' => '#B45309', 'end' => '#F59E0B', 'accent' => '#FEF3C7', 'soft' => 'rgba(245,158,11,0.24)'],
            'Coleccionables' => ['icon' => '🃏', 'label' => 'Collectibles', 'start' => '#1D4ED8', 'end' => '#7C3AED', 'accent' => '#DBEAFE', 'soft' => 'rgba(124,58,237,0.24)'],
            default => ['icon' => '🛍️', 'label' => 'Catalog', 'start' => '#F97316', 'end' => '#FB7185', 'accent' => '#FED7AA', 'soft' => 'rgba(249,115,22,0.24)'],
        };
    }
}
