<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotifikasiController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $notifikasis = Notifikasi::with('pengajuan.anggota')
            ->forSkpd($user->skpd_id)
            ->orderBy('read_at')
            ->latest()
            ->paginate(10);

        $summary = [
            'urgent_count' => Notifikasi::forSkpd($user->skpd_id)
                ->whereIn('type', ['mendesak', 'terlambat'])
                ->belumDibaca()
                ->count(),

            'belum_dibaca' => Notifikasi::forSkpd($user->skpd_id)->belumDibaca()->count(),

            // Breakdown per tipe untuk kartu "Ringkasan Kotak Masuk"
            'permohonan_baru' => Notifikasi::forSkpd($user->skpd_id)->where('type', 'baru')->belumDibaca()->count(),
            'mendesak'        => Notifikasi::forSkpd($user->skpd_id)->where('type', 'mendesak')->belumDibaca()->count(),
            'terlambat'       => Notifikasi::forSkpd($user->skpd_id)->where('type', 'terlambat')->belumDibaca()->count(),
        ];

        return view('pages.admin.notifikasi', compact('notifikasis', 'summary'));
    }

    public function tandaiDibaca($id): RedirectResponse
    {
        $notifikasi = Notifikasi::forSkpd(Auth::user()->skpd_id)
            ->where('id', $id)
            ->firstOrFail();

        $notifikasi->tandaiDibaca();

        return back();
    }

    public function tandaiSemuaDibaca(): RedirectResponse
    {
        Notifikasi::forSkpd(Auth::user()->skpd_id)
            ->belumDibaca()
            ->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}