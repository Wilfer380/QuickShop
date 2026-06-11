<?php

namespace App\Support;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryVisuals
{
    public static function imageUrl(Category $category): string
    {
        $path = self::representativeImagePath($category);

        return $path
            ? asset('storage/' . $path)
            : self::fallbackDataUri($category);
    }

    public static function representativeImagePath(Category $category): ?string
    {
        $category->loadMissing(['products.productImages', 'children.products.productImages']);

        if ($path = self::firstImagePath($category->products)) {
            return $path;
        }

        if ($category->parent_id !== null) {
            return null;
        }

        foreach ($category->children as $child) {
            if ($path = self::firstImagePath($child->products)) {
                return $path;
            }
        }

        return null;
    }

    public static function fallbackDataUri(Category $category): string
    {
        $palette = self::paletteFor($category->name);
        $title = htmlspecialchars(Str::limit($category->name, 22), ENT_QUOTES, 'UTF-8');
        $subtitle = htmlspecialchars(Str::limit($category->description ?? 'Explorá el catálogo', 34), ENT_QUOTES, 'UTF-8');
        $emoji = htmlspecialchars($palette['emoji'], ENT_QUOTES, 'UTF-8');
        $label = htmlspecialchars($palette['label'], ENT_QUOTES, 'UTF-8');

        $svg = "<svg xmlns='http://www.w3.org/2000/svg' width='800' height='800' viewBox='0 0 800 800'>
            <defs>
                <linearGradient id='g' x1='0' y1='0' x2='1' y2='1'>
                    <stop offset='0%' stop-color='{$palette['start']}' />
                    <stop offset='100%' stop-color='{$palette['end']}' />
                </linearGradient>
            </defs>
            <rect width='800' height='800' rx='42' fill='url(#g)'/>
            <rect x='44' y='44' width='712' height='712' rx='32' fill='rgba(15,23,42,0.16)' stroke='rgba(255,255,255,0.18)'/>
            <text x='72' y='126' fill='rgba(255,255,255,0.92)' font-family='Arial, Helvetica, sans-serif' font-size='24' font-weight='700' letter-spacing='3'>VEHIPARK</text>
            <text x='72' y='212' fill='white' font-family='Arial, Helvetica, sans-serif' font-size='44' font-weight='800'>{$title}</text>
            <text x='72' y='268' fill='rgba(255,255,255,0.9)' font-family='Arial, Helvetica, sans-serif' font-size='22' font-weight='600'>{$subtitle}</text>
            <rect x='72' y='326' width='248' height='54' rx='27' fill='rgba(255,255,255,0.18)'/>
            <text x='96' y='362' fill='{$palette['accent']}' font-family='Arial, Helvetica, sans-serif' font-size='22' font-weight='800'>{$label}</text>
            <circle cx='602' cy='292' r='128' fill='rgba(255,255,255,0.14)'/>
            <text x='602' y='336' text-anchor='middle' fill='white' font-family='Segoe UI Emoji, Apple Color Emoji, Noto Color Emoji, Arial, sans-serif' font-size='108'>{$emoji}</text>
            <text x='72' y='610' fill='white' font-family='Arial, Helvetica, sans-serif' font-size='28' font-weight='700'>Imagen representativa</text>
            <text x='72' y='652' fill='rgba(255,255,255,0.88)' font-family='Arial, Helvetica, sans-serif' font-size='20' font-weight='500'>Visual pensada para esta colección</text>
        </svg>";

        return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    }

    private static function firstImagePath($products): ?string
    {
        foreach ($products as $product) {
            $imagePath = optional($product->productImages->first())->image_path;

            if ($imagePath) {
                return $imagePath;
            }
        }

        return null;
    }

