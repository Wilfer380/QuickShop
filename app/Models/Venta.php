<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'cliente_id',
        'vehiculo_id',
        'vendedor_id',
        'fecha_venta',
        'precio_base',
        'descuento',
        'impuestos',
        'total',
        'estado',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha_venta' => 'date',
            'precio_base' => 'decimal:2',
            'descuento' => 'decimal:2',
            'impuestos' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
