<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResources;
use Illuminate\Http\Request;
use App\Models\user;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $users = user::latest()->paginate(100);
        return view('pegawai.table_petugas', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
     
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
        ]);

        return new UserResources(true, 'Data User Berhasil Ditambahkan!', $user);


    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResources(true, 'Data User Ditemukan!', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('user.edit', [
            'User' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::where('id',$id);
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'email' => 'email',
            'password' => 'string',
            'role' => 'string',
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role'=> $request->role,
            ]);
        
        return new UserResources(true, 'Data Admin Berhasil Diubah!', $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        user::findorfail($id)->delete();
        return new UserResources(true, 'Data User Berhasil Dihapus!', null);
    }
}
