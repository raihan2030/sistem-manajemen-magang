<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Ensure2FA
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Kalau role tidak wajib 2FA (User biasa), tidak ada yang perlu dicek di sini
        if (! $user->wajib2FA()) {
            return $next($request);
        }

        // Kalau sudah punya 2FA aktif, biarkan lewat
        if ($user->has2FAEnabled()) {
            return $next($request);
        }

        // Wajib 2FA tapi belum pernah setup -> kunci ke halaman setup saja
        if (! $request->routeIs(
            '2fa.setup',
            'two-factor.enable',
            'two-factor.confirm',
            'two-factor.disable',
            'password.confirm',
            'logout'
            )
        ) {
            return redirect()->route('2fa.setup')
                ->with('warning', 'Akun Anda wajib mengaktifkan Google Authenticator sebelum melanjutkan.');
        }

        return $next($request);
    }
}