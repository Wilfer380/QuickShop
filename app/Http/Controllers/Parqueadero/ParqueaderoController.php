<?php

namespace App\Http\Controllers\Parqueadero;

use App\Http\Controllers\Controller;
use App\Http\Requests\Parqueadero\ParqueaderoRequest;
use App\Models\CupoParqueadero;
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
        $movimientos = MovimientoParqueadero::query()
            ->with(['vehiculo', 'cliente', 'cupo', 'tarifa'])
            ->latest()
            ->paginate(10);

        $activos = MovimientoParqueadero::query()->where('estado', 'abierto')->count();

        return view('parqueadero.index', compact('movimientos', 'activos'));
    }

    public function create(): View
    {
        return view('parqueadero.create', [
            'vehiculos' => Vehiculo::query()->whereIn('estado', ['disponible', 'reservado'])->orderBy('placa')->get(),
            'cupos' => CupoParqueadero::query()->where('estado', 'disponible')->orderBy('codigo')->get(),
            'tarifas' => Tarifa::query()->where('activa', true)->orderBy('nombre')->get(),
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
            ->route('parqueadero.show', $movimiento)
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
            ->route('parqueadero.show', $movimiento)
            ->with('status', 'Salida registrada correctamente.');
    }
}
