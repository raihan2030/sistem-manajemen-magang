<?php

namespace App\Http\Responses;

use App\Http\Controllers\Auth\Concerns\RedirectsByRole;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    use RedirectsByRole;

    public function toResponse($request)
    {
        $user = $request->user();

        $request->session()->put('2fa_passed', true);

        return $this->redirectByRole($user);
    }
}