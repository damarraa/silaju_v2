<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'identity_number',
        'rayon_id',
        'name',
        'email',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke Rayon/ULP.
     */
    public function rayon(): BelongsTo
    {
        return $this->belongsTo(Rayon::class);
    }

    /**
     * Relasi ke PJU.
     */
    public function pjus(): HasMany
    {
        return $this->hasMany(PJU::class);
    }

    /**
     * Boot method create user.
     * Cek kode rayon dan urutan terakhir terlebih dahulu.
     * Incoming update: Penambahan atomic lock atau DB Transaction.
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->identity_number) && $user->rayon_id) {
                $rayon = Rayon::find($user->rayon_id);
                $kodeRayon = $rayon->kode_rayon;

                $lastUser = User::where('rayon_id', $user->rayon_id)
                    ->orderBy('identity_number', 'desc')
                    ->first();

                if ($lastUser) {
                    $lastSequence = (int) substr($lastUser->identity_number, -3);
                    $newSequence = $lastSequence + 1;
                } else {
                    $newSequence = 1;
                }

                $user->identity_number = $kodeRayon . str_pad($newSequence, 3, '0', STR_PAD_LEFT);
            } elseif (empty($user->identity_number) && is_null($user->rayon_id)) {
                $user->identity_number = 'GENERIC' . rand(100, 999);
            }
        });
    }
}
