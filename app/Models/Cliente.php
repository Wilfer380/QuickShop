<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'tipo_documento',
        'documento',
        'nombres',
        'apellidos',
        'telefono',
        'email',
        'direccion',
        'foto',
        'ciudad',
        'segmento',
        'estado',
    ];

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function movimientosParqueadero()
    {
        return $this->hasMany(MovimientoParqueadero::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
