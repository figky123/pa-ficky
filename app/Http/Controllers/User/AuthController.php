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

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if ($user->status_akun !== 'verified') {
                return back()->with('error', 'Akun belum diverifikasi, silahkan hubungi admin untuk lebih lanjut');
            }

            Auth::login($user);
            $request->session()->flash('success', 'Login berhasil');
            return redirect()->intended('/dashboard');
        }

        return back()->with('error', 'Email atau password salah');
    }



    public function registerUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => "required|string|max:255|regex:/^[a-zA-Z\s]*$/",
            'email' => "required|email|max:255",
            'password' => "required|string|min:8|max:255",
            'no_kk' => "required|numeric|digits:16",
            'no_hp_user' => "required|numeric|min:10|max:15",
            'alamat' => "required|string|max:255",
            'RT' => "required|numeric|max:10",
            'RW' => "required|numeric|max:10",
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
            return redirect()->route('user.registerForm')->with('success', 'Akun Berhasil Dibuat');
        } else {
            return redirect('/user/gagal');
        }
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id); // Sesuaikan dengan model dan primary key yang digunakan

        // Jika menggunakan form model binding, Anda bisa langsung mengirimkan $user ke view
        return view('user.editwarga', compact('user'));
    }


    public function updateUser(Request $request, $id)
    {
        // Validasi data yang masuk
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'no_kk' => 'nullable|string|max:255',
            'no_hp_user' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'RT' => 'nullable|string|max:10',
            'RW' => 'nullable|string|max:10',
        ]);

        // Cari data user berdasarkan ID
        $user = User::findOrFail($id);

        // Update data user berdasarkan input dari form
        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_kk = $request->no_kk;
        $user->no_hp_user = $request->no_hp_user;
        $user->alamat = $request->alamat;
        $user->RT = $request->RT;
        $user->RW = $request->RW;

        // Simpan perubahan
        $user->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('wargas')->with('success', 'Data warga berhasil diperbarui.');
    }


    public function test()
    {
        if (Auth::guard('web')->check()) {
            $id = auth()->guard('User')->check();
            var_dump($id);
        } else {
            return 'gagal';
        }
    }

    public function verifyUser(Request $request)
    {
        $user = User::find($request->id);
        if ($user) {
            $user->status_akun = 'verified';
            $user->save();
            return response()->json(['status' => true, 'message' => 'User verified successfully.']);
        }
        return response()->json(['status' => false, 'message' => 'User not found.']);
    }
}
