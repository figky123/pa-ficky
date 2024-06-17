<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\warga;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{

    public function loginUserForm()
    {
        return view('user.auth.login');
    }

    public function registerUserForm()
    {
        return view('user.auth.register');
    }


    public function logoutUser(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function guard()
    {
        return Auth::guard('User');
    }

    public function loginUser(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            return redirect('/dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }


    public function registerUser(Request $request)
    {
    
        $validator = Validator::make($request->all(), [
            'name' => "required",
            'email' => "required",
            'password' => "required",
            'no_kk' => "required",
            'no_hp_user' => "required",
            'alamat' => "required",
            'RT' => "required",
            'RW' => "required",
            'role' => "required",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 400);
            }

        $user = user::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_kk' => $request->no_kk,
            'no_hp_user' => $request->no_hp_user,
            'alamat' => $request->alamat,
            'RT' => $request->RT,
            'RW' => $request->RW,
            'role' => $request->role,
           
            
        ]);

        if ($user) {
            return redirect('/login', );;
        } else {
            return redirect('/user/gagal', );;
        }
    }

    public function test()
    {
        if (Auth::guard('web')->check())
        {
             $id = auth()->guard('User')->check();
             var_dump($id);
        }else{
            return 'gagal';
        }
    }
}
