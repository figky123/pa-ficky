<?php

namespace App\Http\Controllers\Pegawai;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pegawai.dashboard');
    }
}
