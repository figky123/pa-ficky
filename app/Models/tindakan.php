<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tindakan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_petugas', 'RW', 'tgl_tindakan', 'ket_tindakan', 'bukti_tindakan'
    ];
    protected $table = 'tindakans';
    protected $primaryKey = 'id';

    // Relasi dengan Warga
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Definisikan relasi ke model Pemeriksaan
    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'id_pemeriksaan');
    }
}

