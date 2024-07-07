<?php

namespace App\Http\Controllers;

use App\Models\pemeriksaan;
use App\Models\tindakan;
use App\Models\user;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TindakanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        {
            $tindakans = Tindakan::orderBy('tgl_tindakan', 'asc')->paginate(100);
            return view('user.laporanpuskesmas', compact('tindakans'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'RW' => 'required|string|max:255',
            'nama_petugas' => 'required|string|max:255',
            'tgl_tindakan' => 'required|date',
            'ket_tindakan' => 'required|string|max:255',
            'bukti_tindakan' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $bukti_tindakan = $request->file('bukti_tindakan');
        $file_ext = pathinfo($bukti_tindakan->getClientOriginalName(), PATHINFO_EXTENSION);
        $file_name = 'bukti_tindakan_' . date('YmdHi') . '.' . $file_ext;
        $bukti_tindakan->move(public_path('storage/bukti_tindakan'), $file_name);

        try {
            // Simpan data ke dalam database
            $tindakan = new tindakan();
            $tindakan->RW = $request->RW;
            $tindakan->nama_petugas = $request->nama_petugas;
            $tindakan->ket_tindakan = $request->ket_tindakan;
            $tindakan->bukti_tindakan = $file_name;
            $tindakan->tgl_tindakan = $request->tgl_tindakan;
            $tindakan->save();


            // Jika berhasil disimpan, kembalikan respons
            return response()->json(['message' => 'Data berhasil disimpan'], 200);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan respons dengan pesan error
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
