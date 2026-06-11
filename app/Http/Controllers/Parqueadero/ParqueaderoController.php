<?php

namespace App\Http\Controllers\Parqueadero;

use App\Http\Controllers\Controller;
use App\Http\Requests\Parqueadero\ParqueaderoRequest;
use App\Models\Cliente;
use App\Models\CupoParqueadero;
use App\Models\Pago;
use App\Models\MovimientoParqueadero;
use App\Models\Tarifa;
use App\Models\Vehiculo;
use App\Services\Parqueadero\ParqueaderoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ParqueaderoController extends Controller
{
    public function __construct(private ParqueaderoService $parqueadero)
    {
    }

    public function index(): View
    {
        $cupos = CupoParqueadero::query()->orderBy('zona')->orderBy('codigo')->get();
        $activeMovements = MovimientoParqueadero::query()
            ->with(['vehiculo', 'cliente', 'cupo'])
            ->where('estado', 'abierto')
            ->get()
            ->keyBy(fn (MovimientoParqueadero $movimiento) => strtoupper((string) $movimiento->cupo?->codigo));

        $movimientos = MovimientoParqueadero::query()
            ->with(['vehiculo', 'cliente', 'cupo', 'tarifa'])
            ->where('estado', 'abierto')
            ->latest('entrada_at')
            ->paginate(5);

        $occupied = $activeMovements->count();

        $reserved = $cupos->filter(fn (CupoParqueadero $cupo) => in_array(strtolower((string) $cupo->estado), ['reserved', 'reservado'], true))->count();
        $maintenance = $cupos->filter(fn (CupoParqueadero $cupo) => in_array(strtolower((string) $cupo->estado), ['maintenance', 'mantenimiento'], true))->count();
        $available = max(0, $cupos->count() - $occupied - $reserved - $maintenance);

        $incomeToday = (float) Pago::query()
            ->where('concepto', 'parqueadero')
            ->whereDate('pagado_at', today())
            ->sum('valor');

        $incomeMonth = (float) Pago::query()
            ->where('concepto', 'parqueadero')
            ->whereMonth('pagado_at', now()->month)
            ->whereYear('pagado_at', now()->year)
            ->sum('valor');

        $recentEntries = MovimientoParqueadero::query()
            ->with(['vehiculo', 'cliente', 'cupo'])
            ->latest('entrada_at')
            ->limit(5)
            ->get()
            ->map(function (MovimientoParqueadero $movimiento) {
                $cliente = trim(($movimiento->cliente?->nombres ?? '') . ' ' . ($movimiento->cliente?->apellidos ?? '')) ?: 'Cliente sin nombre';

                return [
                    'plate' => $movimiento->vehiculo?->placa ?? 'SIN-PLACA',
                    'client' => $cliente,
                    'time' => optional($movimiento->entrada_at)->format('h:i A') ?? '--:--',
                    'slot' => trim('Zona ' . ($movimiento->cupo?->zona ?? '—') . ' - ' . ($movimiento->cupo?->codigo ?? '—')),
                ];
            });

        $upcomingExits = MovimientoParqueadero::query()
            ->with(['vehiculo', 'cliente', 'cupo', 'tarifa'])
            ->where('estado', 'abierto')
            ->oldest('entrada_at')
            ->limit(3)
            ->get()
            ->map(function (MovimientoParqueadero $movimiento) {
                $cliente = trim(($movimiento->cliente?->nombres ?? '') . ' ' . ($movimiento->cliente?->apellidos ?? '')) ?: 'Cliente sin nombre';
                $estimatedExit = optional($movimiento->entrada_at?->copy()?->addHours(2))->format('h:i A') ?? '--:--';

                return [
                    'plate' => $movimiento->vehiculo?->placa ?? 'SIN-PLACA',
                    'client' => $cliente,
                    'time' => $estimatedExit,
                    'slot' => trim('Zona ' . ($movimiento->cupo?->zona ?? '—') . ' - ' . ($movimiento->cupo?->codigo ?? '—')),
                ];
            });

        $kpis = [
            ['label' => 'Espacios totales', 'value' => $cupos->count() ?: 0, 'subtitle' => 'Distribuidos en 3 zonas', 'tone' => 'blue', 'icon' => 'car'],
            ['label' => 'Ocupados', 'value' => $occupied, 'subtitle' => round(($cupos->count() > 0 ? $occupied / $cupos->count() : 0) * 100) . '% del total', 'tone' => 'green', 'icon' => 'trend'],
            ['label' => 'Disponibles', 'value' => $available, 'subtitle' => round(($cupos->count() > 0 ? $available / $cupos->count() : 0) * 100) . '% del total', 'tone' => 'orange', 'icon' => 'clock'],
            ['label' => 'Ingresos hoy', 'value' => '$' . number_format($incomeToday, 0, ',', '.'), 'subtitle' => $movimientos->total() . ' vehículos', 'tone' => 'violet', 'icon' => 'calendar'],
            ['label' => 'Ingresos del mes', 'value' => '$' . number_format($incomeMonth, 0, ',', '.'), 'subtitle' => 'Ingresos reales del parqueadero', 'tone' => 'teal', 'icon' => 'money'],
        ];

        return view('parqueadero.index', [
            'movimientos' => $movimientos,
            'activeMovements' => $activeMovements,
            'activos' => $activeMovements->count(),
            'cupos' => $cupos,
            'tarifas' => Tarifa::query()->where('activa', true)->orderBy('tipo_vehiculo')->orderBy('nombre')->get(),
            'vehiculos' => Vehiculo::query()->with('cliente')->orderBy('placa')->get(),
            'clientes' => Cliente::query()->orderBy('nombres')->orderBy('apellidos')->get(),
            'kpis' => $kpis,
            'recentEntries' => $recentEntries,
            'upcomingExits' => $upcomingExits,
            'parkingStats' => [
                'occupied' => $occupied,
                'available' => $available,
                'reserved' => $reserved,
                'maintenance' => $maintenance,
            ],
        ]);
    }

    public function create(): View
    {
        return view('parqueadero.create', [
            'vehiculos' => Vehiculo::query()->whereIn('estado', ['disponible', 'reservado'])->orderBy('placa')->get(),
            'cupos' => CupoParqueadero::query()->where('estado', 'disponible')->orderBy('codigo')->get(),
            'tarifas' => Tarifa::query()->where('activa', true)->orderBy('nombre')->get(),
            'clientes' => Cliente::query()->orderBy('nombres')->orderBy('apellidos')->get(),
        ]);
    }

    public function store(ParqueaderoRequest $request): RedirectResponse
    {
        try {
            $movimiento = $this->parqueadero->registrarEntrada($request->validated(), $request->user()->id);
        } catch (\RuntimeException $exception) {
            return back()->withInput()->withErrors(['vehiculo_id' => $exception->getMessage()]);
        }

        return redirect()
            ->route('parqueadero.index')
            ->with('status', 'Entrada registrada correctamente.');
    }

    public function show(MovimientoParqueadero $movimiento): View
    {
        $movimiento->load(['vehiculo', 'cliente', 'cupo', 'tarifa', 'pagos.recibidoPor']);

        return view('parqueadero.show', compact('movimiento'));
    }

    public function salida(ParqueaderoRequest $request, MovimientoParqueadero $movimiento): RedirectResponse
    {
        try {
            $movimiento = $this->parqueadero->registrarSalida($movimiento, $request->validated(), $request->user()->id);
        } catch (\RuntimeException $exception) {
            return back()->withErrors(['salida_at' => $exception->getMessage()]);
        }

        return redirect()
            ->route('parqueadero.index')
            ->with('status', 'Salida registrada correctamente.');
    }
}
