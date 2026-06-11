<?php

namespace App\Http\Requests\Tarifas;

use App\Support\Concerns\NormalizesMoneyInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreTarifaRequest extends FormRequest
{
    use NormalizesMoneyInput;

    protected function prepareForValidation(): void
    {
        $this->normalizeMoneyFields(['valor', 'tarifa_minuto', 'tarifa_hora', 'tarifa_dia', 'tarifa_noche']);

        $tarifaHora = $this->input('tarifa_hora') ?: $this->input('valor');
        $tarifaMinuto = $this->input('tarifa_minuto') ?: ($tarifaHora !== null && $tarifaHora !== '' ? (int) round(((int) $tarifaHora) / 60) : null);
        $tarifaDia = $this->input('tarifa_dia') ?: ($tarifaHora !== null && $tarifaHora !== '' ? (int) $tarifaHora * 6 : null);
        $tarifaNoche = $this->input('tarifa_noche') ?: ($tarifaHora !== null && $tarifaHora !== '' ? (int) $tarifaHora * 3 : null);
        $estado = (string) $this->input('estado', $this->boolean('activa') ? 'activa' : 'inactiva');
        $tipoVehiculo = trim((string) $this->input('tipo_vehiculo', ''));
        $zona = trim((string) $this->input('zona', ''));

        $this->merge([
            'tarifa_hora' => $tarifaHora,
            'tarifa_minuto' => $tarifaMinuto,
            'tarifa_dia' => $tarifaDia,
            'tarifa_noche' => $tarifaNoche,
            'valor' => $tarifaHora,
            'estado' => $estado,
            'activa' => $estado === 'activa',
            'nombre' => trim((string) ($this->input('nombre') ?: Str::title(str_replace('_', ' ', $tipoVehiculo)) . ($zona !== '' ? ' · ' . $zona : ''))),
            'icono' => $this->input('icono') ?: null,
            'zona' => $zona !== '' ? $zona : null,
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['nullable', 'string', 'max:255', 'unique:tarifas,nombre'],
            'tipo_vehiculo' => ['required', 'string', Rule::in(['automovil', 'carro', 'camioneta', 'motocicleta', 'moto', 'camion', 'bicicleta', 'otro'])],
            'tipo_cobro' => ['nullable', 'string', Rule::in(['minuto', 'hora', 'dia', 'mes'])],
            'icono' => ['nullable', 'string', 'max:50'],
            'zona' => ['nullable', 'string', 'max:50'],
            'tarifa_minuto' => ['nullable', 'integer', 'min:0'],
            'tarifa_hora' => ['nullable', 'integer', 'min:0'],
            'tarifa_dia' => ['nullable', 'integer', 'min:0'],
            'tarifa_noche' => ['nullable', 'integer', 'min:0'],
            'valor' => ['required', 'integer', 'min:0'],
            'estado' => ['required', 'string', Rule::in(['activa', 'inactiva'])],
            'activa' => ['nullable', 'boolean'],
            'descripcion' => ['nullable', 'string'],
            'observaciones' => ['nullable', 'string'],
        ];
    }
}
