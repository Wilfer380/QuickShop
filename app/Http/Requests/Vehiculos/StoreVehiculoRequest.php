<?php

namespace App\Http\Requests\Vehiculos;

use App\Support\Concerns\NormalizesMoneyInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehiculoRequest extends FormRequest
{
    use NormalizesMoneyInput;

    protected function prepareForValidation(): void
    {
        $this->normalizeMoneyFields(['precio_compra', 'precio_venta']);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'placa' => ['required', 'string', 'max:20', 'unique:vehiculos,placa'],
            'tipo' => ['required', 'string', Rule::in(['carro', 'moto', 'camioneta', 'camion', 'otro', 'automovil', 'motocicleta'])],
            'marca' => ['required', 'string', 'max:255'],
            'modelo' => ['required', 'string', 'max:255'],
            'anio' => ['nullable', 'integer', 'min:1900', 'max:' . now()->addYear()->year],
            'color' => ['nullable', 'string', 'max:255'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'ubicacion' => ['nullable', 'string', Rule::in(['inventario venta', 'parqueadero', 'taller', 'vendido', 'reservado'])],
            'vin' => ['nullable', 'string', 'max:255', 'unique:vehiculos,vin'],
            'kilometraje' => ['nullable', 'integer', 'min:0'],
            'precio_compra' => ['nullable', 'integer', 'min:0'],
            'precio_venta' => ['nullable', 'integer', 'min:0'],
            'estado' => ['required', 'string', Rule::in(['disponible', 'vendido', 'reservado', 'mantenimiento', 'parqueado', 'inactivo'])],
            'observaciones' => ['nullable', 'string'],
        ];
    }

}
