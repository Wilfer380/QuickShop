<?php

namespace App\Http\Controllers\Tarifas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tarifas\StoreTarifaRequest;
use App\Http\Requests\Tarifas\UpdateTarifaRequest;
use App\Models\Auditoria;
use App\Models\ConfiguracionEmpresa;
use App\Models\CupoParqueadero;
use App\Models\MovimientoParqueadero;
use App\Models\Tarifa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TarifasController extends Controller
{
    private const TIPOS_VEHICULO = ['carro', 'moto', 'camioneta', 'camion', 'bicicleta', 'otro'];
    private const TIPOS_COBRO = ['minuto', 'hora', 'dia', 'mes'];

    public function index(Request $request): View
    {
        [$query, $search, $estado] = $this->tarifasQuery($request);

        $tarifas = $query->paginate(5)->withQueryString();

        return view('tarifas.index', [
            'tarifas' => $tarifas,
            'search' => $search,
            'estado' => $estado,
            'metrics' => $this->metrics(),
            'configuracionTarifas' => $this->configuracionTarifas(),
            'tarifasPorZona' => $this->tarifasPorZona(),
            'historialTarifas' => $this->historialTarifas(),
        ]);
    }

    public function create(): View
    {
        return view('tarifas.create', $this->formPayload(new Tarifa([
            'estado' => 'activa',
            'activa' => true,
            'tipo_cobro' => 'hora',
        ])));
    }

    public function store(StoreTarifaRequest $request): RedirectResponse
    {
        $tarifa = Tarifa::create($this->normalizePayload($request->validated()));
        $this->logAudit($request, 'creacion', $tarifa, null, $tarifa->fresh()->toArray());

        return redirect()
            ->route('tarifas.show', $tarifa)
            ->with('status', 'Tarifa creada correctamente.');
    }

    public function show(Tarifa $tarifa): View
    {
        $tarifa->loadCount('movimientosParqueadero');

        return view('tarifas.show', [
            'tarifa' => $tarifa,
            'insights' => $this->tarifaInsights($tarifa),
            'historialTarifa' => Auditoria::query()
                ->with('user')
                ->where('auditable_type', Tarifa::class)
                ->where('auditable_id', $tarifa->id)
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }

    public function edit(Tarifa $tarifa): View
    {
        return view('tarifas.edit', $this->formPayload($tarifa));
    }

    public function update(UpdateTarifaRequest $request, Tarifa $tarifa): RedirectResponse
    {
        $before = $tarifa->replicate()->toArray();
        $tarifa->update($this->normalizePayload($request->validated()));
        $this->logAudit($request, 'actualizacion', $tarifa, $before, $tarifa->fresh()->toArray());

        return redirect()
            ->route('tarifas.show', $tarifa)
            ->with('status', 'Tarifa actualizada correctamente.');
    }

    public function duplicate(Request $request, Tarifa $tarifa): RedirectResponse
    {
        $copy = $tarifa->replicate();
        $copy->nombre = $this->uniqueCopyName($tarifa->nombre);
        $copy->estado = 'activa';
        $copy->activa = true;
        $copy->save();

        $this->logAudit($request, 'duplicacion', $copy, null, $copy->fresh()->toArray());

        return redirect()
            ->route('tarifas.show', $copy)
            ->with('status', 'Tarifa duplicada correctamente.');
    }

    public function destroy(Request $request, Tarifa $tarifa): RedirectResponse
    {
        $before = $tarifa->replicate()->toArray();

        if ($tarifa->movimientosParqueadero()->exists()) {
            $tarifa->update(['activa' => false, 'estado' => 'inactiva']);
            $this->logAudit($request, 'inactivacion', $tarifa, $before, $tarifa->fresh()->toArray());

            return redirect()
                ->route('tarifas.index')
                ->with('status', 'Tarifa inactivada porque tiene movimientos relacionados.');
        }

        $tarifa->delete();
        $this->logAudit($request, 'eliminacion', $tarifa, $before, null);

        return redirect()
            ->route('tarifas.index')
            ->with('status', 'Tarifa eliminada correctamente.');
    }

    private function tarifasQuery(Request $request): array
    {
        $search = trim((string) $request->query('q', ''));
        $estado = (string) $request->query('estado', 'todos');

        $query = Tarifa::query()->latest('id');

        if ($search !== '') {
            $query->where(function ($subquery) use ($search): void {
                $subquery->where('nombre', 'like', '%' . $search . '%')
                    ->orWhere('tipo_vehiculo', 'like', '%' . $search . '%')
                    ->orWhere('zona', 'like', '%' . $search . '%');
            });
        }

        if ($estado !== 'todos') {
            $query->where(function ($subquery) use ($estado): void {
                if ($estado === 'activa') {
                    $subquery->where('activa', true)->orWhere('estado', 'activa');
                } else {
                    $subquery->where('activa', false)->orWhere('estado', 'inactiva');
                }
            });
        }

        return [$query, $search, $estado];
    }

    private function metrics(): array
    {
        $rateColumn = $this->baseRateColumn();
        $activeTarifas = Tarifa::query()->where(function ($query): void {
            $query->where('activa', true)->orWhere('estado', 'activa');
        });

        $promedio = (float) (clone $activeTarifas)->avg($rateColumn);
        $ingresosMes = (float) MovimientoParqueadero::query()
            ->whereNotNull('salida_at')
            ->whereBetween('salida_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('total');

        return [
            [
                'label' => 'Tipos de vehículo',
                'value' => Tarifa::query()->distinct()->count('tipo_vehiculo'),
                'subtitle' => 'Configurados',
                'tone' => 'blue',
                'icon' => 'car',
            ],
            [
                'label' => 'Tarifa promedio/hora',
                'value' => $this->money($promedio),
                'subtitle' => 'Todas las tarifas',
                'tone' => 'green',
                'icon' => 'money',
            ],
            [
                'label' => 'Zonas activas',
                'value' => CupoParqueadero::query()->whereNotNull('zona')->distinct()->count('zona'),
                'subtitle' => 'Habilitadas',
                'tone' => 'orange',
                'icon' => 'calendar',
            ],
            [
                'label' => 'Tarifas activas',
                'value' => $activeTarifas->count(),
                'subtitle' => 'En uso actualmente',
                'tone' => 'purple',
                'icon' => 'tag',
            ],
            [
                'label' => 'Ingresos estimados',
                'value' => $this->money($ingresosMes),
                'subtitle' => 'Este mes',
                'tone' => 'teal',
                'icon' => 'chart',
            ],
        ];
    }

    private function configuracionTarifas(): array
    {
        $config = ConfiguracionEmpresa::query()->latest('id')->first();
        $parametros = $config?->parametros ?? [];

        return [
            ['label' => 'Redondeo de tiempo', 'value' => $parametros['redondeo_minutos'] ?? 'Cada 15 minutos', 'icon' => 'clock'],
            ['label' => 'Tiempo mínimo de cobro', 'value' => $parametros['tiempo_minimo_cobro'] ?? '15 minutos', 'icon' => 'timer'],
            ['label' => 'Tolerancia de salida', 'value' => $parametros['tolerancia_salida'] ?? '10 minutos', 'icon' => 'arrow-right'],
            ['label' => 'Tarifa por pérdida de ticket', 'value' => $this->money((float) ($parametros['tarifa_perdida_ticket'] ?? 20000)), 'icon' => 'ticket'],
            ['label' => 'IVA incluido', 'value' => ($parametros['iva_incluido'] ?? 19) . '%', 'icon' => 'percent'],
        ];
    }

    private function tarifasPorZona(): array
    {
        $zones = CupoParqueadero::query()
            ->whereNotNull('zona')
            ->select('zona')
            ->distinct()
            ->orderBy('zona')
            ->pluck('zona');

        $palette = ['#3B82F6', '#22C55E', '#F97316', '#7C3AED', '#F59E0B'];

        $rateColumn = $this->baseRateColumn();

        return $zones->values()->map(function (string $zona, int $index) use ($palette, $rateColumn): array {
            $tarifaZona = Tarifa::query()->where('zona', $zona)->where(function ($query): void {
                $query->where('activa', true)->orWhere('estado', 'activa');
            })->avg($rateColumn);

            $titulo = match (strtoupper($zona)) {
                'A' => 'Zona A - Planta Baja',
                'B' => 'Zona B - Sótano 1',
                'C' => 'Zona C - Sótano 2',
                default => 'Zona ' . $zona,
            };

            return [
                'name' => $titulo,
                'subtitle' => 'Tarifa promedio',
                'rate' => $this->money((float) ($tarifaZona ?: 0)),
                'color' => $palette[$index % count($palette)],
            ];
        })->all();
    }

    private function historialTarifas()
    {
        return Auditoria::query()
            ->with('user')
            ->where('auditable_type', Tarifa::class)
            ->latest()
            ->limit(8)
            ->get();
    }

    private function tarifaInsights(Tarifa $tarifa): array
    {
        return [
            ['label' => 'Por minuto', 'value' => $this->money($tarifa->baseMinute()), 'tone' => 'blue'],
            ['label' => 'Por hora', 'value' => $this->money($tarifa->baseHour()), 'tone' => 'green'],
            ['label' => 'Día completo', 'value' => $this->money($tarifa->baseDay()), 'tone' => 'orange'],
            ['label' => 'Noche', 'value' => $this->money($tarifa->baseNight()), 'tone' => 'purple'],
        ];
    }

    private function formPayload(Tarifa $tarifa): array
    {
        return [
            'tarifa' => $tarifa,
            'tiposVehiculo' => self::TIPOS_VEHICULO,
            'tiposCobro' => self::TIPOS_COBRO,
            'estados' => ['activa' => 'Activa', 'inactiva' => 'Inactiva'],
            'zonas' => CupoParqueadero::query()->whereNotNull('zona')->distinct()->orderBy('zona')->pluck('zona'),
        ];
    }

    private function normalizePayload(array $data): array
    {
        $baseHour = (float) ($data['tarifa_hora'] ?? $data['valor'] ?? 0);
        $baseMinute = (float) ($data['tarifa_minuto'] ?? round($baseHour / 60));
        $baseDay = (float) ($data['tarifa_dia'] ?? ($baseHour * 6));
        $baseNight = (float) ($data['tarifa_noche'] ?? ($baseHour * 3));
        $estado = (string) ($data['estado'] ?? ((bool) ($data['activa'] ?? true) ? 'activa' : 'inactiva'));

        $nombre = trim((string) ($data['nombre'] ?? ''));
        if ($nombre === '') {
            $nombre = Str::title(str_replace('_', ' ', (string) ($data['tipo_vehiculo'] ?? 'tarifa')));

            if (! empty($data['zona'])) {
                $nombre .= ' · ' . $data['zona'];
            }
        }

        $payload = [
            'nombre' => $nombre,
            'tipo_vehiculo' => $data['tipo_vehiculo'] ?? null,
            'tipo_cobro' => $data['tipo_cobro'] ?? null,
            'valor' => $baseHour,
            'estado' => $estado,
            'activa' => $estado === 'activa',
            'zona' => $data['zona'] ?? null,
            'icono' => $data['icono'] ?? null,
            'descripcion' => $data['descripcion'] ?? null,
            'observaciones' => $data['observaciones'] ?? null,
        ];

        if (Schema::hasColumn('tarifas', 'tarifa_minuto')) {
            $payload['tarifa_minuto'] = $baseMinute;
        }

        if (Schema::hasColumn('tarifas', 'tarifa_hora')) {
            $payload['tarifa_hora'] = $baseHour;
        }

        if (Schema::hasColumn('tarifas', 'tarifa_dia')) {
            $payload['tarifa_dia'] = $baseDay;
        }

        if (Schema::hasColumn('tarifas', 'tarifa_noche')) {
            $payload['tarifa_noche'] = $baseNight;
        }

        return $payload;
    }

    private function baseRateColumn(): string
    {
        return Schema::hasColumn('tarifas', 'tarifa_hora') ? 'tarifa_hora' : 'valor';
    }

    private function logAudit(Request $request, string $accion, ?Tarifa $tarifa, ?array $before, ?array $after): void
    {
        Auditoria::create([
            'user_id' => $request->user()?->id,
            'accion' => $accion,
            'auditable_type' => Tarifa::class,
            'auditable_id' => $tarifa?->id,
            'datos_anteriores' => $before,
            'datos_nuevos' => $after,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    private function uniqueCopyName(string $name): string
    {
        $copyName = $name . ' (copia)';
        $counter = 2;

        while (Tarifa::query()->where('nombre', $copyName)->exists()) {
            $copyName = $name . ' (copia ' . $counter . ')';
            $counter++;
        }

        return $copyName;
    }

    private function money(float $value): string
    {
        return '$' . number_format($value, 0, ',', '.');
    }
}
