<?php
// SummaryController.php
namespace App\Http\Controllers;

use App\Models\laporan;
use App\Models\user;
use App\Models\pemeriksaan;
use Illuminate\Http\Request;
use DB;
use PDF;
use Illuminate\Support\Facades\Log;

class SummaryController extends Controller
{
    // Method to get summarized data
    protected function getSummaryData()
    {
        // Retrieve all pemeriksaan data with relationships and filter by user role 'Jumantik'
        $pemeriksaans = Pemeriksaan::with(['laporan.user'])
            ->whereHas('user', function ($query) {
                $query->where('role', 'Jumantik');
            })
            ->get()
            ->groupBy('id_user');

        if ($pemeriksaans->isEmpty()) {
            // Log or debug if no data is found
            Log::info('No pemeriksaans found with role Jumantik');
            dd('No pemeriksaans found with role Jumantik');
        }

        // Initialize array to hold summarized data
        $summaryData = [];

        foreach ($pemeriksaans as $userId => $userPemeriksaans) {
            // Count unique RT and RW
            $uniqueRTs = $userPemeriksaans->pluck('laporan.user.RT')->unique();
            $uniqueRTCount = $uniqueRTs->count();
            $uniqueRTs = $uniqueRTs->implode(', '); // Convert unique RTs to comma-separated string

            $uniqueRWs = $userPemeriksaans->pluck('laporan.user.RW')->unique();
            $uniqueRWCount = $uniqueRWs->count();
            $uniqueRWs = $uniqueRWs->implode(', '); // Convert unique RWs to comma-separated string

            // Count unique buildings inspected based on id_laporan
            $uniqueBuildingsCount = $userPemeriksaans->pluck('laporan.user.name')->unique()->count();

            // Count the number of buildings with positive and negative larvae
            $positiveLarvaeCount = 0;
            $negativeLarvaeCount = 0;
            $positiveLarvaeNames = []; // Array to store names of residents with positive larvae

            foreach ($userPemeriksaans as $pemeriksaan) {
                $jumlah = $pemeriksaan->kaleng_bekas + $pemeriksaan->pecahan_botol + $pemeriksaan->ban_bekas + $pemeriksaan->tempayan + $pemeriksaan->bak_mandi + $pemeriksaan->lain_lain;
                if ($jumlah > 0) {
                    // Jika jumlah larva lebih besar dari 0, maka itu adalah larva positif
                    $positiveLarvaeCount++;

                    // Kumpulkan nama penghuni dengan peran "Warga"
                    if ($pemeriksaan->laporan->user->role === 'Warga') {
                        $positiveLarvaeNames[] = $pemeriksaan->laporan->user->name;
                    }
                } else {
                    // Jika jumlah larva sama dengan 0, maka itu adalah larva negatif
                    $negativeLarvaeCount++;
                }
            }

            // Calculate ABJ (Angka Bebas Jentik)
            $abj = (($negativeLarvaeCount / $uniqueBuildingsCount) * 100);

            // Determine category based on ABJ
            $kategori = 'Sangat Buruk';
            if ($abj > 95) {
                $kategori = 'Baik';
            } elseif ($abj >= 85 && $abj <= 94) {
                $kategori = 'Sedang';
            } elseif ($abj >= 75 && $abj <= 84) {
                $kategori = 'Buruk';
            }

            // Get Nama Petugas and siklus
            $jumantikUser = $userPemeriksaans->first(function ($userPemeriksaan, $key) {
                return $userPemeriksaan->user->role === 'Jumantik';
            });

            // Jika pengguna dengan peran "Jumantik" ditemukan, gunakan nama petugas dan siklusnya; jika tidak, gunakan dari pengguna pertama kali dalam koleksi
            if ($jumantikUser) {
                $nama_petugas = $jumantikUser->user->name;
                $siklus = $jumantikUser->siklus;
            } else {
                $nama_petugas = $userPemeriksaans->first()->user->name; // Gunakan nama petugas pertama kali jika tidak ada "Jumantik"
                $siklus = $userPemeriksaans->first()->siklus; // Gunakan siklus pertama kali jika tidak ada "Jumantik"
            }
            // Store summarized data for each user
            $summaryData[] = [
                'nama_petugas' => $nama_petugas,
                'siklus' => $siklus,
                'uniqueRTs' => $uniqueRTs,
                'uniqueRWs' => $uniqueRWs,
                'uniqueRTCount' => $uniqueRTCount,
                'uniqueRWCount' => $uniqueRWCount,
                'uniqueBuildingsCount' => $uniqueBuildingsCount,
                'positiveLarvaeCount' => $positiveLarvaeCount,
                'positiveLarvaeNames' => implode(', ', $positiveLarvaeNames), // Convert names to comma-separated string
                'abj' => $abj,
                'kategori' => $kategori,
                'laporan' => $userPemeriksaans->pluck('laporan'), // Masukkan data laporan ke dalam array
            ];
        }

        return $summaryData;
    }

    // Method to show summary
    public function summary(Request $request)
    {
        $summaryData = $this->getSummaryData();
        return view('user.laporan_jumantik2', compact('summaryData'));
    }

    // Method to generate PDF
    public function generatePDF()
    {
        $summaryData = $this->getSummaryData();

        $data = [
            'summaryData' => $summaryData,
        ];

        // Load view and generate PDF
        $pdf = PDF::loadView('user.pdf', $data);
        return $pdf->download('laporan_jumantik.pdf');
    }
}
