<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    protected int $expiryMinutes = 5;

    /**
     * Generate kode OTP baru, simpan (hashed), dan kirim ke email user.
     *
     * @param string $purpose 'login' atau 'register' — untuk menyesuaikan isi pesan email
     */
    public function generateAndSend(User $user, string $purpose = 'login'): void
    {
        $code = (string) random_int(100000, 999999);

        $user->forceFill([
            'otp_code' => Hash::make($code),
            'otp_expires_at' => now()->addMinutes($this->expiryMinutes),
        ])->save();

        $user->notify(new OtpNotification($code, $this->expiryMinutes, $purpose));
    }

    public function verify(User $user, string $code): bool
    {
        if (!$user->otp_code || !$user->otp_expires_at) {
            return false;
        }

        if (now()->greaterThan($user->otp_expires_at)) {
            return false;
        }

        if (!Hash::check($code, $user->otp_code)) {
            return false;
        }

        $this->clear($user);

        return true;
    }

    public function clear(User $user): void
    {
        $user->forceFill([
            'otp_code' => null,
            'otp_expires_at' => null,
        ])->save();
    }
}