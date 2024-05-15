<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Request\StorelaporanRequest;
use App\Http\Request\UpdatelaporanRequest;
use App\Models\laporan;
use App\Models\warga;


class LaporanController extends Controller
{
    /**
     * Display a listing of the resource
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('laporan.index', [
            'laporan' => laporan::Paginate(5)
        ]);
    }
    /**
     * Show the form for creating a new resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('warga.form',[
            'laporans' => laporan::latest()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * * @param  \App\Http\Request\StorelaporanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorelaporanRequest $request)
    {
        laporan::create($request->validated() );
        return redirect()->route('laporans');
    }

    /**
     * Display the specified resource.
     */
    public function show(laporan $laporan)
    {

    }

    /**
     * Show the form for editing the specified resource.
     * @param  \App\Models\laporan  $laporan
     * @return \Illuminate\Http\Response
     */
    public function edit(laporan $laporan)
    {
        return view('laporan.edit',[
            'wargas' => warga::latest()->get(),
            'laporan' => $laporan
        ]);
    }

    /**
     * Update the specified resource in storage.
     * 
     *  @param  \App\Http\Request\UpdatelaporanRequest  $request
     * @param  \App\Models\laporan  $laporan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatelaporanRequest $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        laporan::find($id)->delete();
        return redirect()->route('laporans');
    }
}
