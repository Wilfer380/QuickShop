<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $table = 'permisos';

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'permiso_role', 'permiso_id', 'role_id')->withTimestamps();
    }
}
