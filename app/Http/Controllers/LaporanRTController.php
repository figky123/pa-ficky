<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user;
use Illuminate\Support\Facades\Auth;
use App\Models\pemeriksaan;
use Carbon\Carbon;

class LaporanRTController extends Controller
{
    public function index()
    {
        $loggedInUser = Auth::user();
    $pemeriksaansQuery = Pemeriksaan::where('siklus', 4)
        ->with('user') // Eager load user relationship to avoid N+1 problem
        ->orderBy('tgl_pemeriksaan', 'asc'); // Mengurutkan dari tanggal paling awal

    // Kondisi untuk user dengan role RT
    if ($loggedInUser->hasRole('RT')) {
        $pemeriksaansQuery->whereHas('user', function ($query) use ($loggedInUser) {
            $query->where('RT', $loggedInUser->RT);
        });
    }

    // Kondisi untuk user dengan role RW
    if ($loggedInUser->hasRole('RW')) {
        $pemeriksaansQuery->whereHas('user', function ($query) use ($loggedInUser) {
            $query->where('RW', $loggedInUser->RW);
        });
    }

    $pemeriksaans = $pemeriksaansQuery->get();

    // Format tanggal pemeriksaan
    foreach ($pemeriksaans as $pemeriksaan) {
        $formattedDate = Carbon::parse($pemeriksaan->tgl_pemeriksaan)->translatedFormat('F Y');
        $pemeriksaan->formatted_tgl_pemeriksaan = $formattedDate;
    }

    return view('user.laporanRT', compact('pemeriksaans'));
}
}
