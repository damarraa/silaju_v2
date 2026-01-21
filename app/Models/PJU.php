<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PJU extends Model
{
    protected $table = 'pjus';
    protected $fillable = [
        'user_id',
        'evidence',
        'id_pelanggan',
        'daya',
        'status',
        'alamat',
        'kelurahan',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'latitude',
        'longitude',
        'jenis_lampu',
        'merk_lampu',
        'jumlah_lampu',
        'watt',
        'kondisi_lampu',
        'tindak_lanjut',
        'sistem_operasi',
        'installasi',
        'kepemilikan',
        'peruntukan',
        'nyala_siang',
    ];

    protected $casts = [
        'nyala_siang' => 'boolean',
        'jumlah_lampu' => 'integer',
        'watt' => 'integer',
    ];

    /**
     * Relasi ke User Pelapor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
