<?php

namespace App\Http\Controllers;

use App\Models\pemeriksaan;
use App\Models\laporan;
use App\Models\user;
use Illuminate\Support\Facades\Auth;
use App\Models\tindakan;
use Illuminate\Http\Request;
class TestingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        $selectedYear = $request->input('year', date('Y'));
        // Mendapatkan data chart berdasarkan tahun yang dipilih
        $chartData = Laporan::selectRaw('MONTH(tgl_laporan) as month, COUNT(*) as count')
            ->whereYear('tgl_laporan', $selectedYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        return $chartData;
    }
}
