<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\VentaRequest;
use App\Models\Cliente;
use App\Models\Pago;
use App\Models\Vehiculo;
use App\Models\Venta;
use App\Services\Ventas\VentaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class VentasController extends Controller
{
    public function __construct(private VentaService $ventas)
    {
    }

    public function index(Request $request): View
    {
        [$ventasQuery, $search, $estado, $desde, $hasta] = $this->ventasQuery($request);

        $ventas = $ventasQuery->paginate(8)->withQueryString();
        $stats = $this->stats();
        $dashboard = $this->dashboardData();
        $clientes = Cliente::query()->orderBy('nombres')->get();
        $vehiculos = $this->vehiculosParaVentaQuery()->get();

        return view('ventas.index', compact('ventas', 'stats', 'dashboard', 'search', 'estado', 'desde', 'hasta', 'clientes', 'vehiculos'));
    }

    public function create(): View
    {
        return view('ventas.create', [
            'venta' => new Venta(['fecha_venta' => now()->toDateString(), 'descuento' => 0, 'impuestos' => 0]),
            'clientes' => Cliente::query()->orderBy('nombres')->get(),
            'vehiculos' => $this->vehiculosParaVentaQuery()->get(),
            'action' => route('ventas.store'),
            'method' => 'POST',
        ]);
    }

    public function store(VentaRequest $request): RedirectResponse
    {
        try {
            $venta = $this->ventas->crear($request->validated(), $request->user()->id);
        } catch (\RuntimeException $exception) {
            return back()->withInput()->withErrors(['vehiculo_id' => $exception->getMessage()]);
        }

        return redirect()
            ->route('ventas.show', $venta)
            ->with('status', 'Venta registrada correctamente.');
    }

    public function show(Venta $venta): View
    {
        $venta->load(['cliente', 'vehiculo', 'vendedor', 'pagos.recibidoPor']);

        return view('ventas.show', compact('venta'));
    }

    public function edit(Venta $venta): View
    {
        $venta->load(['cliente', 'vehiculo', 'vendedor', 'pagos']);

        return view('ventas.edit', [
            'venta' => $venta,
            'clientes' => Cliente::query()->orderBy('nombres')->get(),
            'vehiculos' => $this->vehiculosParaVentaQuery()->orWhereKey($venta->vehiculo_id)->get(),
            'action' => route('ventas.update', $venta),
            'method' => 'PUT',
        ]);
    }

    public function update(VentaRequest $request, Venta $venta): RedirectResponse
    {
        try {
            $venta = $this->ventas->actualizar($venta, $request->validated());
        } catch (\RuntimeException $exception) {
            return back()->withInput()->withErrors(['vehiculo_id' => $exception->getMessage()]);
        }

        return redirect()
            ->route('ventas.show', $venta)
            ->with('status', 'Venta actualizada correctamente.');
    }

    public function exportar(Request $request)
    {
        [$ventasQuery] = $this->ventasQuery($request);
        $ventas = $ventasQuery->get();

        return response()->streamDownload(function () use ($ventas) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fwrite($handle, "sep=;\n");
            fputcsv($handle, ['Venta', 'Cliente', 'Vehiculo', 'Fecha', 'Precio base', 'Descuento', 'Impuestos', 'Total', 'Pagado', 'Saldo', 'Estado', 'Vendedor'], ';');

            foreach ($ventas as $venta) {
                $pagado = (float) $venta->pagos_sum_valor;
                fputcsv($handle, [
                    '#' . $venta->id,
                    trim($venta->cliente->nombres . ' ' . ($venta->cliente->apellidos ?? '')),
                    trim($venta->vehiculo->marca . ' ' . $venta->vehiculo->modelo . ' ' . $venta->vehiculo->placa),
                    optional($venta->fecha_venta)->format('d/m/Y'),
                    number_format((float) $venta->precio_base, 0, ',', '.'),
                    number_format((float) $venta->descuento, 0, ',', '.'),
                    number_format((float) $venta->impuestos, 0, ',', '.'),
                    number_format((float) $venta->total, 0, ',', '.'),
                    number_format($pagado, 0, ',', '.'),
                    number_format(max(0, (float) $venta->total - $pagado), 0, ',', '.'),
                    ucfirst($venta->estado),
                    $venta->vendedor?->name ?? '',
                ], ';');
            }

            fclose($handle);
        }, 'ventas-vehipark.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * @return array{0:\Illuminate\Database\Eloquent\Builder,1:string,2:string,3:string,4:string}
     */
    private function ventasQuery(Request $request): array
    {
        $search = trim((string) $request->query('q', ''));
        $estado = (string) $request->query('estado', 'todos');
        $desde = (string) $request->query('desde', '');
        $hasta = (string) $request->query('hasta', '');

        $query = Venta::query()
            ->with(['cliente', 'vehiculo', 'vendedor'])
            ->withSum('pagos', 'valor')
            ->latest('fecha_venta')
            ->latest('id');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('cliente', function ($cliente) use ($search) {
                    $cliente->where('nombres', 'like', "%{$search}%")
                        ->orWhere('apellidos', 'like', "%{$search}%")
                        ->orWhere('documento', 'like', "%{$search}%");
                })->orWhereHas('vehiculo', function ($vehiculo) use ($search) {
                    $vehiculo->where('marca', 'like', "%{$search}%")
                        ->orWhere('modelo', 'like', "%{$search}%")
                        ->orWhere('placa', 'like', "%{$search}%");
                });
            });
        }

        if ($estado !== 'todos') {
            $query->where('estado', $estado);
        }

        if ($desde !== '') {
            $query->whereDate('fecha_venta', '>=', $desde);
        }

        if ($hasta !== '') {
            $query->whereDate('fecha_venta', '<=', $hasta);
        }

        return [$query, $search, $estado, $desde, $hasta];
    }

    private function stats(): array
    {
        $total = (float) Venta::sum('total');
        $pagado = (float) Pago::query()->whereNotNull('venta_id')->sum('valor');
        $monthTotal = (float) Venta::query()
            ->whereBetween('fecha_venta', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('total');

        return [
            ['label' => 'Ventas del mes', 'value' => $this->money($monthTotal), 'trend' => Venta::whereBetween('fecha_venta', [now()->startOfMonth(), now()->endOfMonth()])->count() . ' cierres activos', 'tone' => 'blue', 'icon' => 'chart'],
            ['label' => 'Pagado', 'value' => $this->money($pagado), 'trend' => 'Recaudo registrado', 'tone' => 'green', 'icon' => 'money'],
            ['label' => 'Pendiente', 'value' => $this->money(max(0, $total - $pagado)), 'trend' => Venta::whereIn('estado', ['pendiente', 'abono'])->count() . ' ventas por cobrar', 'tone' => 'orange', 'icon' => 'wallet'],
            ['label' => 'Total facturado', 'value' => $this->money($total), 'trend' => Venta::count() . ' operaciones históricas', 'tone' => 'purple', 'icon' => 'tag'],
        ];
    }

    private function vehiculosParaVentaQuery()
    {
        return Vehiculo::query()
            ->orderByRaw("CASE WHEN estado IN ('vendido', 'inactivo') THEN 1 ELSE 0 END")
            ->orderBy('marca')
            ->orderBy('modelo')
            ->orderBy('placa');
    }

    private function dashboardData(): array
    {
        $today = today();
        $salesToday = (float) Venta::query()->whereDate('fecha_venta', $today)->sum('total');
        $paidToday = (float) Pago::query()->whereNotNull('venta_id')->whereDate('pagado_at', $today)->sum('valor');
        $pendingSales = Venta::query()->whereIn('estado', ['pendiente', 'abono'])->count();

        $upcomingCollections = Venta::query()
            ->with(['cliente', 'vehiculo'])
            ->withSum('pagos', 'valor')
            ->whereIn('estado', ['pendiente', 'abono'])
            ->oldest('fecha_venta')
            ->limit(4)
            ->get()
            ->map(function (Venta $venta) {
                $pagado = (float) ($venta->pagos_sum_valor ?? 0);

                return [
                    'id' => $venta->id,
                    'cliente' => trim($venta->cliente->nombres . ' ' . ($venta->cliente->apellidos ?? '')),
                    'vehiculo' => trim($venta->vehiculo->marca . ' ' . $venta->vehiculo->modelo),
                    'saldo' => $this->money(max(0, (float) $venta->total - $pagado)),
                    'fecha' => optional($venta->fecha_venta)->format('d/m/Y'),
                    'estado' => ucfirst($venta->estado),
                ];
            });

        $recentActivity = collect()
            ->merge(Venta::query()->with(['cliente', 'vehiculo'])->latest('created_at')->limit(4)->get()->map(function (Venta $venta) {
                return [
                    'type' => 'venta',
                    'title' => 'Venta #' . $venta->id . ' registrada',
                    'meta' => trim($venta->cliente->nombres . ' ' . ($venta->cliente->apellidos ?? '')) . ' · ' . trim($venta->vehiculo->marca . ' ' . $venta->vehiculo->modelo),
                    'amount' => $this->money((float) $venta->total),
                    'time' => $venta->created_at?->diffForHumans() ?? 'Sin fecha',
                    'sort_at' => $venta->created_at ?? now()->subYears(10),
                ];
            }))
            ->merge(Pago::query()->with(['cliente', 'venta'])->whereNotNull('venta_id')->latest('pagado_at')->limit(4)->get()->map(function (Pago $pago) {
                return [
                    'type' => 'pago',
                    'title' => 'Abono recibido',
                    'meta' => trim(($pago->cliente->nombres ?? '') . ' ' . ($pago->cliente->apellidos ?? '')) . ' · Venta #' . $pago->venta_id,
                    'amount' => $this->money((float) $pago->valor),
                    'time' => $pago->pagado_at?->diffForHumans() ?? 'Sin fecha',
                    'sort_at' => $pago->pagado_at ?? now()->subYears(10),
                ];
            }))
            ->sortByDesc(fn (array $activity) => Carbon::parse($activity['sort_at']))
            ->take(6)
            ->map(function (array $activity) {
                unset($activity['sort_at']);

                return $activity;
            })
            ->values();

        return [
            'today' => [
                ['label' => 'Vendido hoy', 'value' => $this->money($salesToday), 'hint' => Venta::query()->whereDate('fecha_venta', $today)->count() . ' ventas'],
                ['label' => 'Recaudado hoy', 'value' => $this->money($paidToday), 'hint' => Pago::query()->whereNotNull('venta_id')->whereDate('pagado_at', $today)->count() . ' pagos'],
                ['label' => 'Cartera activa', 'value' => $pendingSales, 'hint' => 'pendientes o con abono'],
            ],
            'upcomingCollections' => $upcomingCollections,
            'recentActivity' => $recentActivity,
        ];
    }

    private function money(float $value): string
    {
        return '$' . number_format($value, 0, ',', '.');
    }
}
