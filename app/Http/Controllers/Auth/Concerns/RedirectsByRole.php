<?php

namespace App\Http\Controllers\Auth\Concerns;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

trait RedirectsByRole
{
    /**
     * Redirect user ke dashboard/halaman awal sesuai role_id.
     * Dipakai bersama oleh login manual (AuthenticatedSessionController)
     * dan login via Google (GoogleAuthController) agar perilakunya konsisten.
     */
    protected function redirectByRole(User $user): RedirectResponse
    {
        return match ((int) $user->role_id) {
            1 => redirect()->intended(route('superadmin.dashboard', absolute: false)),
            2 => redirect()->intended(route('admin.dashboard', absolute: false)),
            3 => redirect()->intended(route('peserta.status', absolute: false)),
            default => redirect()->intended('/'),
        };
    }
}