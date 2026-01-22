<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rayon extends Model
{
    protected $table = 'rayons';

    protected $fillable = [
        'kode_rayon',
        'nama'
    ];

    /**
     * Relasi ke User.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relasi ke Area/UP3.
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
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
