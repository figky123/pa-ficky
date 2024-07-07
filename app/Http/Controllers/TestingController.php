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
        $pemeriksaans = Pemeriksaan::where('siklus', 4)
        ->with('users') // Eager load user relationship to avoid N+1 problem
        ->get();
    }
}
