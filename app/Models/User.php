<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'phone',
        'documento',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'role_user', 'user_id', 'role_id')->withTimestamps();
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role || $this->roles()->where('name', $role)->exists();
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'vendedor_id');
    }

    public function movimientosParqueadero()
    {
        return $this->hasMany(MovimientoParqueadero::class, 'registrado_por_id');
    }

    public function pagosRecibidos()
    {
        return $this->hasMany(Pago::class, 'recibido_por_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
