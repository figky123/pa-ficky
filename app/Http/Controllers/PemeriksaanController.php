<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Request\StorepemeriksaanRequest;
use App\Http\Request\UpdatepemeriksaanRequest;
use App\Models\laporan;
use App\Models\user;
use App\Models\pemeriksaan;



class PemeriksaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pemeriksaan.index', [
            'pemeriksaan' => pemeriksaan::Paginate(5)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pemeriksaan.create',[
            'users' => user::latest()->get(),
            'laporans' => laporan::latest()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param  \App\Http\Request\StorepemeriksaanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorepemeriksaanRequest $request)
    {
        $bukti_pemeriksaan = $request->file('bukti_pemeriksaan');
        $bukti_pemeriksaan-> storeAs('public/pemeriksaans', $bukti_pemeriksaan->hashName());
        $bukti_pemeriksaan = pemeriksaan::create([
            'id_user' => $request->id_user,
            'id_laporan' => $request->id_laporan,
            'siklus' => $request->siklus,
            'indikator' => $request->indikator,
            'bukti_pemeriksaan' => $bukti_pemeriksaan->hashName(),
            'ket_pemeriksaan' => $request->ket_pemeriksaan,
            'tindakan' => $request->tindakan,
        ]);
        return 'hello';
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(pemeriksaan $pemeriksaan)
    {
        return view('pemeriksaan.edit',[
            'users' => user::latest()->get(),
            'laporans' => laporan::latest()->get(),
            'pemeriksaan' => $pemeriksaan
        ]);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \App\Http\Request\UpdatepemeriksaanRequest  $request
     * @param  \App\Models\pemeriksaan  $pemeriksaan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatepemeriksaanRequest $request, $id)
    {
        $pemeriksaan = pemeriksaan::find($id);
        $pemeriksaan->auther_id = $request->author_id;
        $pemeriksaan->category_id = $request->category_id;
        $pemeriksaan->publisher_id = $request->publisher_id;
        $pemeriksaan->name = $request->name;
        $pemeriksaan->save();
        return redirect()->route('pemeriksaans');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        pemeriksaan::find($id)->delete();
        return redirect()->route('pemeriksaans');
    }
}
