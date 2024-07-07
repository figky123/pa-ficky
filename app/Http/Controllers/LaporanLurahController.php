<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pemeriksaan;
use Carbon\Carbon;
use PDF;

class LaporanLurahController extends Controller
{
    public function index(Request $request)
    {
        $summaryData = [];

        // Get the selected month and year from the request
        $selectedMonth = $request->input('month', Carbon::now()->month); // Default to current month
        $selectedYear = $request->input('year', Carbon::now()->year); // Default to current year

        // Fetch all users with their RW data
        $users = User::all();

        foreach ($users as $user) {
            $RW = $user->RW;

            // Calculate the number of houses inspected with siklus 4 for this RW and selected month/year
            $jumlahrumahdiperiksa = Pemeriksaan::where('id_user', $user->id)
                ->where('siklus', 4)
                ->whereMonth('tgl_pemeriksaan', $selectedMonth)
                ->whereYear('tgl_pemeriksaan', $selectedYear)
                ->count();

            // Calculate the number of houses positive for jentik with siklus 4 and status_jentik "positif" for this RW and selected month/year
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

            // Group data by RW
            if (!isset($summaryData[$RW])) {
                $summaryData[$RW] = (object)[
                    'RW' => $RW,
                    'jumlahrumahdiperiksa' => 0,
                    'jumlahrumahpositif' => 0,
                    'ABJ' => 0,
                    'Kategori' => '',
                    'users' => [],
                    'bulan_tahun' => Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('F Y')
                ];
            }

            $summaryData[$RW]->jumlahrumahdiperiksa += $jumlahrumahdiperiksa;
            $summaryData[$RW]->jumlahrumahpositif += $jumlahrumahpositif;
            $summaryData[$RW]->users[] = $user;

            // Recalculate ABJ for the group
            $summaryData[$RW]->ABJ = (($summaryData[$RW]->jumlahrumahdiperiksa - $summaryData[$RW]->jumlahrumahpositif) / $summaryData[$RW]->jumlahrumahdiperiksa) * 100;

            // Re-determine the category for the group
            if ($summaryData[$RW]->ABJ >= 95) {
                $summaryData[$RW]->Kategori = 'Tidak Berisiko';
            } elseif ($summaryData[$RW]->ABJ >= 90 && $summaryData[$RW]->ABJ < 95) {
                $summaryData[$RW]->Kategori = 'Risiko Rendah';
            } elseif ($summaryData[$RW]->ABJ >= 50 && $summaryData[$RW]->ABJ < 90) {
                $summaryData[$RW]->Kategori = 'Risiko Sedang';
            } else {
                $summaryData[$RW]->Kategori = 'Risiko Tinggi';
            }
        }

        if ($request->has('pdf')) {
            return $this->generatePDF($summaryData, $selectedMonth, $selectedYear);
        }

        return view('user.laporan_lurah', compact('summaryData', 'selectedMonth', 'selectedYear'));
    }

    public function generatePDF($summaryData, $selectedMonth, $selectedYear)
    {
        $pdf = PDF::loadView('user.pdf', compact('summaryData', 'selectedMonth', 'selectedYear'));
        return $pdf->download('laporan_jentik.pdf');
    }
}
