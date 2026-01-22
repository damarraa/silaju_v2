<?php

namespace App\Http\Requests\PJU;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\Auth;

class StorePJURequest extends FormRequest
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
            'status' => 'required|in:meterisasi,non_meterisasi,ilegal',
            'id_pelanggan' => 'required_if:status,meterisasi|nullable|string',
            'trafo_id' => 'nullable|exists:trafos,id',
            'daya' => 'required_if:status,meterisasi|nullable|string',
            
            // Lokasi
            'area_id' => 'required|exists:areas,id',
            'rayon_id' => 'required|exists:rayons,id',
            'alamat' => 'required|string',
            'kelurahan' => 'required|string',
            'kecamatan' => 'required|string',
            'kabupaten' => 'required|string',
            'provinsi' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',

            // Teknis
            'jenis_lampu' => 'required|string',
            'merk_lampu' => 'required|string',
            'jumlah_lampu' => 'required|integer|min:1',
            'watt' => 'required|integer|min:1',

            // Status & Operasional
            'kondisi_lampu' => 'required|in:baik,rusak',
            'tindak_lanjut' => 'required|in:bongkar,putus,dibiarkan',
            'sistem_operasi' => 'required|in:manual,photo_cell,timer',
            'installasi' => 'required|in:kabel_tanah,kabel_udara',
            'peruntukan' => 'required|in:jalan,taman,fasilitas_umum',

            // Kepemilikan & Nyala Siang
            'kepemilikan' => 'required|in:pemda,swadaya',
            'nyala_siang' => 'required|boolean',

            // Gambar
            'evidence' => 'required|image|mimes:jpeg,png,jpg|max:5048',
        ];
    }

    /**
     * Summary of messages
     * @return array{daya.required_if: string, id_pelanggan.required_if: string}
     */
    public function messages()
    {
        return [
            'id_pelanggan.required_if' => 'ID Pelanggan wajib diisi jika status Meterisasi.',
            'daya.required_if' => 'Daya wajib diisi jika status Meterisasi.',
            'evidence.required' => 'Bukti foto wajib diunggah.',
            'evidence.max' => 'Ukuran foto maksimal 5MB.',
        ];
    }
}
