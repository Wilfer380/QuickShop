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
        'imagen',
        'ubicacion',
        'precio_compra',
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
            'precio_compra' => 'decimal:2',
            'kilometraje' => 'integer',
        ];
    }

    public function getUbicacionAttribute($value): ?string
    {
        return match ((string) $value) {
            '0', 'inventario venta' => 'inventario venta',
            '1', 'parqueadero' => 'parqueadero',
            '2', 'taller' => 'taller',
            '3', 'vendido' => 'vendido',
            '4', 'reservado' => 'reservado',
            default => $value,
        };
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
