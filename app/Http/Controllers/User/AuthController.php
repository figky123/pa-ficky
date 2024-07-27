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
use Illuminate\Support\Facades\Log;

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
            if ($user->status_akun === 'rejected') {
                return back()->with('error', 'Pendaftaran akun anda ditolak, silahkan hubungi adm@lurah.gmail.com untuk lebih lanjut');
            }

            if ($user->status_akun !== 'verified') {
                return back()->with('error', 'Akun belum diverifikasi, silahkan hubungi adm@lurah.gmail.com untuk lebih lanjut');
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
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]*$/',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:255',
            'no_kk' => 'required|numeric|digits:16|unique:users,no_kk',
            'no_hp_user' => 'required|numeric|digits_between:10,13|unique:users,no_hp_user',
            'alamat' => 'required|string|max:255',
            'RT' => 'required|numeric|between:1,57',
            'RW' => 'required|numeric|between:1,12',
            'role' => 'required|string',
            'foto_kk' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.string' => 'Nama harus berupa huruf.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'name.regex' => 'Nama hanya boleh mengandung huruf dan spasi.',

            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',

            'password.required' => 'Kata sandi harus diisi.',
            'password.string' => 'Kata sandi harus berupa string.',
            'password.min' => 'Kata sandi minimal harus 8 karakter.',
            'password.max' => 'Kata sandi tidak boleh lebih dari 255 karakter.',

            'no_kk.required' => 'Nomor KK harus diisi.',
            'no_kk.numeric' => 'Nomor KK harus berupa angka.',
            'no_kk.digits' => 'Nomor KK harus 16 digit.',
            'no_kk.unique' => 'Nomor KK sudah terdaftar.',

            'no_hp_user.required' => 'Nomor HP harus diisi.',
            'no_hp_user.numeric' => 'Nomor HP harus berupa angka.',
            'no_hp_user.digits_between' => 'Nomor HP harus antara 10 hingga 13 digit.',
            'no_hp_user.unique' => 'Nomor Hp sudah terdaftar.',

            'alamat.required' => 'Alamat harus diisi.',
            'alamat.string' => 'Alamat harus berupa string.',
            'alamat.max' => 'Alamat tidak boleh lebih dari 255 karakter.',

            'RT.required' => 'RT harus diisi.',
            'RT.numeric' => 'RT harus berupa angka.',
            'RT.between' => 'RT harus antara 1 hingga 57.',

            'RW.required' => 'RW harus diisi.',
            'RW.numeric' => 'RW harus berupa angka.',
            'RW.between' => 'RW harus antara 1 hingga 12.',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        $foto_kk = $request->file('foto_kk');
        $file_ext = pathinfo($foto_kk->getClientOriginalName(), PATHINFO_EXTENSION);
        $file_name = 'foto_kk_' . date('YmdHi') . '.' . $file_ext;
        $foto_kk->move(public_path('storage/foto_kk'), $file_name);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_kk' => $request->no_kk,
            'no_hp_user' => $request->no_hp_user,
            'alamat' => $request->alamat,
            'RT' => $request->RT,
            'RW' => $request->RW,
            'role' => $request->role,
            'status' => 'not verified', // Default status
            'foto_kk' => $file_name,
        ]);

        if ($user) {
            return redirect()->route('user.registerForm')->with('success', 'Akun Berhasil Dibuat');
        } else {
            return redirect()->route('user.registerForm')->with('error', 'Gagal membuat akun');
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

    public function deleteUser(Request $request)
    {
        $user = User::findOrFail($request->id);
        if ($user->status_akun === 'rejected') {
            $user->delete();
            return response()->json(['status' => true, 'message' => 'Akun berhasil dihapus.']);
        }
        return response()->json(['status' => false, 'message' => 'Akun tidak bisa dihapus.']);
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

    public function rejectUser(Request $request)
    {
        $user = User::find($request->id);

        if ($user) {
            // Update user status to 'rejected'
            $user->status_akun = 'rejected';
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Pendaftaran Akun berhasil ditolak.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Akun tidak ditemukan.'
            ]);
        }
    }
}
