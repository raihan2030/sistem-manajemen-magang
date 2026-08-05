<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\PengajuanMagang;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil peserta magang.
     */
    public function index(): View
    {
        $user = Auth::user();

        $pengajuan = PengajuanMagang::with(['bidang.skpd', 'anggota', 'dataMagang'])
            ->where('perwakilan_user_id', $user->id)
            ->latest('tanggal_pengajuan')
            ->first();

        return view('pages.peserta.profil', compact('user', 'pengajuan'));
    }

    /**
     * Update nama pembimbing lapangan via AJAX/Fetch API.
     */
    public function updatePembimbing(Request $request, $id): JsonResponse
    {
        $request->validate([
            'nama_pembimbing' => ['nullable', 'string', 'max:255'],
        ]);

        $pengajuan = PengajuanMagang::where('id', $id)
            ->where('perwakilan_user_id', Auth::id())
            ->firstOrFail();

        $pengajuan->update([
            'nama_pembimbing' => $request->nama_pembimbing,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Nama pembimbing lapangan berhasil diperbarui.',
        ]);
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
