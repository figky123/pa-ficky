<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Request\StorepemeriksaanRequest;
use App\Http\Request\UpdatepemeriksaanRequest;
use App\Models\laporan;
use App\Models\user;
use App\Models\pemeriksaan;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PemeriksaanResource;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\RequestStack;
use Illuminate\Support\Carbon;


class PemeriksaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pemeriksaans = pemeriksaan::with('laporan.user')->latest()->paginate(100);
        return view('user.laporan_jumantik1', compact('pemeriksaans'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $users = user::latest()->get();
        $laporans = laporan::with('user')->latest()->get(); // Ensure that 'user' relationship is loaded
        return view('user.modal-create', compact('users', 'laporans'));
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param  \App\Http\Request\StorepemeriksaanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'id_laporan' => 'required|integer|exists:laporans,id', // Add exists validation
            'id_user' => 'required|integer',
            'siklus' => 'required|integer',
            'kaleng_bekas' => 'required|integer|in: 1,0,-1',
            'pecahan_botol' => 'required|integer|in: 1,0,-1',
            'ban_bekas' => 'required|integer|in: 1,0,-1',
            'tempayan' => 'required|integer|in: 1,0,-1',
            'bak_mandi' => 'required|integer|in: 1,0,-1',
            'lain_lain' => 'required|integer|in:1,0,-1',
            'bukti_pemeriksaan' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ket_pemeriksaan' => 'required|string|max:255',
        ]);

        // Tentukan nilai kolom boolean sebagai integer
        $kaleng_bekas_value = $request->input('kaleng_bekas');
        $pecahan_botol_value = $request->input('pecahan_botol');
        $ban_bekas_value = $request->input('ban_bekas');
        $tempayan_value = $request->input('tempayan');
        $bak_mandi_value = $request->input('bak_mandi');
        $lain_lain_value = $request->input('lain_lain');


        $bukti_pemeriksaan = $request->file('bukti_pemeriksaan');
        $file_ext = pathinfo($bukti_pemeriksaan->getClientOriginalName(), PATHINFO_EXTENSION);
        $file_name = 'bukti_pemeriksaan_' . date('YmdHi') . '.' . $file_ext;
        $bukti_pemeriksaan->move(public_path('storage/bukti_pemeriksaan'), $file_name);

        // Simpan ke database
        $pemeriksaan = new Pemeriksaan();
        $pemeriksaan->id_laporan = $validatedData['id_laporan'];
        $pemeriksaan->id_user = $validatedData['id_user'];
        $pemeriksaan->siklus = $validatedData['siklus'];
        $pemeriksaan->kaleng_bekas = $kaleng_bekas_value;
        $pemeriksaan->pecahan_botol = $pecahan_botol_value;
        $pemeriksaan->ban_bekas = $ban_bekas_value;
        $pemeriksaan->tempayan = $tempayan_value;
        $pemeriksaan->bak_mandi = $bak_mandi_value;
        $pemeriksaan->lain_lain = $lain_lain_value;
        $pemeriksaan->bukti_pemeriksaan = $file_name;
        $pemeriksaan->ket_pemeriksaan = $validatedData['ket_pemeriksaan'];

        $pemeriksaan->save();

        // Hitung jumlah yang bernilai 1 dan 0
        $jumlah_1 = ($kaleng_bekas_value == 1 ? 1 : 0) +
            ($pecahan_botol_value == 1 ? 1 : 0) +
            ($ban_bekas_value == 1 ? 1 : 0) +
            ($tempayan_value == 1 ? 1 : 0) +
            ($bak_mandi_value == 1 ? 1 : 0) +
            ($lain_lain_value == 1 ? 1 : 0);

        $jumlah_0 = ($kaleng_bekas_value == 0 ? 1 : 0) +
            ($pecahan_botol_value == 0 ? 1 : 0) +
            ($ban_bekas_value == 0 ? 1 : 0) +
            ($tempayan_value == 0 ? 1 : 0) +
            ($bak_mandi_value == 0 ? 1 : 0) +
            ($lain_lain_value == 0 ? 1 : 0);

        // Jumlah total nilai 1 dan 0
        $jumlah = $jumlah_1 + $jumlah_0;

        // Redirect ke halaman index atau halaman lainnya dengan pesan sukses
        return redirect()->back()->with('success', 'Data berhasil ditambahkan')->with('jumlah', $jumlah);
    }

    public function updateStatus(Request $request)
    {
        try {
            $pemeriksaan = Pemeriksaan::findOrFail($request->id);
            $pemeriksaan->tindakan = $request->status; // Updated to use $request->status instead of $request->tindakan
            $pemeriksaan->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
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
    public function edit(pemeriksaan $pemeriksaan)
    {
        return view('pemeriksaan.edit', [
            'users' => user::latest()->get(),
            'laporans' => laporan::latest()->get(),
            'pemeriksaan' => $pemeriksaan
        ]);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \App\Http\Request\UpdatepemeriksaanRequest  $request
     * @param  \App\Models\pemeriksaan  $pemeriksaan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatepemeriksaanRequest $request, $id)
    {
        $pemeriksaan = pemeriksaan::find($id);
        $pemeriksaan->auther_id = $request->author_id;
        $pemeriksaan->category_id = $request->category_id;
        $pemeriksaan->publisher_id = $request->publisher_id;
        $pemeriksaan->name = $request->name;
        $pemeriksaan->save();
        return redirect()->route('pemeriksaans');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        pemeriksaan::find($id)->delete();
        return redirect()->route('pemeriksaans');
    }
}
