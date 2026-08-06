<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateStatusPermohonanRequest;
use App\Models\DataMagang;
use App\Models\PengajuanMagang;
use App\Services\PermohonanStatsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $pengajuan = PengajuanMagang::forSkpd(Auth::user()->skpd_id)
            ->where('id', $id)
            ->firstOrFail();

        $statusSebelumnya = $pengajuan->status;

        $updateData = [
            'status' => $request->status,
            'komentar_revisi' => $request->komentar_revisi,
        ];

        if ($request->status === 'Revisi') {
            $updateData['batas_verifikasi'] = PengajuanMagang::hitungBatasVerifikasi();
        }

        if ($request->status === 'Diterima' && $request->hasFile('surat_balasan')) {
            $updateData['surat_balasan'] = $request->file('surat_balasan')->store('surat_balasan', 'minio');
        }

        try {
            DB::transaction(function () use ($pengajuan, $updateData, $request, $statusSebelumnya) {

                $pengajuan->update($updateData);

                if ($request->status === 'Diterima') {
                    $dataMagang = DataMagang::firstOrCreate(
                        ['pengajuan_id' => $pengajuan->id],
                        ['status' => 'Terdaftar']
                    );

                    if ($statusSebelumnya !== 'Diterima') {
                        $jumlahAnggota = $pengajuan->anggota->count();
                        $pengajuan->bidang()->decrement('sisa_kuota', $jumlahAnggota);
                    }

                    if ($request->filled('nama_pembimbing') || $request->filled('no_wa_pembimbing')) {
                        $dataMagang->update([
                            'nama_pembimbing' => $request->nama_pembimbing,
                            'no_hp_pembimbing' => $request->no_wa_pembimbing,
                        ]);
                    }
                }
            });

            // Kalau statusnya TIDAK berubah (edit pembimbing setelah Diterima),
            // tetap di halaman detail yang sama. Kalau ini aksi approve/tolak/revisi
            // baru (status berubah), kembali ke daftar permohonan seperti biasa.
            if ($statusSebelumnya === $request->status) {
                return redirect()
                    ->route('admin.permohonan.detail', $pengajuan->id)
                    ->with('success', 'Data pembimbing lapangan berhasil diperbarui!');
            }

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

    public function batalkan($id)
    {
        $pengajuan = PengajuanMagang::with(['dataMagang', 'anggota', 'bidang'])->findOrFail($id);

        if ($pengajuan->status !== 'Diterima' || !$pengajuan->dataMagang) {
            return redirect()
                ->route('admin.permohonan.detail', $pengajuan->id)
                ->with('warning', 'Permohonan ini tidak dapat dibatalkan.');
        }

        if ($pengajuan->dataMagang->status === 'Dibatalkan') {
            return redirect()
                ->route('admin.permohonan.detail', $pengajuan->id)
                ->with('warning', 'Magang peserta ini sudah dibatalkan sebelumnya.');
        }

        DB::transaction(function () use ($pengajuan) {
            $jumlahAnggota = $pengajuan->anggota->count();

            $pengajuan->dataMagang->update([
                'status' => 'Dibatalkan',
            ]);

            $pengajuan->bidang()->increment('sisa_kuota', $jumlahAnggota);
        });

        return redirect()
            ->route('admin.permohonan.detail', $pengajuan->id)
            ->with('success', 'Magang peserta berhasil dibatalkan dan kuota bidang telah dikembalikan.');
    }

    protected function getPermohonanFilteredQuery(Request $request)
    {
        $skpdId = Auth::user()->skpd_id;
        $filter = $request->query('filter', 'semua');

        $query = PengajuanMagang::with(['anggota', 'bidang', 'perwakilan'])
            ->forSkpd($skpdId)
            ->whereNotIn('status', ['Ditolak', 'Diterima']);

        match ($filter) {
            'mendesak'  => $query->mendesak(),
            'terlambat' => $query->terlambat(),
            'revisi'    => $query->where('status', 'Revisi'),
            default     => null,
        };

        return $query->latest('tanggal_pengajuan');
    }

    /**
     * Hitung teks SLA per baris, sama persis logikanya dengan yang ada di blade.
     */
    protected function hitungSlaText(PengajuanMagang $row): string
    {
        if ($row->status === 'Revisi') {
            return 'Menunggu Revisi';
        }

        $sekarang = \Carbon\Carbon::now('+08:00');
        $batasVerifikasi = \Carbon\Carbon::parse($row->batas_verifikasi)->timezone('+08:00');

        if ($sekarang->greaterThan($batasVerifikasi)) {
            return 'Waktu Habis';
        }

        $selisihJam  = (int) $sekarang->diffInHours($batasVerifikasi);
        $selisihHari = (int) $sekarang->diffInDays($batasVerifikasi);

        return $selisihJam <= 24
            ? $selisihJam . ' Jam Tersisa'
            : $selisihHari . ' Hari Tersisa';
    }

    public function exportCsv(Request $request)
    {
        $permohonans = $this->getPermohonanFilteredQuery($request)->get();
        $filename = 'daftar-permohonan-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($permohonans) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Pemohon', 'Email', 'Institusi Asal', 'Jurusan', 'Bidang', 'Tanggal Pengajuan', 'Status', 'SLA']);

            foreach ($permohonans as $row) {
                $ketua = $row->anggota->first();

                fputcsv($file, [
                    $ketua->nama_lengkap ?? ($row->perwakilan->name ?? 'Pemohon'),
                    $row->perwakilan->email ?? '-',
                    $row->institusi_asal ?? '-',
                    $ketua->jurusan_prodi ?? '-',
                    $row->bidang->nama_bidang ?? '-',
                    \Carbon\Carbon::parse($row->tanggal_pengajuan)->translatedFormat('d M Y H:i'),
                    $row->status,
                    $this->hitungSlaText($row),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $permohonans = $this->getPermohonanFilteredQuery($request)->get();
        $filter = $request->query('filter', 'semua');

        $pdf = Pdf::loadView('pages.admin.exports.permohonan_pdf', [
            'permohonans' => $permohonans,
            'filterLabel' => match ($filter) {
                'mendesak'  => 'Mendesak',
                'terlambat' => 'Terlambat',
                'revisi'    => 'Revisi',
                default     => 'Semua',
            },
            'skpdNama' => Auth::user()->skpd->nama_skpd ?? '-',
            'slaTextResolver' => fn ($row) => $this->hitungSlaText($row),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('daftar-permohonan-' . now()->format('Y-m-d-His') . '.pdf');
    }
}
