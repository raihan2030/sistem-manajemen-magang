<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;

class TwoFactorSetupController extends Controller
{
    public function show(Request $request): View
    {
        return view('auth.2fa-setup');
    }

    public function recoveryCodes(Request $request): View
    {
        return view('auth.2fa-recovery-codes', [
            'recoveryCodes' => $request->user()->recoveryCodes(),
        ]);
    }

    public function regenerateRecoveryCodes(Request $request, GenerateNewRecoveryCodes $generate): RedirectResponse
    {
        $generate($request->user());

        return redirect()->route('2fa.recovery-codes')
            ->with('status', 'Kode pemulihan baru berhasil dibuat. Kode lama sudah tidak berlaku.');
    }
}