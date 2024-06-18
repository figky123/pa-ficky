<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Request\StorelaporanRequest;
use App\Http\Request\UpdatelaporanRequest;
use App\Models\laporan;
use Illuminate\Http\Request;
use App\Models\warga;
use App\Models\user;
use App\Models\DB;
use Illuminate\Support\Facades\DB as FacadesDB;
use App\Mail\LaporanBaru;
use Illuminate\Support\Facades\Mail;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $laporans = laporan::with('user')->get();
        return view('user.laporan_warga', compact('laporans'));
    }
    /**
     * Show the form for creating a new resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('warga.form', [
            'laporans' => laporan::latest()->get(),
            'users' => user::latest()->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * * @param  \App\Http\Request\StorelaporanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Validasi data yang diterima dari request
        $request->validate([
            'id_user' => 'required',
            'tgl_laporan' => 'required',
            'ket_laporan' => 'required',
            'bukti_laporan' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // Anda mungkin perlu menambahkan validasi untuk kolom lainnya di sini
        ]);

        $bukti_laporan = $request->file('bukti_laporan');
        $file_ext = pathinfo($bukti_laporan->getClientOriginalName(), PATHINFO_EXTENSION);
        $file_name = 'bukti_laporan_' . date('YmdHi') . '.' . $file_ext;
        $bukti_laporan->move(public_path('storage/bukti_laporan'), $file_name);

        try {
            // Simpan data ke dalam database
            $laporan = new laporan();
            $laporan->id_user = $request->id_user;
            $laporan->tgl_laporan = $request->tgl_laporan;
            $laporan->status_laporan = 'proses'; // Nilai tetap
            $laporan->ket_laporan = $request->ket_laporan;
            $laporan->bukti_laporan = $file_name;
            $laporan->save();

            // Kirim email ke admin
            Mail::to('admlurah@gmail.com')->send(new LaporanBaru($laporan));


            // Jika berhasil disimpan, kembalikan respons
            return response()->json(['success' => true, 'message' => 'Data berhasil disimpan'], 200);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan respons dengan pesan error
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
        public function updateStatus($id, Request $request)
    {
        $laporan = Laporan::find($id);

        if ($laporan) {
            // Optionally validate the status input
            $request->validate([
                'status_laporan' => 'required|string|in:proses,tindaklanjut jumantik,tindaklanjut puskesmas,selesai,ditolak'
            ]);
            $laporan->status_laporan = $request->status_laporan;
            $laporan->save();

            return response()->json(['success' => true, 'message' => 'Status laporan berhasil diubah.']);
        }

        return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan.']);
    }
    public function getDetail($id)
    {
        $laporan = Laporan::with('warga')->findOrFail($id);
        return response()->json($laporan);
    }
    /**
     * Display the specified resource.
     */
    public function show(laporan $laporan)
    {
    }

    /**
     * Show the form for editing the specified resource.
     * @param  \App\Models\laporan  $laporan
     * @return \Illuminate\Http\Response
     */
    public function edit(laporan $laporan)
    {
        return view('laporan.edit', [
            'wargas' => warga::latest()->get(),
            'laporan' => $laporan
        ]);
    }

    /**
     * Update the specified resource in storage.
     * 
     *  @param  \App\Http\Request\UpdatelaporanRequest  $request
     * @param  \App\Models\laporan  $laporan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatelaporanRequest $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        laporan::find($id)->delete();
        return redirect()->route('laporans');
    }
}
