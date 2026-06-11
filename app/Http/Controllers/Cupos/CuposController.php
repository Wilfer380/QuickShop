<?php

namespace App\Http\Controllers\Cupos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cupos\StoreCupoRequest;
use App\Http\Requests\Cupos\UpdateCupoRequest;
use App\Models\CupoParqueadero;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CuposController extends Controller
{
    private const TIPOS_VEHICULO = ['automovil', 'camioneta', 'motocicleta', 'camion'];
    private const ESTADOS = ['disponible', 'ocupado', 'mantenimiento', 'inactivo'];

    public function index(): View
    {
        $cupos = CupoParqueadero::query()
            ->latest()
            ->paginate(10);

        return view('cupos.index', compact('cupos'));
    }

    public function create(): View
    {
        return view('cupos.create', [
            'cupo' => new CupoParqueadero(),
            'tiposVehiculo' => self::TIPOS_VEHICULO,
            'estados' => self::ESTADOS,
        ]);
    }

    public function store(StoreCupoRequest $request): RedirectResponse
    {
        $cupo = CupoParqueadero::create($request->validated());

        return redirect()
            ->route('cupos.show', $cupo)
            ->with('status', 'Cupo creado correctamente.');
    }

    public function show(CupoParqueadero $cupo): View
    {
        return view('cupos.show', compact('cupo'));
    }

    public function edit(CupoParqueadero $cupo): View
    {
        return view('cupos.edit', [
            'cupo' => $cupo,
            'tiposVehiculo' => self::TIPOS_VEHICULO,
            'estados' => self::ESTADOS,
        ]);
    }

    public function update(UpdateCupoRequest $request, CupoParqueadero $cupo): RedirectResponse
    {
        $cupo->update($request->validated());

        return redirect()
            ->route('cupos.show', $cupo)
            ->with('status', 'Cupo actualizado correctamente.');
    }

    public function destroy(CupoParqueadero $cupo): RedirectResponse
    {
        $cupo->delete();

        return redirect()
            ->route('cupos.index')
            ->with('status', 'Cupo eliminado correctamente.');
    }
}
