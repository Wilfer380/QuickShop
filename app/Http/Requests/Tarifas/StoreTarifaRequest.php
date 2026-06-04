<?php

namespace App\Http\Requests\Tarifas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTarifaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255', 'unique:tarifas,nombre'],
            'tipo_vehiculo' => ['required', 'string', Rule::in(['automovil', 'camioneta', 'motocicleta', 'camion'])],
            'tipo_cobro' => ['required', 'string', Rule::in(['hora', 'dia', 'mes'])],
            'valor' => ['required', 'numeric', 'gt:0'],
            'activa' => ['required', 'boolean'],
            'descripcion' => ['nullable', 'string'],
        ];
    }
}
