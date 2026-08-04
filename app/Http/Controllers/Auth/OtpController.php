<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\Concerns\RedirectsByRole;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class OtpController extends Controller
{
    use RedirectsByRole;

    public function show(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('login');
        }

        return view('pages.auth.otp-verify');
    }

    public function verify(Request $request, OtpService $otpService): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $userId = $request->session()->get('otp_user_id');

        if (!$userId) {
            return redirect()->route('login')
                ->with('error', 'Sesi login telah berakhir. Silakan login kembali.');
        }

        $throttleKey = 'otp-verify:' . $userId;

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'otp' => "Terlalu banyak percobaan salah. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        $user = User::findOrFail($userId);

        if (!$otpService->verify($user, $request->otp)) {
            RateLimiter::hit($throttleKey, 300);

            return back()->withErrors([
                'otp' => 'Kode OTP salah atau sudah kedaluwarsa.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        // Kalau ini verifikasi pertama kali (dari alur Register), tandai email terverifikasi
        if (is_null($user->email_verified_at)) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        $remember = $request->session()->pull('otp_remember', false);
        $request->session()->forget(['otp_user_id', 'email']);

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    public function resend(Request $request, OtpService $otpService): RedirectResponse
    {
        $userId = $request->session()->get('otp_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $throttleKey = 'otp-resend:' . $userId;

        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->with('error', "Mohon tunggu {$seconds} detik sebelum mengirim ulang kode.");
        }

        RateLimiter::hit($throttleKey, 60);

        $user = User::findOrFail($userId);

        // Tentukan konteks pesan: kalau email belum pernah terverifikasi, ini masih proses register
        $purpose = is_null($user->email_verified_at) ? 'register' : 'login';

        $otpService->generateAndSend($user, $purpose);

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}