<?php

namespace App\Http\Requests\Trafo;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\Auth;

class StoreTrafoRequest extends FormRequest
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
            'id_gardu' => 'required|string|unique:trafos,id_gardu',
            'area_id' => 'required|exists:areas,id',
            'rayon_id' => 'required|exists:rayons,id',
            'sr' => 'nullable|string',
            'daya' => 'required|string',
            'merk' => 'required|string',

            // Lokasi
            'alamat' => 'required|string',
            'kelurahan' => 'required|string',
            'kecamatan' => 'required|string',
            'kabupaten' => 'required|string',
            'provinsi' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',

            // Gambar
            'evidence' => 'required|image|mimes:jpeg,png,jpg|max:5048',
        ];
    }
}
