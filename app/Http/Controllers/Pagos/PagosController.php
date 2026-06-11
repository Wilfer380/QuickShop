<?php

namespace App\Http\Controllers\Pagos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pagos\PagoRequest;
use App\Models\Pago;
use App\Models\Venta;
use App\Models\MovimientoParqueadero;
use App\Services\Pagos\PagoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PagosController extends Controller
{
    public function __construct(private PagoService $pagos)
    {
    }

    public function index(): View
    {
        $pagos = Pago::query()
            ->with(['cliente', 'venta.vehiculo', 'movimientoParqueadero.vehiculo', 'recibidoPor'])
            ->latest()
            ->paginate(10);

        return view('pagos.index', compact('pagos'));
    }

    public function create(): View
    {
        return view('pagos.create', [
            'ventas' => Venta::query()->with(['cliente', 'vehiculo'])->whereIn('estado', ['pendiente', 'abono'])->latest()->get(),
            'movimientos' => MovimientoParqueadero::query()->with(['cliente', 'vehiculo'])->whereIn('estado', ['cerrado'])->latest()->get(),
        ]);
    }

    public function store(PagoRequest $request): RedirectResponse
    {
        $pago = $this->pagos->registrar($request->validated(), $request->user()->id);

        return redirect()
            ->route('pagos.show', $pago)
            ->with('status', 'Pago registrado correctamente.');
    }

    public function show(Pago $pago): View
    {
        $pago->load(['cliente', 'venta.vehiculo', 'movimientoParqueadero.vehiculo', 'recibidoPor']);

        return view('pagos.show', compact('pago'));
    }
}
