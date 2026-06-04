<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id')->withTimestamps();
    }

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'permiso_role', 'role_id', 'permiso_id')->withTimestamps();
    }
}
