<?php

namespace App\Http\Requests\Tarifas;

use Illuminate\Foundation\Http\FormRequest;

class TarifaRequest extends FormRequest
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
