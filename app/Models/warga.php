<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class warga extends Authenticatable
{
    use HasFactory;
    protected $table = 'wargas';
    protected $primaryKey = 'id';

    // Relasi dengan Laporan
    public function laporans()
    {
        return $this->hasMany(laporan::class, 'id_warga');
    }

    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

}
