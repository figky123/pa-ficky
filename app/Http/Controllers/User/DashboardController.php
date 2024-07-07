<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Pemeriksaan;
use Illuminate\Support\Facades\DB;



class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $summaryData = [];

        $totalWarga = User::where('role', 'Warga')->count();

        // Menghitung jumlah RW (distinct RW yang ada)
        $totalRW = User::distinct()->whereNotNull('RW')->count('RW');

        // Menghitung jumlah RT (distinct RT yang ada)
        $totalRT = User::distinct()->whereNotNull('RT')->count('RT');

        $selectedYear = $request->input('year', Carbon::now()->year);

        // Fetch positive houses by RW for the selected year
        $positiveHousesByRW = Pemeriksaan::selectRaw('users.RW as RW, count(*) as positive_count')
            ->join('users', 'pemeriksaans.id_user', '=', 'users.id')
            ->where('pemeriksaans.siklus', 4)
            ->whereYear('pemeriksaans.tgl_pemeriksaan', $selectedYear)
            ->where('pemeriksaans.status_jentik', 'positif')
            ->groupBy('users.RW')
            ->get();

        // Fetch years with available data for filter
        $years = Pemeriksaan::selectRaw('YEAR(tgl_pemeriksaan) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('user.index', compact('totalWarga', 'totalRW', 'totalRT', 'positiveHousesByRW', 'selectedYear', 'years'));
    }
    // Method untuk mengambil data grafik jumlah rumah positif berdasarkan RW

}
