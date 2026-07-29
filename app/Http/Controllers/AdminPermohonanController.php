<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateStatusPermohonanRequest;
use App\Models\DataMagang;
use App\Models\PengajuanMagang;
use App\Services\PermohonanStatsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AdminPermohonanController extends Controller
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

        $permohonans = $query->orderBy('tanggal_pengajuan', 'desc')->paginate(10);

        return view('pages.admin.permohonan', array_merge(
            compact('skpd', 'permohonans'),
            $stats->hitung($user->skpd_id)
        ));
    }

    public function proses($id): RedirectResponse
    {
        $pengajuan = PengajuanMagang::forSkpd(Auth::user()->skpd_id)
            ->where('id', $id)
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

        $pengajuan = PengajuanMagang::forSkpd(Auth::user()->skpd_id)
            ->where('id', $id)
            ->firstOrFail();

        $current_skpd = [
            'kode_skpd' => $skpd->kode_skpd ?? 'SKPD-000',
            'nama_skpd' => $skpd->nama_skpd ?? 'Nama SKPD Tidak Ditemukan',
        ];

        return view('pages.admin.detail_permohonan', compact('pengajuan', 'current_skpd'));
    }

    public function updateStatus(UpdateStatusPermohonanRequest $request, $id): RedirectResponse
    {
        $user = Auth::user();

        $pengajuan = PengajuanMagang::forSkpd(Auth::user()->skpd_id)
            ->where('id', $id)
            ->firstOrFail();

        $statusSebelumnya = $pengajuan->status;

        $updateData = [
            'status' => $request->status,
            'komentar_revisi' => $request->komentar_revisi
        ];

        if ($request->status === 'Revisi') {
            $updateData['batas_verifikasi'] = PengajuanMagang::hitungBatasVerifikasi();
        }

        try {
            DB::transaction(function () use ($pengajuan, $updateData, $request, $statusSebelumnya) {

                $pengajuan->update($updateData);

                if ($request->status === 'Diterima' && $statusSebelumnya !== 'Diterima') {

                    DataMagang::firstOrCreate(
                        ['pengajuan_id' => $pengajuan->id],
                        ['status' => 'Terdaftar']
                    );

                    $jumlahAnggota = $pengajuan->anggota->count();

                    $pengajuan->bidang()->decrement('sisa_kuota', $jumlahAnggota);
                }
            });

            return redirect()
                ->route('admin.permohonan')
                ->with('success', 'Status permohonan berhasil diperbarui menjadi ' . $request->status . '!');
        } catch (\Exception $e) {
            Log::error('Gagal memproses pendaftaran magang', [
                'error'   => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi admin.']);
        }
    }
}
