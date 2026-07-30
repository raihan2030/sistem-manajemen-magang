<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use App\Models\PengajuanMagang;
use App\Services\PermohonanStatsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(Request $request, PermohonanStatsService $stats): View
    {
        $user = Auth::user();
        $skpd = $user->skpd;
        $filter = $request->query('filter', 'semua');

        $query = PengajuanMagang::with(['bidang.skpd', 'anggota', 'perwakilan'])
            ->forSkpd($user->skpd_id);

        if ($filter === 'mendesak') {
            $query->mendesak();
        } elseif ($filter === 'terlambat') {
            $query->terlambat();
        } elseif ($filter === 'revisi') {
            $query->where('status', 'Revisi');
        } else {
            $query->whereIn('status', ['Diajukan', 'Diproses', 'Revisi']);
        }

        $permohonans = $query->orderBy('tanggal_pengajuan', 'desc')->paginate(5);

        $statsCount = $stats->hitung($user->skpd_id);

        // Card-card statistik dashboard
        $totalMenunggu = PengajuanMagang::forSkpd($user->skpd_id)
            ->whereIn('status', ['Diajukan', 'Diproses'])->count();

        $menungguHariIni = PengajuanMagang::forSkpd($user->skpd_id)
            ->whereIn('status', ['Diajukan', 'Diproses'])
            ->whereDate('tanggal_pengajuan', now()->toDateString())
            ->count();

        $kuotaTotal = Bidang::where('skpd_id', $user->skpd_id)->sum('kuota_total');
        $sisaKuota = Bidang::where('skpd_id', $user->skpd_id)->sum('sisa_kuota');

        $cardStats = [
            'total_menunggu' => $totalMenunggu,
            'tren_menunggu'  => $menungguHariIni . ' pengajuan hari ini',
            'batas_dekat'    => $statsCount['countMendesak'],
            'sisa_kuota'     => $sisaKuota,
            'kuota_total'    => $kuotaTotal,
        ];

        // --- QUERY DATA GRAFIK REAL DATABASE ---
        // Mengambil jumlah pendaftar per bulan di tahun berjalan (Jan - Des)
        $bulanNama = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
        
        $pendaftaranPerBulan = PengajuanMagang::forSkpd($user->skpd_id)
            ->whereYear('tanggal_pengajuan', date('Y'))
            ->selectRaw('MONTH(tanggal_pengajuan) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $chartLabels = [];
        $chartData = [];

        // Mapping dari bulan 1 - 12 agar urut dan bulan tanpa pendaftar tetap bernilai 0
        for ($m = 1; $m <= 12; $m++) {
            $chartLabels[] = $bulanNama[$m - 1];
            $chartData[] = $pendaftaranPerBulan[$m] ?? 0;
        }

        return view('pages.admin.dashboard', array_merge(
            compact('skpd', 'permohonans', 'chartLabels', 'chartData'),
            ['stats' => $cardStats],
            $statsCount
        ));
    }
}