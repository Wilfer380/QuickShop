<?php

namespace App\Http\Requests\Configuracion;

use Illuminate\Foundation\Http\FormRequest;

class ConfiguracionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_empresa' => ['nullable', 'string', 'max:255'],
            'nit' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'moneda' => ['nullable', 'string', 'max:10'],
            'parametros.horas_promocion_parqueadero' => ['nullable', 'integer', 'min:0', 'max:24'],
            'parametros.zona_horaria' => ['nullable', 'string', 'max:80'],
            'parametros.redondeo_minutos' => ['nullable', 'string', 'max:50'],
            'parametros.tiempo_minimo_cobro' => ['nullable', 'string', 'max:50'],
            'parametros.tolerancia_salida' => ['nullable', 'string', 'max:50'],
            'parametros.tarifa_perdida_ticket' => ['nullable', 'numeric', 'min:0'],
            'parametros.iva_incluido' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
