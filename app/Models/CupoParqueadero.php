<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CupoParqueadero extends Model
{
    use HasFactory;

    protected $table = 'cupos';

    protected $fillable = [
        'codigo',
        'zona',
        'tipo_vehiculo',
        'estado',
        'observaciones',
    ];

    public function movimientosParqueadero()
    {
        return $this->hasMany(MovimientoParqueadero::class, 'cupo_id');
    }
}
