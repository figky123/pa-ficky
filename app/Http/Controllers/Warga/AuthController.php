<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{

    public function __construct()
    {
         Auth::setDefaultDriver('warga');
         config(['auth.defaults.passwords' => 'wargas']); 
    }

    public function loginWargaForm()
    {
        return view('warga.login');
    }

    public function registerWargaForm()
    {
        return view('warga.register');
    }



    public function logoutWarga(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/warga/login');
    }

    public function guard()
    {
        return Auth::guard('warga');
    }

    public function loginWarga(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::guard('warga')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return view('warga.index');
        } else {
            return redirect('warga/login');
        }
    }


    public function registerWarga(Request $request)
    {
        $request->validate([
            'nama_warga' => "required",
            'no_kk' => "required",
            'no_hp_warga' => "required",
            'email' => "required",
            'password' => "required",
            'alamat' => "required",
            'RT' => "required",
            'RW' => "required",
        ]);

        $warga = warga::create([
            'nama_warga' => $request->nama_warga,
            'no_kk' => $request->no_kk,
            'no_hp_warga' => $request->no_hp_warga,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'RT' => $request->RT,
            'RW' => $request->RW,
        ]);

        if ($warga) {
            return redirect('/warga/login', );;
        } else {
            return redirect()->back()->withErrors(['message' => 'Akun berhasil dibuat']);
        }
    }

    public function test()
    {
        if (Auth::guard('web')->check())
        {
             $id = auth()->guard('warga')->check();
             var_dump($id);
        }else{
            return 'gagal';
        }
    }
}
