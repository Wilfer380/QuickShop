<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clientes\StoreClienteRequest;
use App\Http\Requests\Clientes\UpdateClienteRequest;
use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientesController extends Controller
{
    public function index(): View
    {
        $clientes = Cliente::query()
            ->withCount('vehiculos')
            ->latest()
            ->paginate(10);

        return view('clientes.index', compact('clientes'));
    }

    public function create(): View
    {
        return view('clientes.create', [
            'cliente' => new Cliente(),
        ]);
    }

    public function store(StoreClienteRequest $request): RedirectResponse
    {
        $cliente = Cliente::create($request->validated());

        return redirect()
            ->route('clientes.show', $cliente)
            ->with('status', 'Cliente creado correctamente.');
    }

    public function show(Cliente $cliente): View
    {
        $cliente->load('vehiculos');

        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente): View
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente): RedirectResponse
    {
        $cliente->update($request->validated());

        return redirect()
            ->route('clientes.show', $cliente)
            ->with('status', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();

        return redirect()
            ->route('clientes.index')
            ->with('status', 'Cliente eliminado correctamente.');
    }
}
