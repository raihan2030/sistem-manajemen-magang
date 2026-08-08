<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\Concerns\RedirectsByRole;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    use RedirectsByRole;

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request, OtpService $otpService): RedirectResponse
    {
        $request->authenticate();

        $user = User::where('email', $request->email)->firstOrFail();

        // KASUS 1: 2FA Authenticator sudah aktif -> SELALU minta kode, tanpa terkecuali
        if ($user->has2FAEnabled()) {
            $request->session()->put([
                'login.id' => $user->getKey(),
                'login.remember' => $request->boolean('remember'),
            ]);

            return redirect()->route('two-factor.login');
        }

        // KASUS 2: Role wajib 2FA (Admin/Superadmin) tapi belum pernah setup
        if ($user->wajib2FA()) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->route('2fa.setup')
                ->with('warning', 'Akun Anda wajib mengaktifkan Google Authenticator sebelum melanjutkan.');
        }

        // KASUS 3: User biasa, 2FA belum aktif -> alur OTP email TIDAK BERUBAH
        $otpService->generateAndSend($user, 'login');

        $request->session()->put('otp_user_id', $user->id);
        $request->session()->put('otp_remember', $request->boolean('remember'));
        $request->session()->put('email', $user->email);

        return redirect()->route('otp.verify');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        cookie()->queue(cookie()->forget('trusted_device'));

        return redirect('/');
    }
}