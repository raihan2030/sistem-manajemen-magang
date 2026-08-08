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

    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            Log::error('Gagal login via Google', ['error' => $e->getMessage()]);

            return redirect()->route('login')
                ->with('error', 'Gagal login menggunakan Google. Silakan coba lagi.');
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->update(['google_id' => $googleUser->getId()]);
            } else {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(24)),
                    'role_id' => 3,
                    'email_verified_at' => now(),
                ]);
            }
        }

        // KASUS 1: 2FA Authenticator sudah aktif -> tetap wajib verifikasi kode
        if ($user->has2FAEnabled()) {
            session([
                'login.id' => $user->getKey(),
                'login.remember' => true,
            ]);

            return redirect()->route('two-factor.login');
        }

        // KASUS 2: Role wajib 2FA (Admin/Superadmin) tapi belum pernah setup
        if ($user->wajib2FA()) {
            Auth::login($user, remember: true);

            return redirect()->route('2fa.setup')
                ->with('warning', 'Akun Anda wajib mengaktifkan Google Authenticator sebelum melanjutkan.');
        }

        // KASUS 3: User biasa via Google, 2FA belum aktif -> langsung login
        Auth::login($user, remember: true);

        return $this->redirectByRole($user);
    }

    public function redirectForConfirm(): RedirectResponse
    {
        return Socialite::driver('google')
            ->redirectUrl(route('auth.google.reconfirm.callback'))
            ->redirect();
    }

    public function callbackForConfirm(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(route('auth.google.reconfirm.callback'))
                ->user();
        } catch (\Exception $e) {
            Log::error('Gagal konfirmasi ulang via Google', ['error' => $e->getMessage()]);

            return redirect()->route('2fa.setup')
                ->with('error', 'Gagal konfirmasi via Google. Silakan coba lagi.');
        }

        $user = Auth::user();

        // Pastikan akun Google yang dipakai persis sama dengan user yang sedang login sekarang
        if (! $user || ! $user->google_id || $user->google_id !== $googleUser->getId()) {
            Log::warning('Percobaan konfirmasi 2FA dengan akun Google yang tidak cocok', [
                'logged_in_user_id' => $user?->id,
                'google_id_returned' => $googleUser->getId(),
            ]);

            return redirect()->route('2fa.setup')
                ->withErrors(['google' => 'Akun Google tidak cocok dengan sesi login Anda saat ini.']);
        }

        session(['auth.password_confirmed_at' => time()]);

        return redirect()->route('2fa.setup')
            ->with('status', 'Identitas berhasil dikonfirmasi via Google.');
    }
}