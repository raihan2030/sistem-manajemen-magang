<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'role_id',
        'skpd_id',
        'no_hp',
        'otp_code',
        'otp_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function skpd(): BelongsTo
    {
        return $this->belongsTo(Skpd::class, 'skpd_id');
    }

    public function pengajuanPerwakilan(): HasMany
    {
        return $this->hasMany(PengajuanMagang::class, 'perwakilan_user_id');
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        if (!$this->no_hp) {
            return null;
        }

        $nomor = preg_replace('/\D/', '', $this->no_hp);

        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }

        return "https://wa.me/{$nomor}";
    }

    public function wajib2FA(): bool
    {
        return in_array($this->role_id, [1, 2]);
    }

    public function has2FAEnabled(): bool
    {
        return ! is_null($this->two_factor_confirmed_at);
    }

    public function trustedDevices()
    {
        return $this->hasMany(TrustedDevice::class);
    }
}
