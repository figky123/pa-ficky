<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class user extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'users';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'no_kk',
        'no_hp_user',
        'email',
        'password',
        'role',
        'alamat',
        'RT',
        'RW'
    ];

    public function laporans()
    {
        return $this->hasMany(laporan::class, 'id_user');
    }

    public function pemeriksaan()
    {
        return $this->hasMany(pemeriksaan::class, 'id_user');
    }

    public function hasRole($role)
    {
        return $this->role === $role; // Misalnya, jika kolom untuk peran adalah 'role'
    }

    public function tindakan()
    {
        return $this->hasMany(Tindakan::class, 'id_user');
    }

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
