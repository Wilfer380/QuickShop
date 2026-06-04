<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\VentaRequest;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\Venta;
use App\Services\Ventas\VentaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VentasController extends Controller
{
    public function __construct(private VentaService $ventas)
    {
    }

    public function index(): View
    {
        $ventas = Venta::query()
            ->with(['cliente', 'vehiculo', 'vendedor'])
            ->latest()
            ->paginate(10);

        return view('ventas.index', compact('ventas'));
    }

    public function create(): View
    {
        return view('ventas.create', [
            'clientes' => Cliente::query()->orderBy('nombres')->get(),
            'vehiculos' => Vehiculo::query()->where('estado', 'disponible')->orderBy('marca')->get(),
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
}
