<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PJU extends Model
{
    protected $table = 'pjus';
    protected $fillable = [
        'user_id',
        'trafo_id',
        'area_id',
        'rayon_id',
        'evidence',
        'verification_status',
        'verified_at',
        'verified_by',
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
     * Helper Status.
     */
    public function isVerified()
    {
        return $this->verification_status === 'verified';
    }

    /**
     * Relasi ke User Pelapor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Trafo.
     */
    public function trafo(): BelongsTo
    {
        return $this->belongsTo(Trafo::class);
    }

    /**
     * Relasi ke Area/UP3.
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Relasi ke Rayon/ULP.
     */
    public function rayon(): BelongsTo
    {
        return $this->belongsTo(Rayon::class);
    }
}
