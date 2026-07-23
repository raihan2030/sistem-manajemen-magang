<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // Arahkan ke tampilan Blade milik temanmu
        return view('pages.auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();

        // Adjust redirect route name agar sesuai dengan routes/web.php yang baru
        return match ((int) $user->role_id) {
            1 => redirect()->intended(route('superadmin.dashboard', absolute: false)),
            2 => redirect()->intended(route('admin.dashboard', absolute: false)),
            3 => redirect()->intended(route('peserta.status', absolute: false)),
            default => redirect()->intended('/'),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
