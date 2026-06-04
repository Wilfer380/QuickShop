<?php

namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehiculoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vehiculoId = $this->route('vehiculo')?->id;

        return [
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'placa' => ['required', 'string', 'max:20', Rule::unique('vehiculos', 'placa')->ignore($vehiculoId)],
            'tipo' => ['required', 'string', Rule::in(['automovil', 'camioneta', 'motocicleta', 'camion'])],
            'marca' => ['required', 'string', 'max:255'],
            'modelo' => ['required', 'string', 'max:255'],
            'anio' => ['nullable', 'integer', 'min:1900', 'max:' . now()->addYear()->year],
            'color' => ['nullable', 'string', 'max:255'],
            'vin' => ['nullable', 'string', 'max:255', Rule::unique('vehiculos', 'vin')->ignore($vehiculoId)],
            'kilometraje' => ['nullable', 'integer', 'min:0'],
            'precio_venta' => ['nullable', 'numeric', 'min:0'],
            'estado' => ['required', 'string', Rule::in(['disponible', 'vendido', 'reservado', 'mantenimiento'])],
            'observaciones' => ['nullable', 'string'],
        ];
    }
}
