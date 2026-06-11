<?php

namespace App\Http\Requests\Ventas;

use App\Support\Concerns\NormalizesMoneyInput;
use Illuminate\Foundation\Http\FormRequest;

class VentaRequest extends FormRequest
{
    use NormalizesMoneyInput;

    protected function prepareForValidation(): void
    {
        $this->normalizeMoneyFields(['precio_base', 'descuento', 'impuestos', 'pago_inicial']);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $updating = $this->isMethod('put') || $this->isMethod('patch');

        return [
            'cliente_id' => ['required', 'exists:clientes,id'],
            'vehiculo_id' => ['required', 'exists:vehiculos,id'],
            'fecha_venta' => ['required', 'date'],
            'precio_base' => ['required', 'integer', 'min:0'],
            'descuento' => ['nullable', 'integer', 'min:0'],
            'impuestos' => ['nullable', 'integer', 'min:0'],
            'pago_inicial' => [$updating ? 'prohibited' : 'nullable', 'integer', 'min:0'],
            'metodo_pago' => [$updating ? 'prohibited' : 'nullable', 'string', 'max:30'],
            'referencia' => [$updating ? 'prohibited' : 'nullable', 'string', 'max:255'],
            'notas_pago' => [$updating ? 'prohibited' : 'nullable', 'string'],
            'notas' => ['nullable', 'string'],
        ];
    }

}
