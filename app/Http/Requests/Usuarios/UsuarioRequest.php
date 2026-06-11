<?php

namespace App\Http\Requests\Usuarios;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
