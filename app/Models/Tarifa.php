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
        'activa',
        'descripcion',
    ];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
            'activa' => 'boolean',
        ];
    }

    public function movimientosParqueadero()
    {
        return $this->hasMany(MovimientoParqueadero::class);
    }
}
