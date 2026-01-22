<?php

namespace App\Http\Requests\PJU;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\Auth;

class UpdatePJURequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return Auth::check();
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'sometimes|required|in:meterisasi,non_meterisasi,ilegal',
            'id_pelanggan' => 'sometimes|required_if:status,meterisasi|nullable|string',
            'trafo_id' => 'sometimes|nullable|exists:trafos,id',
            'daya' => 'sometimes|required_if:status,meterisasi|nullable|string',

            // Lokasi
            'area_id' => 'sometimes|required|exists:areas,id',
            'rayon_id' => 'sometimes|required|exists:rayons,id',
            'alamat' => 'sometimes|required|string',
            'kelurahan' => 'sometimes|required|string',
            'kecamatan' => 'sometimes|required|string',
            'kabupaten' => 'sometimes|required|string',
            'provinsi' => 'sometimes|required|string',
            'latitude' => 'sometimes|nullable|numeric',
            'longitude' => 'sometimes|nullable|numeric',

            // Teknis
            'jenis_lampu' => 'sometimes|required|string',
            'merk_lampu' => 'sometimes|required|string',
            'jumlah_lampu' => 'sometimes|required|integer|min:1',
            'watt' => 'sometimes|required|integer|min:1',

            // Status & Operasional
            'kondisi_lampu' => 'sometimes|required|in:baik,rusak',
            'tindak_lanjut' => 'sometimes|required|in:bongkar,putus,dibiarkan',
            'sistem_operasi' => 'sometimes|required|in:manual,photo_cell,timer',
            'installasi' => 'sometimes|required|in:kabel_tanah,kabel_udara',
            'peruntukan' => 'sometimes|required|in:jalan,taman,fasilitas_umum',

            // Kepemilikan & Nyala Siang
            'kepemilikan' => 'sometimes|required|in:pemda,swadaya',
            'nyala_siang' => 'sometimes|required|boolean',

            // Gambar
            'evidence' => 'sometimes|required|image|mimes:jpeg,png,jpg|max:5048',
        ];
    }
}
