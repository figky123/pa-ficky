<?php

namespace App\Http\Controllers;

use App\Models\pemeriksaan;
use App\Models\tindakan;
use App\Models\user;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TindakanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { {
            $tindakans = Tindakan::orderBy('tgl_tindakan', 'asc')->paginate(100);
            return view('user.laporanpuskesmas', compact('tindakans'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentDate = now()->format('Y-m-d');  // Get the current date

        // Validation rules
        $validator = Validator::make($request->all(), [
            'RW' => [
                'required',
                'numeric',
                'between:1,12',
                function ($attribute, $value, $fail) use ($currentDate) {
                    $exists = Tindakan::where('RW', $value)
                        ->whereDate('tgl_tindakan', $currentDate)
                        ->exists();

                    if ($exists) {
                        $fail('RW sudah ada pada tanggal ' . $currentDate . '.');
                    }
                }
            ],
            'nama_petugas' => 'required|string|max:255|regex:/^[a-zA-Z\s]*$/',
            'tgl_tindakan' => 'required|date|date_equals:' . $currentDate,
            'ket_tindakan' => 'required|string|max:255',
            'bukti_tindakan' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'RW.required' => 'RW harus diisi.',
            'RW.numeric' => 'RW harus berupa angka.',
            'RW.between' => 'RW harus antara 1 hingga 12.',
            'RW.unique' => 'RW sudah ada pada tanggal ' . $currentDate . '.',

            'nama_petugas.required' => 'Nama petugas harus diisi.',
            'nama_petugas.string' => 'Nama petugas harus berupa huruf.',
            'nama_petugas.max' => 'Nama petugas tidak boleh lebih dari 255 karakter.',
            'nama_petugas.regex' => 'Nama petugas hanya boleh mengandung huruf dan spasi.',

            'tgl_tindakan.required' => 'Tanggal tindakan harus diisi.',
            'tgl_tindakan.date' => 'Format tanggal tindakan tidak valid.',
            'tgl_tindakan.date_equals' => 'Tanggal tindakan harus hari ini.',

            'ket_tindakan.required' => 'Keterangan tindakan harus diisi.',
            'ket_tindakan.string' => 'Keterangan tindakan harus berupa string.',
            'ket_tindakan.max' => 'Keterangan tindakan tidak boleh lebih dari 255 karakter.',

            'bukti_tindakan.required' => 'Bukti tindakan harus diisi.',
            'bukti_tindakan.image' => 'Bukti tindakan harus berupa gambar.',
            'bukti_tindakan.mimes' => 'Bukti tindakan harus berupa file dengan format: jpeg, png, jpg, gif, svg.',
            'bukti_tindakan.max' => 'Ukuran file bukti tindakan tidak boleh lebih dari 2048KB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        $bukti_tindakan = $request->file('bukti_tindakan');
        $file_ext = pathinfo($bukti_tindakan->getClientOriginalName(), PATHINFO_EXTENSION);
        $file_name = 'bukti_tindakan_' . date('YmdHi') . '.' . $file_ext;
        $bukti_tindakan->move(public_path('storage/bukti_tindakan'), $file_name);

        try {
            // Simpan data ke dalam database
            $tindakan = Tindakan::create([
                'RW' => $request->RW,
                'nama_petugas' => $request->nama_petugas,
                'ket_tindakan' => $request->ket_tindakan,
                'bukti_tindakan' => $file_name,
                'tgl_tindakan' => $request->tgl_tindakan,
            ]);

            // Jika berhasil disimpan, kembalikan respons
            return response()->json(['message' => 'Data berhasil disimpan'], 200);
        } catch (\Exception $e) {
            // Kesalahan umum
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
