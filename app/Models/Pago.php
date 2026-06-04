<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'cliente_id',
        'venta_id',
        'movimiento_parqueadero_id',
        'recibido_por_id',
        'concepto',
        'metodo_pago',
        'valor',
        'pagado_at',
        'referencia',
        'estado',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
            'pagado_at' => 'datetime',
        ];
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function movimientoParqueadero()
    {
        return $this->belongsTo(MovimientoParqueadero::class);
    }

    public function recibidoPor()
    {
        return $this->belongsTo(User::class, 'recibido_por_id');
    }
}
