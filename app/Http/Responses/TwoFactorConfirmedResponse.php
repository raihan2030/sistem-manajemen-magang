<?php

namespace App\Http\Responses;

use App\Http\Controllers\Auth\Concerns\RedirectsByRole;
use Laravel\Fortify\Contracts\TwoFactorConfirmedResponseContract;

class TwoFactorConfirmedResponse implements TwoFactorConfirmedResponseContract
{
    use RedirectsByRole;

    public function toResponse($request)
    {
        return $request->wantsJson()
            ? response()->json(['two_factor_enabled' => true])
            : $this->redirectByRole($request->user(), useIntended: false);
    }
}