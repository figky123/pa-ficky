<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pemeriksaan extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_user',
        'id_laporan',
        'siklus',
        'indikator',
        'bukti_pemeriksaan',
        'ket_pemeriksaan',
        'tindakan',
    ];
    /*public function laporan()
    {
        return $this->hasOne(warga::class, 'id', 'id_laporan');
    }*/
    public function user()
    {
        return $this->hasMany(user::class, 'id', 'id_user');
    }

}
