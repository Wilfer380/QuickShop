<?php

namespace App\Http\Requests\Clientes;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_documento' => ['required', 'string', 'max:20'],
            'documento' => ['required', 'string', 'max:40', 'unique:clientes,documento'],
            'nombres' => ['required', 'string', 'max:255'],
            'apellidos' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255', 'unique:clientes,email'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'ciudad' => ['nullable', 'string', 'max:120'],
            'segmento' => ['required', 'in:frecuente,activo,nuevo,inactivo'],
            'estado' => ['required', 'in:activo,inactivo'],
        ];
    }
}
