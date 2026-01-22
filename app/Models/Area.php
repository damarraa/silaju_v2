<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    protected $table = 'areas';

    protected $fillable = [
        'kode',
        'nama'
    ];

    /**
     * Relasi ke Wilayah.
     */
    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class);
    }

    /**
     * Relasi ke PJU.
     */
    public function pjus(): HasMany
    {
        return $this->hasMany(PJU::class);
    }

    /**
     * Relasi ke Trafo.
     */
    public function trafos(): HasMany
    {
        return $this->hasMany(Trafo::class);
    }
}
