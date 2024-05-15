<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\warga;

class WargaController extends Controller
{
    public function index()
    {
        $wargas = warga::latest()->paginate(100);
        return view('pegawai.table_warga', compact('wargas'));
    }
}
