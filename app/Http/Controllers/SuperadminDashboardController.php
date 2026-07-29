<?php

namespace App\Http\Controllers;

use App\Models\PengajuanMagang;
use App\Models\Skpd;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

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
            'skpd_aktif'        => Skpd::count(),
        ];

        // === Data Grafik Tren Pemohon (12 Bulan Terakhir) ===
        $chartDataRaw = PengajuanMagang::select(
                DB::raw('YEAR(tanggal_pengajuan) as year'),
                DB::raw('MONTH(tanggal_pengajuan) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('tanggal_pengajuan', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $chartLabels = [];
        $chartValues = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthNum = (int)$date->format('m');
            $yearNum = (int)$date->format('Y');

            $chartLabels[] = $date->translatedFormat('M Y');

            $found = $chartDataRaw->first(function ($item) use ($monthNum, $yearNum) {
                return $item->month == $monthNum && $item->year == $yearNum;
            });

            $chartValues[] = $found ? $found->total : 0;
        }

        // === Filter & Tabel Antrean Permohonan ===
        $bulanFilter = $request->query('bulan');
        $tahunFilter = $request->query('tahun');
        $perPage     = $request->query('per_page', 10);

        $query = PengajuanMagang::with(['bidang.skpd', 'anggota']);

        if ($bulanFilter) {
            $query->whereMonth('tanggal_pengajuan', $bulanFilter);
        }

        if ($tahunFilter) {
            $query->whereYear('tanggal_pengajuan', $tahunFilter);
        }

        $antreans = $query->orderBy('tanggal_pengajuan', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Opsi Tahun untuk Dropdown Filter (5 Tahun ke belakang dari tahun sekarang)
        $tahunOptions = range(now()->year, now()->year - 4);

        return view('pages.superadmin.dashboard', compact(
            'stats',
            'antreans',
            'chartLabels',
            'chartValues',
            'bulanFilter',
            'tahunFilter',
            'tahunOptions'
        ));
    }
}