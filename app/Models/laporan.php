<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class laporan extends Model
{
    use HasFactory;
    protected $table = 'laporans';
    protected $primaryKey = 'id';

    // Relasi dengan Warga
    public function user()
    {
        return $this->belongsTo(user::class, 'id_user');
    }

    public function pemeriksaan()
    {
        return $this->hasMany(pemeriksaan::class, 'id_laporan');
    }

}
