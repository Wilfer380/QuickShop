<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionEmpresa extends Model
{
    use HasFactory;

    protected $table = 'configuracion_empresa';

    protected $fillable = [
        'nombre_empresa',
        'nit',
        'telefono',
        'email',
        'direccion',
        'moneda',
        'parametros',
    ];

    protected function casts(): array
    {
        return [
            'parametros' => 'array',
        ];
    }
}
