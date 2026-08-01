<?php

namespace App\Http\Controllers;

use App\Models\PengajuanMagang;
use App\Services\NotifikasiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SuperadminAktivitasController extends Controller
{
    public function index(): View
    {
        // Ambil pengajuan yang masih menunggu tindak lanjut (belum Diterima/Ditolak/Revisi)
        $pengajuans = PengajuanMagang::with(['bidang.skpd', 'anggota'])
            ->whereIn('status', ['Diajukan', 'Diproses'])
            ->orderBy('tanggal_pengajuan', 'desc')
            ->take(20)
            ->get();

        // Bentuk jadi struktur $logs yang dipakai blade (menggantikan dummy)
        $logs = $pengajuans->map(function ($pengajuan) {
            $ketua = $pengajuan->anggota->first();
            $isSlaLewat = \Carbon\Carbon::parse($pengajuan->batas_verifikasi)->isPast();

            return (object) [
                'pengajuan_id' => $pengajuan->id,
                'created_at'   => $pengajuan->tanggal_pengajuan,
                'tipe_log'     => $isSlaLewat ? 'warning' : 'info',
                'aktivitas'    => 'Permohonan magang dari <strong>' . ($ketua->nama_lengkap ?? '-')
                    . '</strong> menunggu tindak lanjut (status: ' . $pengajuan->status . ')',
                'skpd_nama'    => $pengajuan->bidang->skpd->nama_skpd ?? '-',
                'status'       => $isSlaLewat ? 'TERLAMBAT' : 'MENUNGGU',
                'status_color' => $isSlaLewat ? 'red' : 'yellow',
                'action'       => 'notifikasi',
            ];
        });

        $stats = [
            'sesuai_jadwal' => PengajuanMagang::whereIn('status', ['Diajukan', 'Diproses'])
                ->where('batas_verifikasi', '>', now())->count(),
            'terlambat' => PengajuanMagang::whereIn('status', ['Diajukan', 'Diproses'])
                ->where('batas_verifikasi', '<', now())->count(),
            // NOTE: konsep "gagal upload" belum ada di sistem saat ini, sementara ditampilkan 0
            'gagal_upload' => 0,
        ];

        // Jumlah SKPD unik yang punya minimal 1 pengajuan terlambat
        $alert_skpd_count = PengajuanMagang::whereIn('status', ['Diajukan', 'Diproses'])
            ->where('batas_verifikasi', '<', now())
            ->with('bidang')
            ->get()
            ->pluck('bidang.skpd_id')
            ->unique()
            ->count();

        return view('pages.superadmin.aktivitas', compact('logs', 'stats', 'alert_skpd_count'));
    }

    /**
     * Kirim notifikasi manual ke admin SKPD terkait sebuah pengajuan.
     */
    public function kirimNotifikasi(int $pengajuanId, NotifikasiService $notifikasiService): RedirectResponse
    {
        $pengajuan = PengajuanMagang::with(['bidang', 'anggota'])->findOrFail($pengajuanId);

        $notifikasiService->kirimNotifikasiManual($pengajuan);

        return back()->with('success', 'Notifikasi berhasil dikirim ke admin SKPD terkait.');
    }
}