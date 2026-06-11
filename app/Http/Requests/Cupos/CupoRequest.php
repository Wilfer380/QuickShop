<?php

namespace App\Http\Requests\Cupos;

use Illuminate\Foundation\Http\FormRequest;

class CupoRequest extends FormRequest
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
