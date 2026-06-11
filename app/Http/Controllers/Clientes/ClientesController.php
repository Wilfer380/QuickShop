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
            ->withSum('movimientosParqueadero as parqueadero_total', 'total')
            ->withMax('ventas as ultima_compra', 'fecha_venta')
            ->withMax('pagos as ultimo_pago', 'pagado_at')
            ->withMax('movimientosParqueadero as ultimo_movimiento', 'entrada_at')
            ->get();
        $fileName = 'clientes-vehipark.xls';

        return response()->streamDownload(function () use ($clientes) {
            echo '<html><head><meta charset="UTF-8"></head><body>';
            echo '<table border="1" cellspacing="0" cellpadding="6" style="border-collapse:collapse;font-family:Arial,sans-serif;font-size:12px;">';
            echo '<tr><th colspan="14" style="background:#1f2937;color:#fff;font-size:14px;text-align:left;">Reporte de clientes - VehiPark</th></tr>';
            echo '<tr style="background:#dbeafe;font-weight:bold;">';
            foreach (['Nombre completo', 'Tipo de documento', 'Documento', 'Teléfono', 'Correo electrónico', 'Ciudad', 'Dirección', 'Segmento', 'Estado', 'Vehículos', 'Ventas', 'Pagos', 'Parqueadero', 'Fecha de registro'] as $header) {
                echo '<th>' . e($header) . '</th>';
            }
            echo '</tr>';

            foreach ($clientes as $cliente) {
                echo '<tr>';
                echo '<td>' . e(trim($cliente->nombres . ' ' . ($cliente->apellidos ?? ''))) . '</td>';
                echo '<td>' . e((string) $cliente->tipo_documento) . '</td>';
                echo '<td>' . e((string) $cliente->documento) . '</td>';
                echo '<td>' . e((string) ($cliente->telefono ?? '')) . '</td>';
                echo '<td>' . e((string) ($cliente->email ?? '')) . '</td>';
                echo '<td>' . e((string) ($cliente->ciudad ?? '')) . '</td>';
                echo '<td>' . e((string) ($cliente->direccion ?? '')) . '</td>';
                echo '<td>' . e(ucfirst((string) ($cliente->segmento ?? ''))) . '</td>';
                echo '<td>' . e(ucfirst((string) ($cliente->estado ?? ''))) . '</td>';
                echo '<td>' . e((string) ($cliente->vehiculos_count ?? 0)) . '</td>';
                echo '<td>' . e((string) ($cliente->ventas_count ?? 0)) . ' / $' . e(number_format((float) ($cliente->compras_total ?? 0), 0, ',', '.')) . '</td>';
                echo '<td>' . e((string) ($cliente->pagos_count ?? 0)) . ' / $' . e(number_format((float) ($cliente->pagos_total ?? 0), 0, ',', '.')) . '</td>';
                echo '<td>' . e((string) ($cliente->movimientos_parqueadero_count ?? 0)) . ' / $' . e(number_format((float) ($cliente->parqueadero_total ?? 0), 0, ',', '.')) . '</td>';
                echo '<td>' . e(optional($cliente->created_at)->format('d/m/Y')) . '</td>';
                echo '</tr>';

                echo '<tr>';
                echo '<td colspan="14" style="background:#f8fafc;color:#334155;">';
                echo '<strong>Últimos movimientos:</strong> ';
                echo 'Venta: ' . e($cliente->ultima_compra ? \Illuminate\Support\Carbon::parse($cliente->ultima_compra)->format('d/m/Y') : 'Sin ventas') . ' · ';
                echo 'Pago: ' . e($cliente->ultimo_pago ? \Illuminate\Support\Carbon::parse($cliente->ultimo_pago)->format('d/m/Y h:i A') : 'Sin pagos') . ' · ';
                echo 'Parqueadero: ' . e($cliente->ultimo_movimiento ? \Illuminate\Support\Carbon::parse($cliente->ultimo_movimiento)->format('d/m/Y h:i A') : 'Sin movimientos');
                echo '</td>';
                echo '</tr>';
            }

            echo '</table>';
            echo '</body></html>';
        }, $fileName, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
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
