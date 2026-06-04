<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos';

    protected $fillable = [
        'cliente_id',
        'placa',
        'tipo',
        'marca',
        'modelo',
        'anio',
        'color',
        'vin',
        'kilometraje',
        'precio_venta',
        'estado',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'precio_venta' => 'decimal:2',
            'kilometraje' => 'integer',
        ];
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function venta()
    {
        return $this->hasOne(Venta::class);
    }

    public function movimientosParqueadero()
    {
        return $this->hasMany(MovimientoParqueadero::class);
    }
}
