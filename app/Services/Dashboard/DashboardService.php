<?php

namespace App\Services\Dashboard;

use App\Models\Category;
use App\Models\CupoParqueadero;
use App\Models\MovimientoParqueadero;
use App\Models\Pago;
use App\Models\User;
use App\Models\VehiclePublication;
use App\Models\Venta;
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

        $parkingMovements = MovimientoParqueadero::query()
            ->with(['vehiculo', 'cliente', 'cupo'])
            ->latest('entrada_at')
            ->limit(5)
            ->get();

        $parkingStats = [
            'total' => CupoParqueadero::count(),
            'occupied' => $parkingMovements->count(),
            'reserved' => CupoParqueadero::query()->whereIn('estado', ['reservado', 'reserved'])->count(),
            'maintenance' => CupoParqueadero::query()->whereIn('estado', ['mantenimiento', 'maintenance'])->count(),
            'todayIncome' => (float) Pago::query()->where('concepto', 'parqueadero')->whereDate('pagado_at', today())->sum('valor'),
            'monthIncome' => (float) Pago::query()->where('concepto', 'parqueadero')->whereMonth('pagado_at', now()->month)->whereYear('pagado_at', now()->year)->sum('valor'),
        ];
        $parkingStats['available'] = max(0, $parkingStats['total'] - $parkingStats['occupied'] - $parkingStats['reserved'] - $parkingStats['maintenance']);

        $salesByDay = Venta::query()
            ->whereMonth('fecha_venta', now()->month)
            ->whereYear('fecha_venta', now()->year)
            ->get(['fecha_venta', 'total'])
            ->groupBy(fn (Venta $venta) => optional($venta->fecha_venta)->day)
            ->map(fn (Collection $sales) => $sales->sum('total'));

        $daysInMonth = now()->daysInMonth;
        $salesChart = collect(range(1, $daysInMonth))
            ->map(fn (int $day) => round(((float) ($salesByDay[$day] ?? 0)) / 1000000, 1))
            ->all();

        $salesMonthTotal = (float) Venta::query()
            ->whereMonth('fecha_venta', now()->month)
            ->whereYear('fecha_venta', now()->year)
            ->sum('total');

        $salesAverageDaily = $daysInMonth > 0 ? $salesMonthTotal / $daysInMonth : 0;
        $bestDay = $salesByDay->isNotEmpty() ? (float) $salesByDay->max() : 0;

        $dashboardKpis = [
            ['title' => 'Vehículos disponibles', 'value' => $vehicleStats['available'] ?? 0, 'note' => 'Actualizado ahora', 'iconBg' => 'bg-blue-500', 'iconText' => 'text-white'],
            ['title' => 'Vehículos vendidos', 'value' => VehiclePublication::where('status', 'sold')->count(), 'note' => 'Actualizado ahora', 'iconBg' => 'bg-emerald-500', 'iconText' => 'text-white'],
            ['title' => 'Vehículos parqueados', 'value' => $parkingStats['occupied'], 'note' => 'Actualizado ahora', 'iconBg' => 'bg-violet-500', 'iconText' => 'text-white'],
            ['title' => 'Cupos libres', 'value' => $parkingStats['available'], 'note' => 'Actualizado ahora', 'iconBg' => 'bg-orange-500', 'iconText' => 'text-white'],
            ['title' => 'Ingresos del día', 'value' => '$' . number_format($parkingStats['todayIncome'], 0, ',', '.'), 'note' => 'Actualizado ahora', 'iconBg' => 'bg-teal-500', 'iconText' => 'text-white'],
        ];

        $dashboardMovements = $parkingMovements->map(function (MovimientoParqueadero $movement) {
            return [
                'plate' => $movement->vehiculo?->placa ?? 'SIN-PLACA',
                'type' => $movement->estado === 'abierto' ? 'Entrada al parqueadero' : 'Movimiento de parqueadero',
                'time' => optional($movement->entrada_at)->format('h:i A') ?? '--:--',
                'tone' => $movement->estado === 'abierto' ? 'green' : 'blue',
                'icon' => $movement->estado === 'abierto' ? 'arrow-in' : 'car',
            ];
        })->all();

        $dashboardSales = Venta::query()
            ->with(['cliente', 'vehiculo'])
            ->latest('fecha_venta')
            ->limit(5)
            ->get()
            ->map(function (Venta $venta) {
                return [
                    'date' => optional($venta->fecha_venta)->format('d/m/Y'),
                    'plate' => $venta->vehiculo?->placa ?? 'SIN-PLACA',
                    'vehicle' => trim(($venta->vehiculo?->marca ?? '') . ' ' . ($venta->vehiculo?->modelo ?? '')) ?: 'Vehículo',
                    'client' => trim(($venta->cliente?->nombres ?? '') . ' ' . ($venta->cliente?->apellidos ?? '')) ?: 'Cliente',
                    'value' => '$' . number_format((float) $venta->total, 0, ',', '.'),
                ];
            })->all();

        $dashboardAlerts = [
            ['title' => $parkingStats['reserved'] . ' espacios reservados', 'desc' => 'Actualizar reservas del parqueadero.', 'tone' => 'amber', 'icon' => 'warning'],
            ['title' => $parkingStats['occupied'] . ' vehículos parqueados', 'desc' => 'Ocupación actual del parqueadero.', 'tone' => 'blue', 'icon' => 'calendar'],
            ['title' => 'Ingresos hoy: $' . number_format($parkingStats['todayIncome'], 0, ',', '.'), 'desc' => 'Cierre operativo actualizado.', 'tone' => 'red', 'icon' => 'alert'],
        ];
        $kpis = $dashboardKpis;
        $movimientos = $dashboardMovements;
        $ventas = $dashboardSales;
        $alerts = $dashboardAlerts;
        $summary = [
            ['label' => 'Publicaciones activas', 'value' => $vehicleStats['total'], 'icon' => 'car'],
            ['label' => 'Disponibles', 'value' => $vehicleStats['available'], 'icon' => 'users'],
            ['label' => 'Vehículos parqueados', 'value' => $parkingStats['occupied'], 'icon' => 'clock'],
            ['label' => 'Valor inventario', 'value' => '$' . number_format((float) $vehicleStats['inventoryValue'], 0, ',', '.'), 'icon' => 'dollar'],
        ];
        $catalogMood = $this->catalogMood($selectedFamily, $selectedSubcategory, $publications);

        return compact(
            'summary',
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
            'vehicleStats',
            'parkingStats',
            'dashboardKpis',
            'dashboardMovements',
            'dashboardSales',
            'dashboardAlerts',
            'kpis',
            'movimientos',
            'ventas',
            'alerts',
            'salesChart',
            'salesMonthTotal',
            'salesAverageDaily',
            'bestDay'
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
