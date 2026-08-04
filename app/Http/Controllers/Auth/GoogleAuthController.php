<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\Concerns\RedirectsByRole;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    use RedirectsByRole;

    /**
     * Arahkan user ke halaman consent Google.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Tangani callback dari Google setelah user login/consent.
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            Log::error('Gagal login via Google', ['error' => $e->getMessage()]);

            return redirect()->route('login')
                ->with('error', 'Gagal login menggunakan Google. Silakan coba lagi.');
        }

        // 1. Cek apakah user sudah pernah login via Google sebelumnya
        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            // 2. Cek apakah email sudah terdaftar (daftar manual sebelumnya) -> link akun
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->update(['google_id' => $googleUser->getId()]);
            // SESUDAH
            } else {
                // 3. User benar-benar baru -> daftarkan sebagai role Peserta (role_id 3, sesuai RoleSeeder)
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(24)),
                    'role_id' => 3, // Role "User" / Peserta — lihat RoleSeeder
                    'email_verified_at' => now(),
                ]);
            }
        }

        Auth::login($user, remember: true);

        return $this->redirectByRole($user);
    }
}