<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuracion\ConfiguracionRequest;
use App\Models\ConfiguracionEmpresa;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ConfiguracionController extends Controller
{
    private const DEFAULTS = [
        'nombre_empresa' => 'VehiPark',
        'nit' => null,
        'telefono' => null,
        'email' => null,
        'direccion' => null,
        'moneda' => 'COP',
        'parametros' => [
            'horas_promocion_parqueadero' => 2,
            'zona_horaria' => 'America/Bogota',
            'redondeo_minutos' => 'Cada 15 minutos',
            'tiempo_minimo_cobro' => '15 minutos',
            'tolerancia_salida' => '10 minutos',
            'tarifa_perdida_ticket' => 20000,
            'iva_incluido' => 19,
        ],
    ];

    public function index(): View
    {
        return view('configuracion.index', [
            'configuracion' => $this->configuracion(),
            'defaults' => self::DEFAULTS,
        ]);
    }

    public function update(ConfiguracionRequest $request): RedirectResponse
    {
        $configuracion = $this->configuracion();
        $validated = $request->validated();

        $configuracion->update([
            'nombre_empresa' => $this->value($validated, $configuracion, 'nombre_empresa'),
            'nit' => $this->value($validated, $configuracion, 'nit'),
            'telefono' => $this->value($validated, $configuracion, 'telefono'),
            'email' => $this->value($validated, $configuracion, 'email'),
            'direccion' => $this->value($validated, $configuracion, 'direccion'),
            'moneda' => strtoupper((string) $this->value($validated, $configuracion, 'moneda')),
            'parametros' => array_merge(
                self::DEFAULTS['parametros'],
                $configuracion->parametros ?? [],
                $this->parametros($validated)
            ),
        ]);

        return redirect()
            ->route('configuracion.index')
            ->with('status', 'Configuración guardada correctamente.');
    }

    private function configuracion(): ConfiguracionEmpresa
    {
        $configuracion = ConfiguracionEmpresa::query()->find(1);

        if (! $configuracion) {
            $configuracion = new ConfiguracionEmpresa(self::DEFAULTS);
            $configuracion->id = 1;
            $configuracion->save();
        }

        $configuracion->fill([
            'nombre_empresa' => $configuracion->nombre_empresa ?: self::DEFAULTS['nombre_empresa'],
            'moneda' => $configuracion->moneda ?: self::DEFAULTS['moneda'],
            'parametros' => array_merge(self::DEFAULTS['parametros'], $configuracion->parametros ?? []),
        ]);

        return $configuracion;
    }

    private function value(array $validated, ConfiguracionEmpresa $configuracion, string $key): mixed
    {
        if (! array_key_exists($key, $validated)) {
            return $configuracion->{$key};
        }

        return $validated[$key] ?: (self::DEFAULTS[$key] ?? null);
    }

    private function parametros(array $validated): array
    {
        return array_filter(
            $validated['parametros'] ?? [],
            static fn (mixed $value): bool => $value !== null && $value !== ''
        );
    }
}
