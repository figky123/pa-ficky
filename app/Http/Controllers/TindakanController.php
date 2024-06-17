<?php

namespace App\Http\Controllers;

use App\Models\pemeriksaan;
use App\Models\laporan;
use App\Models\user;
use Illuminate\Support\Facades\Auth;
use App\Models\tindakan;
use Illuminate\Http\Request;

class TindakanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { {
            $tindakans = tindakan::with('pemeriksaan.laporan.user')->latest()->paginate(100);
            return view('user.laporan_puskesmas', compact('tindakans'));
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
            'id_pemeriksaan' => 'required|exists:pemeriksaans,id',
            'tgl_tindakan' => 'required|date',
            'bukti_tindakan' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'aksi_tindakan' => 'required'
        ]);

        $bukti_laporan = $request->file('bukti_tindakan');
        $file_ext = pathinfo($bukti_laporan->getClientOriginalName(), PATHINFO_EXTENSION);
        $file_name = 'bukti_tindakan_' . date('YmdHi') . '.' . $file_ext;
        $bukti_laporan->move(public_path('storage/bukti_tindakan'), $file_name);

        try {
            // Simpan data ke dalam database
            $tindakan = new tindakan();
            $tindakan->id_user = Auth::id();
            $tindakan->id_pemeriksaan = $request->id_pemeriksaan;
            $tindakan->tgl_tindakan = $request->tgl_tindakan;
            $tindakan->ket_tindakan = $request->ket_tindakan;
            $tindakan->bukti_tindakan = $file_name;
            $tindakan->aksi_tindakan = $request->aksi_tindakan;
            $tindakan->status_tindakan = 'sudah ditindak'; // Nilai tetap
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
