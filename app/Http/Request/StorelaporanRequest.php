<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class StorelaporanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_warga' => 'required',
            'tgl_laporan' => 'required',
            'ket_laporan' => 'required',
            'status_laporan' => '',
        ];
    }
}