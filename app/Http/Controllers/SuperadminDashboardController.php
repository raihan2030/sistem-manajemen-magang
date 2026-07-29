<?php

namespace App\Http\Controllers;

use App\Models\PengajuanMagang;
use App\Models\Skpd;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuperadminDashboardController extends Controller
{
    public function index(Request $request): View
    {
        // === Card 1: Total Permohonan (semua status, semua SKPD, sepanjang waktu) ===
        $totalPermohonan = PengajuanMagang::count();

        $totalBulanIni = PengajuanMagang::whereMonth('tanggal_pengajuan', now()->month)
            ->whereYear('tanggal_pengajuan', now()->year)
            ->count();

        $totalBulanLalu = PengajuanMagang::whereMonth('tanggal_pengajuan', now()->subMonthNoOverflow()->month)
            ->whereYear('tanggal_pengajuan', now()->subMonthNoOverflow()->year)
            ->count();

        $trenTotal = $totalBulanLalu > 0
            ? round((($totalBulanIni - $totalBulanLalu) / $totalBulanLalu) * 100)
            : ($totalBulanIni > 0 ? 100 : 0);

        // === Card 2: Permohonan Baru (masuk HARI INI), tren dibanding kemarin ===
        $permohonanBaruHariIni = PengajuanMagang::whereDate('tanggal_pengajuan', now()->toDateString())->count();
        $permohonanBaruKemarin = PengajuanMagang::whereDate('tanggal_pengajuan', now()->subDay()->toDateString())->count();

        $trenBaru = $permohonanBaruKemarin > 0
            ? round((($permohonanBaruHariIni - $permohonanBaruKemarin) / $permohonanBaruKemarin) * 100)
            : ($permohonanBaruHariIni > 0 ? 100 : 0);

        $stats = [
            'total_permohonan'  => number_format($totalPermohonan),
            'tren_total'        => ($trenTotal >= 0 ? '+' : '') . $trenTotal . '% Bulan Ini',
            'permohonan_baru'   => number_format($permohonanBaruHariIni),
            'tren_baru'         => ($trenBaru >= 0 ? '+' : '') . $trenBaru . '% dari Kemarin',
            'skpd_aktif'   => Skpd::count(),
        ];

        // === Tabel Antrean: SEMUA status ditampilkan, termasuk Diterima/Ditolak ===
        $perPage = $request->query('per_page', 10);

        $antreans = PengajuanMagang::with(['bidang.skpd', 'anggota'])
            ->orderBy('tanggal_pengajuan', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('pages.superadmin.dashboard', compact('stats', 'antreans'));
    }
}