<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Request\UpdatepemeriksaanRequest;
use App\Models\laporan;
use App\Models\user;
use App\Models\pemeriksaan;
use App\Mail\PemeriksaanNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;


class PemeriksaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loggedInUser = Auth::user();
        $pemeriksaansQuery = Pemeriksaan::with('user');

        if ($loggedInUser->hasRole('Warga')) {
            $pemeriksaansQuery->where('id_user', $loggedInUser->id);
        } elseif ($loggedInUser->hasRole('RT')) {
            $pemeriksaansQuery->whereHas('user', function ($query) use ($loggedInUser) {
                $query->where('RT', $loggedInUser->RT);
            });
        }

        $pemeriksaans = $pemeriksaansQuery->orderBy('tgl_pemeriksaan', 'asc')->paginate(100);

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
            'id_user' => 'required|integer|exists:users,id',
            'tgl_pemeriksaan' => 'required|date',
            'siklus' => 'required|integer',
            'kaleng_bekas' => 'required|integer|in:1,0,-1',
            'ember' => 'required|integer|in:1,0,-1',
            'ban_bekas' => 'required|integer|in:1,0,-1',
            'vas_bunga' => 'required|integer|in:1,0,-1',
            'bak_mandi' => 'required|integer|in:1,0,-1',
            'lainnya_dalam' => 'required|integer|in:1,0,-1',
            'lainnya_luar' => 'required|integer|in:1,0,-1',
            'bukti_pemeriksaan' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ket_pemeriksaan' => 'required|string|max:255',
        ]);

        // Ambil data dari request
        $id_user = $request->input('id_user');
        $tgl_pemeriksaan = $request->input('tgl_pemeriksaan');

        // Cek apakah sudah ada pemeriksaan dalam minggu yang sama
        $startOfWeek = Carbon::parse($tgl_pemeriksaan)->startOfWeek();
        $endOfWeek = Carbon::parse($tgl_pemeriksaan)->endOfWeek();

        $existingPemeriksaan = Pemeriksaan::where('id_user', $id_user)
            ->whereBetween('tgl_pemeriksaan', [$startOfWeek, $endOfWeek])
            ->exists();

        if ($existingPemeriksaan) {
            return response()->json(['error' => 'Anda telah menginput data untuk minggu ini. Harap tunggu sampai minggu depan untuk menginput data baru.'], 400);
        }

        // Retrieve and process boolean values
        $keys = ['kaleng_bekas', 'ember', 'ban_bekas', 'vas_bunga', 'bak_mandi', 'lainnya_dalam', 'lainnya_luar'];
        $values = array_map(fn ($key) => $request->input($key), $keys);

        // Hitung penjumlahan nilai, abaikan nilai -1
        $sum = array_sum(array_filter($values, fn ($value) => $value != -1));

        // Tentukan status_jentik berdasarkan hasil penjumlahan
        $status_jentik = $sum > 0 ? 'positif' : 'negatif';

        // Handle file upload with try-catch
        $bukti_pemeriksaan = $request->file('bukti_pemeriksaan');
        $file_ext = pathinfo($bukti_pemeriksaan->getClientOriginalName(), PATHINFO_EXTENSION);
        $file_name = 'bukti_tindakan_' . date('YmdHi') . '.' . $file_ext;
        $bukti_pemeriksaan->move(public_path('storage/bukti_pemeriksaan'), $file_name);

        // Simpan ke database
        $pemeriksaan = new Pemeriksaan();
        $pemeriksaan->id_user = $validatedData['id_user'];
        $pemeriksaan->siklus = $validatedData['siklus'];
        $pemeriksaan->bukti_pemeriksaan = $file_name;
        $pemeriksaan->tgl_pemeriksaan = $validatedData['tgl_pemeriksaan'];
        foreach ($keys as $key) {
            $pemeriksaan->$key = $request->input($key);
        }
        $pemeriksaan->status_jentik = $status_jentik;
        $pemeriksaan->ket_pemeriksaan = $validatedData['ket_pemeriksaan'];

        $pemeriksaan->save();

        // Retrieve user data
        $user = User::find($validatedData['id_user']);
        $RT = $user->RT;
        $RW = $user->RW;
        $alamat = $user->alamat;

        // Send email notification if siklus is 4 and status_jentik is positif
        if ($pemeriksaan->siklus == 4 && $pemeriksaan->status_jentik == 'positif') {
            $data = [
                'id_user' => $pemeriksaan->user->name,
                'tgl_pemeriksaan' => $pemeriksaan->tgl_pemeriksaan,
                'siklus' => $pemeriksaan->siklus,
                'status_jentik' => $pemeriksaan->status_jentik,
                'RT' => $RT,
                'RW' => $RW,
                'alamat' => $alamat,
            ];
            Mail::to('admlurah@gmail.com')->send(new PemeriksaanNotification($data));
        }

        return redirect()->route('pemeriksaans')
            ->with('success', 'Data pemeriksaan berhasil disimpan.');
    }

    public function updateStatus($id, $status)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $pemeriksaan->status_pemeriksaan = $status;
        $pemeriksaan->save();

        return redirect()->back()->with('success', 'Status pemeriksaan berhasil diperbarui');
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
