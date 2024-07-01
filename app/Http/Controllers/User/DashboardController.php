<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Pemeriksaan;
use App\Models\Laporan;
use App\Models\User;
use App\Models\Tindakan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Menghitung total data warga
        $totalWarga = User::where('role', 'Warga')->count('id');

        // Menghitung total bangunan yang sudah diperiksa
        $totalRumahSudahDiperiksa = Pemeriksaan::count('id');

        // Menghitung total laporan yang belum diperiksa
        $totalRumahBelumDiperiksa = Laporan::leftJoin('pemeriksaans', 'laporans.id', '=', 'pemeriksaans.id_laporan')
            ->whereNull('pemeriksaans.id_laporan')
            ->count();

        // Menghitung jumlah status jentik positif
        $pemeriksaans = Pemeriksaan::all();
        $jumlahStatusJentikPositif = 0;
        $jumlahStatusJentikNegatif = 0;

        foreach ($pemeriksaans as $pemeriksaan) {
            $values = [
                $pemeriksaan->kaleng_bekas,
                $pemeriksaan->pecahan_botol,
                $pemeriksaan->ban_bekas,
                $pemeriksaan->tempayan,
                $pemeriksaan->bak_mandi,
                $pemeriksaan->lain_lain
            ];

            // Abaikan nilai -1 dalam perhitungan
            $jumlah = array_reduce($values, function ($carry, $item) {
                return $item == -1 ? $carry : $carry + $item;
            }, 0);

            // Status jentik positif jika jumlahnya lebih dari 0
            if ($jumlah > 0) {
                $jumlahStatusJentikPositif++;
            }

            // Status jentik negatif jika jumlahnya sama dengan 0
            if ($jumlah == 0) {
                $jumlahStatusJentikNegatif++;
            }
        }

        // Menghitung jumlah laporan
        $jumlahlaporan = Laporan::count('id');

        // Menghitung jumlah laporan yang sudah ditindak
        $totalSudahDitindak = Tindakan::count('id');

        // Menghitung total pemeriksaan yang belum ditindak
        $totalBelumDitindak = DB::table('pemeriksaans')
            ->leftJoin('tindakans', 'pemeriksaans.id', '=', 'tindakans.id_pemeriksaan')
            ->whereNull('tindakans.id_pemeriksaan')
            ->count();

        // Mengambil tahun dari permintaan atau default ke tahun saat ini
        $year = $request->input('year', date('Y'));

        // Mengambil data untuk chart berdasarkan tahun
        $chartData = Laporan::selectRaw('MONTH(tgl_laporan) as month, COUNT(*) as count')
            ->whereYear('tgl_laporan', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Jika permintaan berasal dari AJAX, kembalikan data chart dalam JSON format
        if ($request->ajax()) {
            return response()->json(['chartData' => $chartData]);
        }

        // Mengirimkan nilai total ke view 'pegawai.index'
        return view('user.index', [
            'totalWarga' => $totalWarga,
            'totalRumahSudahDiperiksa' => $totalRumahSudahDiperiksa,
            'totalRumahBelumDiperiksa' => $totalRumahBelumDiperiksa,
            'jumlahStatusJentikPositif' => $jumlahStatusJentikPositif,
            'jumlahStatusJentikNegatif' => $jumlahStatusJentikNegatif,
            'jumlahlaporan' => $jumlahlaporan,
            'totalSudahDitindak' => $totalSudahDitindak,
            'totalBelumDitindak' => $totalBelumDitindak,
            'chartData' => $chartData,
            'year' => $year
        ]);
    }
}
