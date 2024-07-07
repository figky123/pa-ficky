<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pemeriksaan;
use Carbon\Carbon;

class LaporanRWController extends Controller
{
    public function index(Request $request)
    {
        $summaryData = [];

        // Get the selected month and year from the request, or use the current month and year as default
        $selectedMonth = $request->input('month', Carbon::now()->month);
        $selectedYear = $request->input('year', Carbon::now()->year);

        // Fetch all users with their RT data
        $users = User::all();

        foreach ($users as $user) {
            $RT = $user->RT;

            // Calculate the number of houses inspected with siklus 4 for this RT and within the selected month and year
            $jumlahrumahdiperiksa = Pemeriksaan::where('id_user', $user->id)
                ->where('siklus', 4)
                ->whereMonth('tgl_pemeriksaan', $selectedMonth)
                ->whereYear('tgl_pemeriksaan', $selectedYear)
                ->count();

            // Calculate the number of houses positive for jentik with siklus 4 and status_jentik "positif" for this RT and within the selected month and year
            $jumlahrumahpositif = Pemeriksaan::where('id_user', $user->id)
                ->where('siklus', 4)
                ->where('status_jentik', 'positif')
                ->whereMonth('tgl_pemeriksaan', $selectedMonth)
                ->whereYear('tgl_pemeriksaan', $selectedYear)
                ->count();

            if ($jumlahrumahdiperiksa == 0) {
                continue; // Skip this user if no houses were inspected
            }

            // Calculate ABJ
            $ABJ = (($jumlahrumahdiperiksa - $jumlahrumahpositif) / $jumlahrumahdiperiksa) * 100;

            // Determine the category based on ABJ
            if ($ABJ >= 95) {
                $Kategori = 'Tidak Berisiko';
            } elseif ($ABJ >= 90 && $ABJ < 95) {
                $Kategori = 'Risiko Rendah';
            } elseif ($ABJ >= 50 && $ABJ < 90) {
                $Kategori = 'Risiko Sedang';
            } else {
                $Kategori = 'Risiko Tinggi';
            }

            // Group data by RT
            if (!isset($summaryData[$RT])) {
                $summaryData[$RT] = (object)[
                    'RT' => $RT,
                    'jumlahrumahdiperiksa' => 0,
                    'jumlahrumahpositif' => 0,
                    'ABJ' => 0,
                    'Kategori' => '',
                    'bulan_tahun' => Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('F Y'),
                    'users' => []
                ];
            }

            $summaryData[$RT]->jumlahrumahdiperiksa += $jumlahrumahdiperiksa;
            $summaryData[$RT]->jumlahrumahpositif += $jumlahrumahpositif;
            $summaryData[$RT]->users[] = $user;

            // Recalculate ABJ for the group
            $summaryData[$RT]->ABJ = (($summaryData[$RT]->jumlahrumahdiperiksa - $summaryData[$RT]->jumlahrumahpositif) / $summaryData[$RT]->jumlahrumahdiperiksa) * 100;

            // Re-determine the category for the group
            if ($summaryData[$RT]->ABJ >= 95) {
                $summaryData[$RT]->Kategori = 'Tidak Berisiko';
            } elseif ($summaryData[$RT]->ABJ >= 90 && $summaryData[$RT]->ABJ < 95) {
                $summaryData[$RT]->Kategori = 'Risiko Rendah';
            } elseif ($summaryData[$RT]->ABJ >= 50 && $summaryData[$RT]->ABJ < 90) {
                $summaryData[$RT]->Kategori = 'Risiko Sedang';
            } else {
                $summaryData[$RT]->Kategori = 'Risiko Tinggi';
            }
        }

        return view('user.laporanRW', compact('summaryData', 'selectedMonth', 'selectedYear'));
    }
}
