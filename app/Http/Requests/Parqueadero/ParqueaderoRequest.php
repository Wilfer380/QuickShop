<?php

namespace App\Http\Requests\Parqueadero;

use Illuminate\Foundation\Http\FormRequest;

class ParqueaderoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->routeIs('parqueadero.salida')) {
            return [
                'salida_at' => ['nullable', 'date'],
                'pago_salida' => ['nullable', 'numeric', 'min:0'],
                'metodo_pago' => ['nullable', 'string', 'max:30'],
                'referencia' => ['nullable', 'string', 'max:255'],
                'notas_pago' => ['nullable', 'string'],
                'observaciones' => ['nullable', 'string'],
            ];
        }

        return [
            'vehiculo_id' => ['required', 'exists:vehiculos,id'],
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'cupo_id' => ['nullable', 'exists:cupos,id'],
            'tarifa_id' => ['required', 'exists:tarifas,id'],
            'entrada_at' => ['nullable', 'date'],
            'observaciones' => ['nullable', 'string'],
        ];
    }
}
