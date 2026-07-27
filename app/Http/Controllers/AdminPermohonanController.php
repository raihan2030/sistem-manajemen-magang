<?php

namespace App\Http\Controllers;

use App\Models\DataMagang;
use App\Models\PengajuanMagang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminPermohonanController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $skpd = $user->skpd;

        // Tangkap parameter 'filter' dari URL (Default: 'semua')
        $filter = $request->query('filter', 'semua');

        // Mulai Query Dasar (Hanya untuk SKPD terkait)
        $query = PengajuanMagang::with(['bidang.skpd', 'anggota', 'perwakilan'])
            ->whereHas('bidang', function ($q) use ($user) {
                $q->where('skpd_id', $user->skpd_id);
            });

        // Terapkan Filter Berdasarkan Tombol Tab yang Dipilih
        if ($filter === 'mendesak') {
            // SLA <= 12 Jam (belum terlewat)
            $query->whereIn('status', ['Diajukan', 'Diproses'])
                ->where('batas_verifikasi', '>', now())
                ->where('batas_verifikasi', '<=', now()->addHours(6)->addMinutes(59));
        } elseif ($filter === 'terlambat') {
            // SLA Terlewat (batas_verifikasi sudah lewat dari waktu sekarang)
            $query->whereIn('status', ['Diajukan', 'Diproses'])
                ->where('batas_verifikasi', '<', now());
        } elseif ($filter === 'revisi') {
            // Hanya tampilkan yang berstatus Revisi
            $query->where('status', 'Revisi');
        } else {
            // Default "Semua" (tampilkan Diajukan, Diproses, dan Revisi)
            $query->whereIn('status', ['Diajukan', 'Diproses', 'Revisi']);
        }

        // Jalankan Query & Paginasi
        $permohonans = $query->orderBy('tanggal_pengajuan', 'desc')->paginate(10);

        // === Hitung jumlah data untuk tiap tab, independen dari filter yang aktif ===
        $baseCountQuery = fn() => PengajuanMagang::whereHas('bidang', function ($q) use ($user) {
            $q->where('skpd_id', $user->skpd_id);
        });

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

        return view('pages.admin.permohonan', compact(
            'skpd',
            'permohonans',
            'countSemua',
            'countMendesak',
            'countTerlambat',
            'countRevisi'
        ));
    }

    public function proses($id): RedirectResponse
    {
        $pengajuan = PengajuanMagang::where('id', $id)
            ->whereHas('bidang', function ($q) {
                $q->where('skpd_id', Auth::user()->skpd_id);
            })
            ->firstOrFail();

        $pengajuan->update([
            'status' => 'Diproses',
        ]);

        return back()->with('success', 'Status permohonan berhasil diubah menjadi Diproses!');
    }

    public function show($id): View
    {
        $user = Auth::user();
        $skpd = $user->skpd;

        // Ambil data pengajuan beserta relasinya, pastikan milik SKPD yang login
        $pengajuan = PengajuanMagang::with(['perwakilan', 'anggota', 'bidang.skpd'])
            ->where('id', $id)
            ->whereHas('bidang', function ($q) use ($user) {
                $q->where('skpd_id', $user->skpd_id);
            })
            ->firstOrFail();

        $current_skpd = [
            'kode_skpd' => $skpd->kode_skpd ?? 'SKPD-000',
            'nama_skpd' => $skpd->nama_skpd ?? 'Nama SKPD Tidak Ditemukan',
        ];

        return view('pages.admin.detail_permohonan', compact('pengajuan', 'current_skpd'));
    }

    public function updateStatus(Request $request, $id): RedirectResponse
    {
        // Validasi input
        $request->validate([
            'status' => 'required|in:Diterima,Ditolak,Revisi',
            'komentar_revisi' => 'nullable|string'
        ]);

        $user = Auth::user();

        // Cari permohonan, pastikan milik SKPD admin yang login, dan load relasinya
        $pengajuan = PengajuanMagang::with(['bidang', 'anggota'])
            ->where('id', $id)
            ->whereHas('bidang', function ($q) use ($user) {
                $q->where('skpd_id', $user->skpd_id);
            })
            ->firstOrFail();

        // Simpan status lama untuk mencegah pengurangan kuota berulang (double-deduct)
        $statusSebelumnya = $pengajuan->status;

        // Siapkan data dasar yang akan di-update
        $updateData = [
            'status' => $request->status,
            'komentar_revisi' => $request->komentar_revisi
        ];

        // Logika khusus: Jika status Revisi, tambah batas waktu 24 jam dari sekarang
        if ($request->status === 'Revisi') {
            $updateData['batas_verifikasi'] = now()->addHours(24);
        }

        try {
            // Gunakan Transaction agar jika ada yang gagal, semuanya di-rollback
            \Illuminate\Support\Facades\DB::transaction(function () use ($pengajuan, $updateData, $request, $statusSebelumnya) {
                
                // 1. Update status pengajuan
                $pengajuan->update($updateData);

                // 2. Jika status Diterima (dan sebelumnya belum Diterima)
                if ($request->status === 'Diterima' && $statusSebelumnya !== 'Diterima') {
                    
                    // Buat record DataMagang
                    DataMagang::firstOrCreate(
                        ['pengajuan_id' => $pengajuan->id],
                        ['status' => 'Berlangsung']
                    );

                    // Hitung jumlah anggota tim
                    $jumlahAnggota = $pengajuan->anggota->count();

                    // Kurangi sisa kuota pada bidang terkait
                    $pengajuan->bidang()->decrement('sisa_kuota', $jumlahAnggota);
                }
            });

            // Redirect kembali ke daftar permohonan dengan pesan sukses
            return redirect()
                ->route('admin.permohonan')
                ->with('success', 'Status permohonan berhasil diperbarui menjadi ' . $request->status . '!');

        } catch (\Exception $e) {
            // Tangkap jika terjadi error database
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
