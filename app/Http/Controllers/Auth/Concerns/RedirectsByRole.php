<?php

namespace App\Http\Controllers\Auth\Concerns;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

trait RedirectsByRole
{
    protected function redirectByRole(User $user, bool $useIntended = true): RedirectResponse
    {
        $target = match ((int) $user->role_id) {
            1 => route('superadmin.dashboard', absolute: false),
            2 => route('admin.dashboard', absolute: false),
            3 => '/',
            default => '/',
        };

        return $useIntended ? redirect()->intended($target) : redirect()->to($target);
    }
}