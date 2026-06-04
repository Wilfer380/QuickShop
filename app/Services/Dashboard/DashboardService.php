<?php

namespace App\Services\Dashboard;

use App\Models\Category;
use App\Models\User;
use App\Models\VehiclePublication;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class DashboardService
{
    public function summary(): array
    {
        $catalogFamilies = Category::query()
            ->whereNull('parent_id')
            ->withCount('products')
            ->with([
                'products' => fn ($query) => $query->with('productImages')->orderBy('id'),
                'children' => fn ($query) => $query->withCount('products')
                    ->with(['products' => fn ($productQuery) => $productQuery->with('productImages')->orderBy('id')])
                    ->whereHas('products')
                    ->ordered(),
            ])
            ->ordered()
            ->get()
            ->filter(fn (Category $family) => $family->products->isNotEmpty() || $family->children->isNotEmpty())
            ->values();

        $allCategories = $catalogFamilies
            ->flatMap(fn (Category $family) => collect([$family])->merge($family->children))
            ->keyBy('id');

        $selectedCategoryId = request()->query('c');
        $search = trim((string) request()->query('q', ''));
        $sort = request()->query('sort', 'newest');
        $editVehicleId = request()->query('edit');
        $selectedCategory = $selectedCategoryId ? $allCategories->firstWhere('id', (int) $selectedCategoryId) : null;
        $selectedCategoryId = $selectedCategory?->id;
        $selectedFamily = $selectedCategory?->parent_id
            ? $catalogFamilies->firstWhere('id', $selectedCategory->parent_id)
            : $selectedCategory;
        $selectedSubcategory = $selectedCategory?->parent_id ? $selectedCategory : null;

        $productsQuery = VehiclePublication::query()
            ->with(['productImages', 'category.parent', 'user'])
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $productQuery) use ($search) {
                    $productQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            });

        if ($selectedCategory) {
            $categoryIds = [$selectedCategory->id];

            if ($selectedCategory->parent_id === null) {
                $categoryIds = array_merge($categoryIds, $selectedCategory->children->pluck('id')->all());
            }

            $productsQuery->whereIn('category_id', $categoryIds);
        }

        match ($sort) {
            'price_asc' => $productsQuery->orderBy('price'),
            'price_desc' => $productsQuery->orderByDesc('price'),
            'stock_desc' => $productsQuery->orderByDesc('stock'),
            default => $productsQuery->latest(),
        };

        $publications = $productsQuery->get();
        $products = $publications;
        $categories = Category::with('parent')->orderBy('parent_id')->orderBy('name')->get();
        $editVehicle = $editVehicleId
            ? VehiclePublication::with(['productImages', 'category.parent'])->find($editVehicleId)
            : null;
        $vehicleStats = [
            'total' => VehiclePublication::count(),
            'available' => VehiclePublication::where('stock', '>', 0)->count(),
            'segments' => Category::count(),
            'employees' => User::count(),
            'inventoryValue' => (float) VehiclePublication::query()->selectRaw('COALESCE(SUM(price * stock), 0) as value')->value('value'),
        ];
        $catalogMood = $this->catalogMood($selectedFamily, $selectedSubcategory, $publications);

        return compact(
            'publications',
            'products',
            'catalogFamilies',
            'categories',
            'selectedCategoryId',
            'selectedCategory',
            'selectedFamily',
            'selectedSubcategory',
            'search',
            'sort',
            'catalogMood',
            'editVehicle',
            'vehicleStats'
        );
    }

    private function catalogMood(?Category $selectedFamily, ?Category $selectedSubcategory, Collection $products): array
    {
        $familyName = $selectedFamily?->name;
        $categoryName = $selectedSubcategory?->name ?? $familyName;
        $defaultTitle = $categoryName ? "Segmento operativo: {$categoryName}" : 'Panel de administracion de flota';
        $chips = $selectedFamily
            ? [$selectedFamily->name, 'Disponibilidad', 'Asignacion', 'Mantenimiento']
            : ['Disponibilidad', 'Asignacion', 'Mantenimiento', 'Administracion'];

        if ($selectedSubcategory && ! in_array($selectedSubcategory->name, $chips, true)) {
            array_unshift($chips, $selectedSubcategory->name);
        }

        if ($selectedFamily && $selectedFamily->children->isNotEmpty()) {
            $chips = array_slice(array_merge($chips, $selectedFamily->children->pluck('name')->take(4)->all()), 0, 4);
        }

        return [
            'eyebrow' => $selectedSubcategory && $selectedFamily ? $selectedFamily->name . ' / ' . $selectedSubcategory->name : 'VehiPark Control',
            'title' => $selectedSubcategory ? 'Administrando ' . $selectedSubcategory->name : $defaultTitle,
            'subtitle' => 'Gestiona ventas, parqueadero, cupos, tarifas y pagos desde una experiencia interna.',
            'chips' => $chips,
            'accent' => '#38BDF8',
            'surface' => '#0F172A',
            'highlight' => $categoryName ? "Inventario enfocado en {$categoryName}" : 'Inventario general de flota',
            'spotlightProducts' => $products
                ->take(4)
                ->map(fn (VehiclePublication $product) => [
                    'name' => $product->name,
                    'price' => $product->price,
                    'stock' => $product->stock,
                ])
                ->values()
                ->all(),
        ];
    }
}
