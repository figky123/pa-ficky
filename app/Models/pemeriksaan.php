<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pemeriksaan extends Model
{
    use HasFactory;
    protected $table = 'pemeriksaans';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_user',
        'id_laporan',
        'siklus',
        'kaleng_bekas' ,
        'pecahan_botol' ,
        'ban_bekas' ,
        'tempayan' ,
        'bak_mandi' ,
        'lain_lain' ,
        'status_jentik',
        'bukti_pemeriksaan',
        'ket_pemeriksaan',
        'tindakan',
        'status_jentik'
    ];
    /*public function laporan()
    {
        return $this->hasOne(warga::class, 'id', 'id_laporan');
    }*/
    public function laporan()
    {
        return $this->belongsTo(laporan::class, 'id_laporan');
    }

    public function user()
    {
        return $this->belongsTo(user::class, 'id_user');
    }

    public function tindakan()
    {
        return $this->hasMany(Tindakan::class, 'id_pemeriksaan');
    }
}


