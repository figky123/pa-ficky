<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class laporan extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_warga',
        'tgl_laporan',
        'ket_laporan',
        'status_laporan',
    ];

    public function warga()
    {
        return $this->hasMany(warga::class, 'id', 'id_warga');
    }

    public function pemeriksaan()
    {
        return $this->belongsTo(pemeriksaan::class, 'id_pemeriksaan');
    }

}
