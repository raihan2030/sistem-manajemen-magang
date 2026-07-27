<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use App\Models\PengajuanMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $skpd = $user->skpd;

        $filter = $request->query('filter', 'semua');

        // === Query dasar untuk tabel ===
        $query = PengajuanMagang::with(['bidang.skpd', 'anggota', 'perwakilan'])
            ->whereHas('bidang', function ($q) use ($user) {
                $q->where('skpd_id', $user->skpd_id);
            });

        if ($filter === 'mendesak') {
            // SLA <= 6 Jam 59 Menit (belum terlewat)
            $query->whereIn('status', ['Diajukan', 'Diproses'])
                ->where('batas_verifikasi', '>', now())
                ->where('batas_verifikasi', '<=', now()->addHours(6)->addMinutes(59));
        } elseif ($filter === 'terlambat') {
            // SLA Terlewat (batas_verifikasi sudah lewat dari waktu sekarang)
            $query->whereIn('status', ['Diajukan', 'Diproses'])
                ->where('batas_verifikasi', '<', now());
        } elseif ($filter === 'revisi') {
            $query->where('status', 'Revisi');
        } else {
            // Default "Semua"
            $query->whereIn('status', ['Diajukan', 'Diproses', 'Revisi']);
        }

        // Paginate 5 data per halaman (khusus dashboard)
        $permohonans = $query->orderBy('tanggal_pengajuan', 'desc')->paginate(5);

        // === Base query independen dari filter aktif, dipakai untuk semua hitungan ===
        $baseCountQuery = fn () => PengajuanMagang::whereHas('bidang', function ($q) use ($user) {
            $q->where('skpd_id', $user->skpd_id);
        });

        // Count untuk tiap tab
        $countSemua = (clone $baseCountQuery())
            ->whereIn('status', ['Diajukan', 'Diproses', 'Revisi'])
            ->count();

        $countMendesak = (clone $baseCountQuery())
            ->whereIn('status', ['Diajukan', 'Diproses'])
            ->where('batas_verifikasi', '>', now())
            ->where('batas_verifikasi', '<=', now()->addHours(6)->addMinutes(59))
            ->count();

        $countTerlambat = (clone $baseCountQuery())
            ->whereIn('status', ['Diajukan', 'Diproses'])
            ->where('batas_verifikasi', '<', now())
            ->count();

        $countRevisi = (clone $baseCountQuery())
            ->where('status', 'Revisi')
            ->count();

        // === 1. Card "Total Menunggu" ===
        $totalMenunggu = (clone $baseCountQuery())
            ->whereIn('status', ['Diajukan', 'Diproses'])
            ->count();

        $menungguHariIni = (clone $baseCountQuery())
            ->whereIn('status', ['Diajukan', 'Diproses'])
            ->whereDate('tanggal_pengajuan', now()->toDateString())
            ->count();

        // === 2. Card "Batas Waktu Dekat" (sama dengan hitungan tab Mendesak) ===
        $batasDekat = $countMendesak;

        // === 3. Card "Kapasitas Anak Magang" ===
        // Total kuota = akumulasi kuota seluruh bidang milik skpd ini
        // NOTE: sesuaikan nama kolom 'kuota' jika berbeda di tabel bidang
        $kuotaTotal = Bidang::where('skpd_id', $user->skpd_id)->sum('kuota_total');

        // Peserta magang yang statusnya sudah diterima/aktif (mengurangi sisa kuota)
        // NOTE: sesuaikan nama status 'Diterima' jika berbeda di sistemmu
        $magangAktif = (clone $baseCountQuery())
            ->where('status', 'Diterima')
            ->count();

        $sisaKuota = Bidang::where('skpd_id', $user->skpd_id)->sum('sisa_kuota');

        $stats = [
            'total_menunggu' => $totalMenunggu,
            'tren_menunggu'  => $menungguHariIni . ' pengajuan hari ini',
            'batas_dekat'    => $batasDekat,
            'sisa_kuota'     => $sisaKuota,
            'kuota_total'    => $kuotaTotal,
        ];

        return view('pages.admin.dashboard', compact(
            'skpd',
            'permohonans',
            'stats',
            'countSemua',
            'countMendesak',
            'countTerlambat',
            'countRevisi'
        ));
    }
}