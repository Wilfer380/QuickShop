<?php

namespace App\Http\Requests\Cupos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCupoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $cupoId = $this->route('cupo')?->id;

        return [
            'codigo' => ['required', 'string', 'max:255', Rule::unique('cupos', 'codigo')->ignore($cupoId)],
            'zona' => ['nullable', 'string', 'max:255'],
            'tipo_vehiculo' => ['required', 'string', Rule::in(['automovil', 'camioneta', 'motocicleta', 'camion'])],
            'estado' => ['required', 'string', Rule::in(['disponible', 'ocupado', 'mantenimiento', 'inactivo'])],
            'observaciones' => ['nullable', 'string'],
        ];
    }
}
