<?php

namespace App\Http\Requests\Trafo;

use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\Auth;

class UpdateTrafoRequest extends FormRequest
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
            'id_gardu' => 'sometimes|required|string|unique:trafos,id_gardu',
            'area_id' => 'sometimes|required|exists:areas,id',
            'rayon_id' => 'sometimes|required|exists:rayons,id',
            'sr' => 'sometimes|nullable|string',
            'daya' => 'sometimes|required|string',
            'merk' => 'sometimes|required|string',

            // Lokasi
            'alamat' => 'sometimes|required|string',
            'kelurahan' => 'sometimes|required|string',
            'kecamatan' => 'sometimes|required|string',
            'kabupaten' => 'sometimes|required|string',
            'provinsi' => 'sometimes|required|string',
            'latitude' => 'sometimes|nullable|numeric',
            'longitude' => 'sometimes|nullable|numeric',

            // Gambar
            'evidence' => 'sometimes|required|image|mimes:jpeg,png,jpg|max:5048',
        ];
    }
}
