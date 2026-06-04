<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\StoreVehiculoRequest;
use App\Http\Requests\Vehiculos\UpdateVehiculoRequest;
use App\Models\Cliente;
use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VehiculosController extends Controller
{
    private const TIPOS = ['automovil', 'camioneta', 'motocicleta', 'camion'];
    private const ESTADOS = ['disponible', 'vendido', 'reservado', 'mantenimiento'];

    public function index(): View
    {
        $vehiculos = Vehiculo::query()
            ->with('cliente')
            ->latest()
            ->paginate(10);

        return view('vehiculos.index', compact('vehiculos'));
    }

    public function create(): View
    {
        return view('vehiculos.create', [
            'vehiculo' => new Vehiculo(),
            'clientes' => Cliente::query()->orderBy('nombres')->get(),
            'tipos' => self::TIPOS,
            'estados' => self::ESTADOS,
        ]);
    }

    public function store(StoreVehiculoRequest $request): RedirectResponse
    {
        $vehiculo = Vehiculo::create($request->validated());

        return redirect()
            ->route('vehiculos.show', $vehiculo)
            ->with('status', 'Vehiculo creado correctamente.');
    }

    public function show(Vehiculo $vehiculo): View
    {
        $vehiculo->load('cliente');

        return view('vehiculos.show', compact('vehiculo'));
    }

    public function edit(Vehiculo $vehiculo): View
    {
        return view('vehiculos.edit', [
            'vehiculo' => $vehiculo,
            'clientes' => Cliente::query()->orderBy('nombres')->get(),
            'tipos' => self::TIPOS,
            'estados' => self::ESTADOS,
        ]);
    }

    public function update(UpdateVehiculoRequest $request, Vehiculo $vehiculo): RedirectResponse
    {
        $vehiculo->update($request->validated());

        return redirect()
            ->route('vehiculos.show', $vehiculo)
            ->with('status', 'Vehiculo actualizado correctamente.');
    }

    public function destroy(Vehiculo $vehiculo): RedirectResponse
    {
        $vehiculo->delete();

        return redirect()
            ->route('vehiculos.index')
            ->with('status', 'Vehiculo eliminado correctamente.');
    }
}
