<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\StoreVehiculoRequest;
use App\Http\Requests\Vehiculos\UpdateVehiculoRequest;
use App\Models\Cliente;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VehiculosController extends Controller
{
    private const TIPOS = ['carro', 'moto', 'camioneta', 'camion', 'otro'];
    private const ESTADOS = ['disponible', 'vendido', 'reservado', 'mantenimiento', 'parqueado', 'inactivo'];
    private const UBICACIONES = [
        'inventario venta' => 'Inventario venta',
        'parqueadero' => 'Parqueadero',
        'taller' => 'Taller',
        'vendido' => 'Vendido',
        'reservado' => 'Reservado',
    ];

    public function index(Request $request): View
    {
        [$vehiculosQuery, $search, $tipo, $estado, $anio] = $this->vehiculosQuery($request);

        $vehiculos = $vehiculosQuery->paginate(7)->withQueryString();

        $stats = [
            ['label' => 'Vehículos totales', 'value' => Vehiculo::count(), 'trend' => '15% vs. mes anterior', 'tone' => 'blue', 'icon' => 'car'],
            ['label' => 'Disponibles para venta', 'value' => Vehiculo::whereNotIn('estado', ['vendido', 'inactivo'])->count(), 'trend' => '12% vs. mes anterior', 'tone' => 'green', 'icon' => 'tag'],
            ['label' => 'Vehículos vendidos', 'value' => Vehiculo::where('estado', 'vendido')->count(), 'trend' => '18% vs. mes anterior', 'tone' => 'purple', 'icon' => 'cart'],
            ['label' => 'En parqueadero', 'value' => Vehiculo::whereIn('ubicacion', ['parqueadero', '1', 1])->count(), 'trend' => '8% vs. mes anterior', 'tone' => 'orange', 'icon' => 'parking'],
            ['label' => 'Valor inventario', 'value' => '$' . number_format((float) Vehiculo::whereIn('estado', ['disponible', 'reservado', 'parqueado', 'mantenimiento'])->sum('precio_venta'), 0, ',', '.'), 'trend' => '22% vs. mes anterior', 'tone' => 'teal', 'icon' => 'money'],
        ];

        $vehiculoTipos = collect(array_merge(['todos' => 'todos'], array_combine(self::TIPOS, self::TIPOS)));

        return view('vehiculos.index', compact('vehiculos', 'stats', 'search', 'tipo', 'estado', 'anio', 'vehiculoTipos'));
    }

    public function create(): View
    {
        return view('vehiculos.create', [
            'vehiculo' => new Vehiculo(),
            'clientes' => Cliente::query()->orderBy('nombres')->get(),
            'tipos' => self::TIPOS,
            'estados' => self::ESTADOS,
            'ubicaciones' => self::UBICACIONES,
        ]);
    }

    public function store(StoreVehiculoRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('vehiculos', 'public');
        }

        $vehiculo = Vehiculo::create($data);

        return redirect()
            ->route('vehiculos.show', $vehiculo)
            ->with('status', 'Vehiculo creado correctamente.');
    }

    public function show(Vehiculo $vehiculo): View
    {
        $vehiculo->load('cliente', 'venta');

        return view('vehiculos.show', compact('vehiculo'));
    }

    public function imagen(Vehiculo $vehiculo)
    {
        abort_unless($vehiculo->imagen && Storage::disk('public')->exists($vehiculo->imagen), 404);

        return response()->file(Storage::disk('public')->path($vehiculo->imagen), [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }

    public function edit(Vehiculo $vehiculo): View
    {
        return view('vehiculos.edit', [
            'vehiculo' => $vehiculo,
            'clientes' => Cliente::query()->orderBy('nombres')->get(),
            'tipos' => self::TIPOS,
            'estados' => self::ESTADOS,
            'ubicaciones' => self::UBICACIONES,
        ]);
    }

    public function update(UpdateVehiculoRequest $request, Vehiculo $vehiculo): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('imagen')) {
            if ($vehiculo->imagen) {
                Storage::disk('public')->delete($vehiculo->imagen);
            }

            $data['imagen'] = $request->file('imagen')->store('vehiculos', 'public');
        }

        $vehiculo->update($data);

        return redirect()
            ->route('vehiculos.show', $vehiculo)
            ->with('status', 'Vehiculo actualizado correctamente.');
    }

    public function destroy(Vehiculo $vehiculo): RedirectResponse
    {
        if ($vehiculo->imagen) {
            Storage::disk('public')->delete($vehiculo->imagen);
        }

        $vehiculo->delete();

        return redirect()
            ->route('vehiculos.index')
            ->with('status', 'Vehiculo eliminado correctamente.');
    }

    public function exportar(Request $request)
    {
        [$vehiculosQuery] = $this->vehiculosQuery($request);
        $vehiculos = $vehiculosQuery->get();
        $fileName = 'vehiculos-vehipark.csv';

        return response()->streamDownload(function () use ($vehiculos) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fwrite($handle, "sep=;\n");
            fputcsv($handle, ['Vehículo', 'Placa', 'Tipo', 'Marca', 'Modelo', 'Año', 'Color', 'Kilometraje', 'Precio compra', 'Precio venta', 'Estado', 'Ubicación'], ';');
            foreach ($vehiculos as $vehiculo) {
                fputcsv($handle, [
                    trim($vehiculo->marca . ' ' . $vehiculo->modelo),
                    $vehiculo->placa,
                    $this->tipoLabel($vehiculo->tipo),
                    $vehiculo->marca,
                    $vehiculo->modelo,
                    $vehiculo->anio,
                    $vehiculo->color,
                    $vehiculo->kilometraje,
                    number_format((float) ($vehiculo->precio_compra ?? 0), 0, ',', '.'),
                    number_format((float) ($vehiculo->precio_venta ?? 0), 0, ',', '.'),
                    ucfirst($vehiculo->estado),
                    $vehiculo->ubicacion,
                ], ';');
            }
            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * @return array{0:\Illuminate\Database\Eloquent\Builder,1:string,2:string,3:string,4:string}
     */
    private function vehiculosQuery(Request $request): array
    {
        $search = trim((string) $request->query('q', ''));
        $tipo = $request->query('tipo', 'todos');
        $estado = $request->query('estado', 'todos');
        $anio = $request->query('anio', 'todos');

        $query = Vehiculo::query()->with('cliente')->latest('id');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('placa', 'like', "%{$search}%")
                    ->orWhere('marca', 'like', "%{$search}%")
                    ->orWhere('modelo', 'like', "%{$search}%")
                    ->orWhere('color', 'like', "%{$search}%");
            });
        }

        if ($tipo !== 'todos') {
            $query->where('tipo', $tipo);
        }

        if ($estado !== 'todos') {
            $query->where('estado', $estado);
        }

        if ($anio !== 'todos') {
            if ($anio === 'anteriores') {
                $query->where('anio', '<=', 2020);
            } else {
                $query->where('anio', $anio);
            }
        }

        return [$query, $search, $tipo, $estado, $anio];
    }

    private function tipoLabel(string $tipo): string
    {
        return match ($tipo) {
            'automovil', 'carro' => 'Carro',
            'motocicleta', 'moto' => 'Moto',
            'camioneta' => 'Camioneta',
            'camion' => 'Camión',
            default => 'Otro',
        };
    }
}
