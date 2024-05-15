<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class warga extends Authenticatable
{
    use HasFactory;
    protected $guard = 'warga';
    protected $primaryKey = 'id_warga';
    
    protected $fillable = [
        'nama_warga',
        'no_kk',
        'no_hp_warga',
        'email',
        'password',
        'alamat',
        'RT',
        'RW',
    ];
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

    public function laporan()
    {
        return $this->belongsTo(laporan::class, 'id', 'id_laporan');
    }
}
