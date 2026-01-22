<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trafo extends Model
{
    protected $table = 'trafos';
    protected $fillable = [
        'user_id',
        'area_id',
        'rayon_id',
        'id_gardu',
        'sr',
        'daya',
        'merk',
        'alamat',
        'kelurahan',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'latitude',
        'longitude',
        'evidence',
    ];

    /**
     * Relasi ke User Pelapor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Many-to-Many.
     */
    public function pjus(): HasMany
    {
        return $this->hasMany(PJU::class);
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
