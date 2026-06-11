<?php

namespace App\Http\Requests\Clientes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clienteId = $this->route('cliente')?->id;

        return [
            'tipo_documento' => ['required', 'string', 'max:20'],
            'documento' => ['required', 'string', 'max:40', Rule::unique('clientes', 'documento')->ignore($clienteId)],
            'nombres' => ['required', 'string', 'max:255'],
            'apellidos' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('clientes', 'email')->ignore($clienteId)],
            'direccion' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'ciudad' => ['nullable', 'string', 'max:120'],
            'segmento' => ['required', 'in:frecuente,activo,nuevo,inactivo'],
            'estado' => ['required', 'in:activo,inactivo'],
        ];
    }
}
