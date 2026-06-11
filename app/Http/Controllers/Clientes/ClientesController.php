<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clientes\StoreClienteRequest;
use App\Http\Requests\Clientes\UpdateClienteRequest;
use App\Models\Cliente;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
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
            echo $this->renderClientesExportWorkbook($clientes);
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

    private function renderClientesExportWorkbook($clientes): string
    {
        $generatedAt = now()->format('d/m/Y H:i');
        $total = $clientes->count();

        $rows = $clientes->map(function (Cliente $cliente): string {
            $fullName = trim($cliente->nombres . ' ' . ($cliente->apellidos ?? ''));
            $lastPurchase = $cliente->ultima_compra ? Carbon::parse($cliente->ultima_compra)->format('d/m/Y') : 'Sin ventas';
            $lastPayment = $cliente->ultimo_pago ? Carbon::parse($cliente->ultimo_pago)->format('d/m/Y h:i A') : 'Sin pagos';
            $lastParking = $cliente->ultimo_movimiento ? Carbon::parse($cliente->ultimo_movimiento)->format('d/m/Y h:i A') : 'Sin movimientos';

            return '<tr>'
                . '<td class="text">' . e($fullName) . '</td>'
                . '<td class="center">' . e((string) $cliente->tipo_documento) . '</td>'
                . '<td class="center">' . e((string) $cliente->documento) . '</td>'
                . '<td class="text">' . e((string) ($cliente->telefono ?? '')) . '</td>'
                . '<td class="text">' . e((string) ($cliente->email ?? '')) . '</td>'
                . '<td class="text">' . e((string) ($cliente->ciudad ?? '')) . '</td>'
                . '<td class="text">' . e((string) ($cliente->direccion ?? '')) . '</td>'
                . '<td class="center">' . e(ucfirst((string) ($cliente->segmento ?? ''))) . '</td>'
                . '<td class="center">' . e(ucfirst((string) ($cliente->estado ?? ''))) . '</td>'
                . '<td class="center">' . e((string) (int) ($cliente->vehiculos_count ?? 0)) . '</td>'
                . '<td class="center">' . e((int) ($cliente->ventas_count ?? 0) . ' / $' . number_format((float) ($cliente->compras_total ?? 0), 0, ',', '.')) . '</td>'
                . '<td class="center">' . e((int) ($cliente->pagos_count ?? 0) . ' / $' . number_format((float) ($cliente->pagos_total ?? 0), 0, ',', '.')) . '</td>'
                . '<td class="center">' . e((int) ($cliente->movimientos_parqueadero_count ?? 0) . ' / $' . number_format((float) ($cliente->parqueadero_total ?? 0), 0, ',', '.')) . '</td>'
                . '<td class="center">' . e(optional($cliente->created_at)->format('d/m/Y')) . '</td>'
                . '<td class="text">' . e('Venta: ' . $lastPurchase . ' | Pago: ' . $lastPayment . ' | Parqueadero: ' . $lastParking) . '</td>'
                . '</tr>';
        })->implode('');

        return <<<HTML
<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Clientes</x:Name>
                    <x:WorksheetOptions>
                        <x:DisplayGridlines/>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 20px;
            color: #0f172a;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }
        .title {
            background: #0f766e;
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            text-align: left;
            padding: 14px 16px;
        }
        .subtitle {
            background: #ecfeff;
            color: #155e75;
            font-size: 11px;
            padding: 10px 16px;
            border-bottom: 1px solid #bae6fd;
        }
        thead th {
            background: #1e293b;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 10px 8px;
            border: 1px solid #334155;
            text-align: center;
        }
        tbody td {
            border: 1px solid #cbd5e1;
            font-size: 10.5px;
            padding: 8px;
            vertical-align: top;
            word-wrap: break-word;
            white-space: normal;
        }
        tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        .text { text-align: left; }
        .center { text-align: center; }
        col.name { width: 200px; }
        col.doc-type { width: 78px; }
        col.document { width: 110px; }
        col.phone { width: 120px; }
        col.email { width: 190px; }
        col.city { width: 110px; }
        col.address { width: 180px; }
        col.segment { width: 90px; }
        col.state { width: 80px; }
        col.vehicles { width: 80px; }
        col.sales { width: 95px; }
        col.payments { width: 95px; }
        col.parking { width: 105px; }
        col.created { width: 100px; }
        col.activity { width: 360px; }
    </style>
</head>
<body>
    <table>
        <colgroup>
            <col class="name">
            <col class="doc-type">
            <col class="document">
            <col class="phone">
            <col class="email">
            <col class="city">
            <col class="address">
            <col class="segment">
            <col class="state">
            <col class="vehicles">
            <col class="sales">
            <col class="payments">
            <col class="parking">
            <col class="created">
            <col class="activity">
        </colgroup>
        <thead>
            <tr>
                <th colspan="15" class="title">Reporte de clientes - VehiPark</th>
            </tr>
            <tr>
                <th colspan="15" class="subtitle">Exportado el {$generatedAt} · Registros: {$total}</th>
            </tr>
            <tr>
                <th>Nombre completo</th>
                <th>Tipo de documento</th>
                <th>Documento</th>
                <th>Teléfono</th>
                <th>Correo electrónico</th>
                <th>Ciudad</th>
                <th>Dirección</th>
                <th>Segmento</th>
                <th>Estado</th>
                <th>Vehículos</th>
                <th>Ventas</th>
                <th>Pagos</th>
                <th>Parqueadero</th>
                <th>Fecha de registro</th>
                <th>Últimos movimientos</th>
            </tr>
        </thead>
        <tbody>
            {$rows}
        </tbody>
    </table>
</body>
</html>
HTML;
    }

}
