<?php

namespace App\Http\Requests\Pagos;

use App\Support\Concerns\NormalizesMoneyInput;
use Illuminate\Foundation\Http\FormRequest;

class PagoRequest extends FormRequest
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
        return [
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'venta_id' => ['nullable', 'exists:ventas,id', 'required_without:movimiento_parqueadero_id'],
            'movimiento_parqueadero_id' => ['nullable', 'exists:movimientos_parqueadero,id', 'required_without:venta_id'],
            'concepto' => ['required', 'in:venta,parqueadero'],
            'metodo_pago' => ['required', 'string', 'max:30'],
            'valor' => ['required', 'numeric', 'gt:0'],
            'pagado_at' => ['nullable', 'date'],
            'referencia' => ['nullable', 'string', 'max:255'],
            'notas' => ['nullable', 'string'],
        ];
    }
}
