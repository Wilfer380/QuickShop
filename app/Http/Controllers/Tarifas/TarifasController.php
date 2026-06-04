<?php

namespace App\Http\Controllers\Tarifas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tarifas\StoreTarifaRequest;
use App\Http\Requests\Tarifas\UpdateTarifaRequest;
use App\Models\Tarifa;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TarifasController extends Controller
{
    private const TIPOS_VEHICULO = ['automovil', 'camioneta', 'motocicleta', 'camion'];
    private const TIPOS_COBRO = ['hora', 'dia', 'mes'];

    public function index(): View
    {
        $tarifas = Tarifa::query()
            ->latest()
            ->paginate(10);

        return view('tarifas.index', compact('tarifas'));
    }

    public function create(): View
    {
        return view('tarifas.create', [
            'tarifa' => new Tarifa(['activa' => true]),
            'tiposVehiculo' => self::TIPOS_VEHICULO,
            'tiposCobro' => self::TIPOS_COBRO,
        ]);
    }

    public function store(StoreTarifaRequest $request): RedirectResponse
    {
        $tarifa = Tarifa::create($request->validated());

        return redirect()
            ->route('tarifas.show', $tarifa)
            ->with('status', 'Tarifa creada correctamente.');
    }

    public function show(Tarifa $tarifa): View
    {
        return view('tarifas.show', compact('tarifa'));
    }

    public function edit(Tarifa $tarifa): View
    {
        return view('tarifas.edit', [
            'tarifa' => $tarifa,
            'tiposVehiculo' => self::TIPOS_VEHICULO,
            'tiposCobro' => self::TIPOS_COBRO,
        ]);
    }

    public function update(UpdateTarifaRequest $request, Tarifa $tarifa): RedirectResponse
    {
        $tarifa->update($request->validated());

        return redirect()
            ->route('tarifas.show', $tarifa)
            ->with('status', 'Tarifa actualizada correctamente.');
    }

    public function destroy(Tarifa $tarifa): RedirectResponse
    {
        $tarifa->delete();

        return redirect()
            ->route('tarifas.index')
            ->with('status', 'Tarifa eliminada correctamente.');
    }
}
