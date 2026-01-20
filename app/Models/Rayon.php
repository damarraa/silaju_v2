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

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
}