    private static function paletteFor(string $categoryName): array
    {
        return match ($categoryName) {
            'Tecnología', 'Audio', 'Computación' => ['emoji' => '💻', 'label' => 'Tech', 'start' => '#0EA5E9', 'end' => '#2563EB', 'accent' => '#BAE6FD'],
            'Electrónica' => ['emoji' => '🎧', 'label' => 'Electrónica', 'start' => '#111827', 'end' => '#2563EB', 'accent' => '#BFDBFE'],
            'Ropa' => ['emoji' => '👕', 'label' => 'Moda', 'start' => '#EC4899', 'end' => '#F97316', 'accent' => '#FBCFE8'],
            'Deportes', 'Deportes al Aire Libre' => ['emoji' => '🏋️', 'label' => 'Deportes', 'start' => '#16A34A', 'end' => '#0F766E', 'accent' => '#DCFCE7'],
            'Hogar y Decoración', 'Muebles' => ['emoji' => '🛋️', 'label' => 'Hogar', 'start' => '#F59E0B', 'end' => '#D97706', 'accent' => '#FEF3C7'],
            'Belleza' => ['emoji' => '💄', 'label' => 'Beauty', 'start' => '#FB7185', 'end' => '#E11D48', 'accent' => '#FFE4E6'],
            'Libros' => ['emoji' => '📚', 'label' => 'Lectura', 'start' => '#7C3AED', 'end' => '#4F46E5', 'accent' => '#E9D5FF'],
            'Juguetes' => ['emoji' => '🧸', 'label' => 'Kids', 'start' => '#F97316', 'end' => '#FB7185', 'accent' => '#FFEDD5'],
            'Alimentos', 'Alimentos y Bebidas', 'Cervezas Artesanales y Licores' => ['emoji' => '🍎', 'label' => 'Food', 'start' => '#F97316', 'end' => '#EF4444', 'accent' => '#FED7AA'],
            'Automotriz' => ['emoji' => '🚗', 'label' => 'Auto', 'start' => '#334155', 'end' => '#475569', 'accent' => '#CBD5E1'],
            'Salud y Bienestar' => ['emoji' => '💊', 'label' => 'Health', 'start' => '#14B8A6', 'end' => '#0EA5E9', 'accent' => '#CCFBF1'],
            'Bebé y Niños' => ['emoji' => '🍼', 'label' => 'Baby', 'start' => '#38BDF8', 'end' => '#818CF8', 'accent' => '#DBEAFE'],
            'Electrodomésticos', 'Limpieza del hogar' => ['emoji' => '🧺', 'label' => 'Hogar', 'start' => '#0F172A', 'end' => '#0EA5E9', 'accent' => '#CFFAFE'],
            'Cocina' => ['emoji' => '🍳', 'label' => 'Kitchen', 'start' => '#F97316', 'end' => '#EF4444', 'accent' => '#FFEDD5'],
            'Lavado' => ['emoji' => '🫧', 'label' => 'Laundry', 'start' => '#0EA5E9', 'end' => '#1D4ED8', 'accent' => '#DBEAFE'],
            'Refrigeración' => ['emoji' => '🧊', 'label' => 'Cooling', 'start' => '#22C55E', 'end' => '#14B8A6', 'accent' => '#DCFCE7'],
            'Climatización' => ['emoji' => '🌬️', 'label' => 'Climate', 'start' => '#38BDF8', 'end' => '#0F766E', 'accent' => '#E0F2FE'],
            'Joyería y Relojes' => ['emoji' => '⌚', 'label' => 'Jewelry', 'start' => '#A855F7', 'end' => '#EC4899', 'accent' => '#F3E8FF'],
            'Música y Películas' => ['emoji' => '🎬', 'label' => 'Media', 'start' => '#6366F1', 'end' => '#8B5CF6', 'accent' => '#E0E7FF'],
            'Material de Oficina' => ['emoji' => '🗂️', 'label' => 'Office', 'start' => '#0F766E', 'end' => '#14B8A6', 'accent' => '#CCFBF1'],
            'Mascotas' => ['emoji' => '🐾', 'label' => 'Pets', 'start' => '#F97316', 'end' => '#A16207', 'accent' => '#FEF3C7'],
            'Papelería' => ['emoji' => '✏️', 'label' => 'Stationery', 'start' => '#2563EB', 'end' => '#06B6D4', 'accent' => '#DBEAFE'],
            'Jardín y Exterior' => ['emoji' => '🪴', 'label' => 'Garden', 'start' => '#15803D', 'end' => '#65A30D', 'accent' => '#DCFCE7'],
            'Instrumentos Musicales' => ['emoji' => '🎸', 'label' => 'Music', 'start' => '#7C2D12', 'end' => '#F97316', 'accent' => '#FFEDD5'],
            'Viajes y Equipaje' => ['emoji' => '🧳', 'label' => 'Travel', 'start' => '#0F172A', 'end' => '#334155', 'accent' => '#E2E8F0'],
            'Arte y Manualidades' => ['emoji' => '🎨', 'label' => 'Creative', 'start' => '#9333EA', 'end' => '#F43F5E', 'accent' => '#F5D0FE'],
            'Vintage' => ['emoji' => '📻', 'label' => 'Retro', 'start' => '#A16207', 'end' => '#D97706', 'accent' => '#FEF3C7'],
            'Lujo' => ['emoji' => '💎', 'label' => 'Luxury', 'start' => '#0F172A', 'end' => '#6D28D9', 'accent' => '#EDE9FE'],
            'Regalos y Ocasiones' => ['emoji' => '🎁', 'label' => 'Gifts', 'start' => '#DC2626', 'end' => '#F97316', 'accent' => '#FEE2E2'],
            'Coleccionables' => ['emoji' => '🃏', 'label' => 'Collectibles', 'start' => '#1D4ED8', 'end' => '#7C3AED', 'accent' => '#DBEAFE'],
            default => ['emoji' => '🛍️', 'label' => 'Catalog', 'start' => '#F97316', 'end' => '#FB7185', 'accent' => '#FED7AA'],
        };
    }
}
