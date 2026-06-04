<?php

namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehiculoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'placa' => ['required', 'string', 'max:20', 'unique:vehiculos,placa'],
            'tipo' => ['required', 'string', Rule::in(['automovil', 'camioneta', 'motocicleta', 'camion'])],
            'marca' => ['required', 'string', 'max:255'],
            'modelo' => ['required', 'string', 'max:255'],
            'anio' => ['nullable', 'integer', 'min:1900', 'max:' . now()->addYear()->year],
            'color' => ['nullable', 'string', 'max:255'],
            'vin' => ['nullable', 'string', 'max:255', 'unique:vehiculos,vin'],
            'kilometraje' => ['nullable', 'integer', 'min:0'],
            'precio_venta' => ['nullable', 'numeric', 'min:0'],
            'estado' => ['required', 'string', Rule::in(['disponible', 'vendido', 'reservado', 'mantenimiento'])],
            'observaciones' => ['nullable', 'string'],
        ];
    }
}
