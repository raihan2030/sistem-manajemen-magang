<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use App\Models\PengajuanMagang;
use App\Services\PermohonanStatsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Card-card statistik dashboard (khusus di sini, tidak duplikat ke permohonan controller)
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

        return view('pages.admin.dashboard', array_merge(
            compact('skpd', 'permohonans'),
            ['stats' => $cardStats],
            $statsCount
        ));
    }
}
