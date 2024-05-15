<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class StorepemeriksaanRequest extends FormRequest
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
            'id_user' => 'required',
            'id_laporan' => 'required',
            'siklus' => 'required',
            'indikator' => 'required',
            'bukti_pemeriksaan' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ket_pemeriksaan' => 'required',
            'tindakan' => 'required',
        ];
    }
}