<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\laporan;
use App\Models\user;
use Illuminate\Support\Facades\Auth;

class FiturController extends Controller
{
    public function dashboard()
    {
        return view('warga.dashboard');
    }
    public function data()
    {
        $laporans = laporan::with('user')->get();
        $users = user::all();
        return view('warga.datajentik', compact('laporans', 'users'));
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima dari request
        $request->validate([
            'id_warga' => 'required',
            'tgl_laporan' => 'required|date', // tambahkan validasi untuk memastikan tgl_laporan adalah tanggal
            'ket_laporan' => 'required',
            // Anda mungkin perlu menambahkan validasi untuk kolom lainnya di sini
        ]);
    
        try {
            // Simpan data ke dalam database
            $laporan = new laporan();
            $laporan->id_warga = $request->id_warga;
            $laporan->tgl_laporan = \Carbon\Carbon::parse($request->tgl_laporan); // Ubah format ke tipe data date
            $laporan->status_laporan = 'Proses'; // Nilai tetap
            $laporan->ket_laporan = $request->ket_laporan;
            $laporan->save();
    
            // Jika berhasil disimpan, kembalikan respons
            return response()->json(['message' => 'Data berhasil disimpan'], 200);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan respons dengan pesan error
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
    
}
