<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResources;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::whereIn('role', ['Puskesmas', 'RT', 'RW'])->latest()->paginate(100);
        return view('user.table_petugas', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]*$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'no_kk' => 'required|numeric|digits:16|unique:users,no_kk',
            'no_hp_user' => 'required|numeric|digits_between:10,13',
            'alamat' => 'required|string',
            'RT' => 'required|string|max:10',
            'RW' => 'required|string|max:10',
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
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',

           
            'no_kk.required' => 'Nomor KK harus diisi.',
            'no_kk.numeric' => 'Nomor KK harus berupa angka.',
            'no_kk.digits' => 'Nomor KK harus 16 digit.',
            'no_kk.unique' => 'Nomor KK sudah terdaftar.',


            'no_hp_user.required' => 'Nomor HP harus diisi.',
            'no_hp_user.numeric' => 'Nomor HP harus berupa angka.',
            'no_hp_user.digits_between' => 'Nomor HP harus antara 10 hingga 13 digit.',


            'alamat.required' => 'Alamat harus diisi.',
            'alamat.string' => 'Alamat harus berupa string.',

            'RT.required' => 'RT harus diisi.',
            'RT.string' => 'RT harus berupa string.',
            'RT.max' => 'RT tidak boleh lebih dari 10 karakter.',

            'RW.required' => 'RW harus diisi.',
            'RW.string' => 'RW harus berupa string.',
            'RW.max' => 'RW tidak boleh lebih dari 10 karakter.',

            'foto_kk.required' => 'Foto KK harus diunggah.',
            'foto_kk.image' => 'Foto KK harus berupa gambar.',
            'foto_kk.mimes' => 'Foto KK harus berformat jpeg, png, jpg, gif, atau svg.',
            'foto_kk.max' => 'Ukuran foto KK tidak boleh lebih dari 2MB.',
        ]);

        if ($validator->fails()) {
            // Kembalikan kesalahan validasi dalam format JSON
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Simpan file foto KK
        $foto_kk = $request->file('foto_kk');
        $file_ext = pathinfo($foto_kk->getClientOriginalName(), PATHINFO_EXTENSION);
        $file_name = 'foto_kk_' . date('YmdHi') . '.' . $file_ext;
        $foto_kk->move(public_path('storage/foto_kk'), $file_name);

        try {
            // Simpan data ke dalam database
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hash password
                'no_kk' => $request->no_kk,
                'no_hp_user' => $request->no_hp_user,
                'alamat' => $request->alamat,
                'RT' => $request->RT,
                'RW' => $request->RW,
                'role' => $request->role,
                'foto_kk' => $file_name,
            ]);

            // Kembalikan respons sukses
            return response()->json(['status' => true, 'message' => 'Data berhasil disimpan'], 200);
        } catch (\Exception $e) {
            // Kembalikan kesalahan server dalam format JSON
            return response()->json(['status' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }


    public function show(User $user)
    {
        return new UserResources(true, 'Data User Ditemukan!', $user);
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]*$/',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string',
            'no_hp_user' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->role = $request->role;
        $user->no_hp_user = $request->no_hp_user;
        $user->save();

        return new UserResources(true, 'Data User Berhasil Diupdate!', $user);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return new UserResources(true, 'Data User Berhasil Dihapus!', null);
    }
}
