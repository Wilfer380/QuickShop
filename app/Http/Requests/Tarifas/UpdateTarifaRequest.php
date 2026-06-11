<?php

namespace App\Http\Requests\Tarifas;

use App\Support\Concerns\NormalizesMoneyInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTarifaRequest extends FormRequest
{
    use NormalizesMoneyInput;

    protected function prepareForValidation(): void
    {
        $this->normalizeMoneyFields(['valor']);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tarifaId = $this->route('tarifa')?->id;

        return [
            'nombre' => ['required', 'string', 'max:255', Rule::unique('tarifas', 'nombre')->ignore($tarifaId)],
            'tipo_vehiculo' => ['required', 'string', Rule::in(['automovil', 'camioneta', 'motocicleta', 'camion'])],
            'tipo_cobro' => ['required', 'string', Rule::in(['hora', 'dia', 'mes'])],
            'valor' => ['required', 'numeric', 'gt:0'],
            'activa' => ['required', 'boolean'],
            'descripcion' => ['nullable', 'string'],
        ];
    }
}
