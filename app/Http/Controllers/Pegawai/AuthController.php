<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\user;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginPegawaiForm()
    {
        return view('pegawai.auth.login');
    }

    public function registerPegawaiForm()
    {
        return view('pegawai.auth.register');
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5|max:30'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return Auth::user();
        } else {
            return redirect()->back()->withErrors(['error' => 'Invalid username or password',]);
        }
    }

    public function loginPegawai(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('pegawai/dashboard');
        }  else {
            return redirect()->back()->withErrors(['errors' => $validator->errors()]);
        }
    }

    public function registerPegawai(Request $request)
    {
        $request->validate([
            'email' => "required|email",
            'name' => "required",
            'password' => "required",
            'role' => ["required", Rule::in(['lurah', 'jumantik', 'puskesmas'])]
        ]);

        $user = user::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($user) {
            return redirect('/dashboard');
        } else {
            return redirect()->back()->withErrors(['message' => 'Akun berhasil dibuat']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function test()
    {
        return Auth::user();
    }
}
