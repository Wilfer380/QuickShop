<?php

namespace App\Http\Requests\Ventas;

use Illuminate\Foundation\Http\FormRequest;

class VentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => ['required', 'exists:clientes,id'],
            'vehiculo_id' => ['required', 'exists:vehiculos,id'],
            'fecha_venta' => ['required', 'date'],
            'precio_base' => ['required', 'numeric', 'min:0'],
            'descuento' => ['nullable', 'numeric', 'min:0'],
            'impuestos' => ['nullable', 'numeric', 'min:0'],
            'pago_inicial' => ['nullable', 'numeric', 'min:0'],
            'metodo_pago' => ['nullable', 'string', 'max:30'],
            'referencia' => ['nullable', 'string', 'max:255'],
            'notas_pago' => ['nullable', 'string'],
            'notas' => ['nullable', 'string'],
        ];
    }
}
