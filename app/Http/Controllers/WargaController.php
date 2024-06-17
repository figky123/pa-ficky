<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user;

class WargaController extends Controller
{
    public function index()
    {
        $users = user::where('role', 'Warga')->paginate(10);
        return view('user.table_warga', ['users' => $users]);
    }
}