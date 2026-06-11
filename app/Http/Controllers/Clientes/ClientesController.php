<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clientes\StoreClienteRequest;
use App\Http\Requests\Clientes\UpdateClienteRequest;
use App\Models\Cliente;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ClientesController extends Controller
{
    public function index(Request $request): View
    {
        [$clientesQuery, $search, $estado, $ciudad, $segmento] = $this->clientesQuery($request);

        $clientes = $clientesQuery->paginate(7)->withQueryString();

        $stats = [
            ['label' => 'Clientes totales', 'value' => Cliente::count(), 'trend' => '18% vs. mes anterior', 'tone' => 'blue', 'icon' => 'users'],
            ['label' => 'Clientes activos', 'value' => Cliente::where('estado', 'activo')->count(), 'trend' => '12% vs. mes anterior', 'tone' => 'green', 'icon' => 'user-check'],
            ['label' => 'Clientes inactivos', 'value' => Cliente::where('estado', 'inactivo')->count(), 'trend' => '6% vs. mes anterior', 'tone' => 'purple', 'icon' => 'user-x'],
            ['label' => 'Clientes frecuentes', 'value' => Cliente::where('segmento', 'frecuente')->count(), 'trend' => '9% vs. mes anterior', 'tone' => 'orange', 'icon' => 'star'],
            ['label' => 'Total compras', 'value' => '$' . number_format((float) Venta::sum('total'), 0, ',', '.'), 'trend' => '22% vs. mes anterior', 'tone' => 'teal', 'icon' => 'money'],
        ];

        $ciudades = Cliente::query()
            ->whereNotNull('ciudad')
            ->where('ciudad', '!=', '')
            ->distinct()
            ->orderBy('ciudad')
            ->pluck('ciudad')
            ->values();

        $segmentos = collect(['todos', 'frecuente', 'activo', 'nuevo', 'inactivo']);

        return view('clientes.index', compact('clientes', 'stats', 'search', 'estado', 'ciudad', 'segmento', 'ciudades', 'segmentos'));
    }

    public function show(Cliente $cliente): View
    {
        $cliente->loadCount('vehiculos')
            ->loadSum('ventas as compras_total', 'total')
            ->loadMax('ventas as ultima_compra', 'fecha_venta')
            ->load(['vehiculos', 'ventas' => fn ($query) => $query->latest('fecha_venta')->limit(5), 'pagos' => fn ($query) => $query->latest()->limit(5), 'movimientosParqueadero' => fn ($query) => $query->latest()->limit(5)]);

        return view('clientes.show', compact('cliente'));
    }

    public function exportarExcel(Request $request)
    {
        [$clientesQuery] = $this->clientesQuery($request);

        $selectedIds = collect(explode(',', (string) $request->query('ids', '')))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values()
            ->all();

        if ($selectedIds !== []) {
            $clientesQuery->whereIn('id', $selectedIds);
        }

        $clientes = $clientesQuery
            ->withCount(['vehiculos', 'ventas', 'pagos', 'movimientosParqueadero'])
            ->withSum('ventas as compras_total', 'total')
            ->withSum('pagos as pagos_total', 'valor')
            ->withMax('ventas as ultima_compra', 'fecha_venta')
            ->withMax('pagos as ultimo_pago', 'pagado_at')
            ->withMax('movimientosParqueadero as ultimo_movimiento', 'entrada_at')
            ->get();
        $fileName = 'clientes-vehipark.csv';

        return response()->streamDownload(function () use ($clientes) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fwrite($handle, "sep=;\n");

            fputcsv($handle, [
                'Nombre completo', 'Tipo de documento', 'Documento', 'Teléfono', 'Correo electrónico', 'Ciudad', 'Dirección', 'Segmento', 'Estado', 'Vehículos registrados', 'Ventas registradas', 'Compras totales', 'Última compra', 'Pagos registrados', 'Total pagado', 'Último pago', 'Movimientos parqueadero', 'Último movimiento', 'Fecha de registro',
            ], ';');

            foreach ($clientes as $cliente) {
                fputcsv($handle, [
                    trim($cliente->nombres . ' ' . ($cliente->apellidos ?? '')),
                    $cliente->tipo_documento,
                    $cliente->documento,
                    $cliente->telefono ?? '',
                    $cliente->email ?? '',
                    $cliente->ciudad ?? '',
                    $cliente->direccion ?? '',
                    ucfirst((string) ($cliente->segmento ?? '')),
                    ucfirst((string) ($cliente->estado ?? '')),
                    (int) ($cliente->vehiculos_count ?? 0),
                    (int) ($cliente->ventas_count ?? 0),
                    number_format((float) ($cliente->compras_total ?? 0), 0, ',', '.'),
                    $cliente->ultima_compra ? \Illuminate\Support\Carbon::parse($cliente->ultima_compra)->format('d/m/Y') : '',
                    (int) ($cliente->pagos_count ?? 0),
                    number_format((float) ($cliente->pagos_total ?? 0), 0, ',', '.'),
                    $cliente->ultimo_pago ? \Illuminate\Support\Carbon::parse($cliente->ultimo_pago)->format('d/m/Y h:i A') : '',
                    (int) ($cliente->movimientos_parqueadero_count ?? 0),
                    $cliente->ultimo_movimiento ? \Illuminate\Support\Carbon::parse($cliente->ultimo_movimiento)->format('d/m/Y h:i A') : '',
                    optional($cliente->created_at)->format('d/m/Y'),
                ], ';');
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function create(): View
    {
        return view('clientes.create', [
            'cliente' => new Cliente(),
        ]);
    }

    public function store(StoreClienteRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('clientes', 'public');
        }

        $cliente = Cliente::create($data);

        return redirect()
            ->route('clientes.index')
            ->with('status', 'Cliente creado correctamente.');
    }

    public function edit(Cliente $cliente): View
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($cliente->foto) {
                Storage::disk('public')->delete($cliente->foto);
            }

            $data['foto'] = $request->file('foto')->store('clientes', 'public');
        }

        $cliente->update($data);

        return redirect()
            ->route('clientes.index')
            ->with('status', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        if ($cliente->foto) {
            Storage::disk('public')->delete($cliente->foto);
        }

        $cliente->delete();

        return redirect()
            ->route('clientes.index')
            ->with('status', 'Cliente eliminado correctamente.');
    }

    /**
     * @return array{0:\Illuminate\Database\Eloquent\Builder,1:string,2:string,3:string,4:string}
     */
    private function clientesQuery(Request $request): array
    {
        $search = trim((string) $request->query('q', ''));
        $estado = $request->query('estado', 'todos');
        $ciudad = $request->query('ciudad', 'todas');
        $segmento = $request->query('segmento', 'todos');

        $clientesQuery = Cliente::query()
            ->withCount('vehiculos')
            ->withSum('ventas as compras_total', 'total')
            ->withMax('ventas as ultima_compra', 'fecha_venta')
            ->latest('id');

        if ($search !== '') {
            $clientesQuery->where(function ($query) use ($search) {
                $query->where('nombres', 'like', "%{$search}%")
                    ->orWhere('apellidos', 'like', "%{$search}%")
                    ->orWhere('documento', 'like', "%{$search}%")
                    ->orWhere('telefono', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($estado !== 'todos') {
            $clientesQuery->where('estado', $estado);
        }

        if ($ciudad !== 'todas') {
            $clientesQuery->where('ciudad', $ciudad);
        }

        if ($segmento !== 'todos') {
            $clientesQuery->where('segmento', $segmento);
        }

        return [$clientesQuery, $search, $estado, $ciudad, $segmento];
    }

}
