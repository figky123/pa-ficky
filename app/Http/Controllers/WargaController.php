<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user;

class WargaController extends Controller
{
    public function index()
    {
        $users = user::latest()->paginate(100);
        return view('user.table_warga', compact('users'));
    }
}
