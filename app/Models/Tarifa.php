<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    use HasFactory;

    protected $table = 'tarifas';

    protected $fillable = [
        'nombre',
        'tipo_vehiculo',
        'tipo_cobro',
        'valor',
        'icono',
        'tarifa_minuto',
        'tarifa_hora',
        'tarifa_dia',
        'tarifa_noche',
        'zona',
        'estado',
        'activa',
        'descripcion',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
            'tarifa_minuto' => 'decimal:2',
            'tarifa_hora' => 'decimal:2',
            'tarifa_dia' => 'decimal:2',
            'tarifa_noche' => 'decimal:2',
            'activa' => 'boolean',
        ];
    }

    public function displayEstado(): string
    {
        return $this->estado ?? ((bool) $this->activa ? 'activa' : 'inactiva');
    }

    public function displayIcon(): string
    {
        return $this->icono ?: match ((string) $this->tipo_vehiculo) {
            'moto', 'motocicleta' => 'moto',
            'camioneta' => 'camioneta',
            'camion' => 'camion',
            'bicicleta' => 'bicicleta',
            default => 'carro',
        };
    }

    public function baseHour(): float
    {
        return (float) ($this->tarifa_hora ?? $this->valor ?? 0);
    }

    public function baseMinute(): float
    {
        return (float) ($this->tarifa_minuto ?? round($this->baseHour() / 60));
    }

    public function baseDay(): float
    {
        return (float) ($this->tarifa_dia ?? ($this->baseHour() * 6));
    }

    public function baseNight(): float
    {
        return (float) ($this->tarifa_noche ?? ($this->baseHour() * 3));
    }

    public function movimientosParqueadero()
    {
        return $this->hasMany(MovimientoParqueadero::class);
    }
}
