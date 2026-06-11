<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoParqueadero extends Model
{
    use HasFactory;

    protected $table = 'movimientos_parqueadero';

    protected $fillable = [
        'vehiculo_id',
        'cliente_id',
        'cupo_id',
        'tarifa_id',
        'registrado_por_id',
        'entrada_at',
        'salida_at',
        'minutos',
        'total',
        'estado',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'entrada_at' => 'datetime',
            'salida_at' => 'datetime',
            'minutos' => 'integer',
            'total' => 'decimal:2',
        ];
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function cupo()
    {
        return $this->belongsTo(CupoParqueadero::class, 'cupo_id');
    }

    public function tarifa()
    {
        return $this->belongsTo(Tarifa::class);
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
