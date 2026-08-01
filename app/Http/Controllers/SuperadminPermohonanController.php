<?php

namespace App\Http\Controllers;

use App\Models\PengajuanMagang;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class SuperadminPermohonanController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = $request->query('per_page', 10);
        $bulan   = $request->query('bulan');
        $tahun   = $request->query('tahun');

        // Superadmin melihat SEMUA permohonan lintas SKPD, semua status ditampilkan
        $query = PengajuanMagang::with(['bidang.skpd', 'anggota']);

        if ($bulan) {
            $query->whereMonth('tanggal_pengajuan', $bulan);
        }

        if ($tahun) {
            $query->whereYear('tanggal_pengajuan', $tahun);
        }

        $antreans = $query->orderBy('tanggal_pengajuan', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $tahunOptions = PengajuanMagang::selectRaw('YEAR(tanggal_pengajuan) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('pages.superadmin.permohonan', [
            'antreans'     => $antreans,
            'bulanFilter'  => $bulan,
            'tahunFilter'  => $tahun,
            'tahunOptions' => $tahunOptions,
        ]);
    }

    public function show($id): View
    {
        $pengajuan = PengajuanMagang::with(['bidang.skpd', 'anggota'])
            ->where('id', $id)
            ->firstOrFail();

        $current_skpd = [
            'kode_skpd' => $pengajuan->bidang->skpd->kode_skpd ?? 'SKPD-000',
            'nama_skpd' => $pengajuan->bidang->skpd->nama_skpd ?? 'Nama SKPD Tidak Ditemukan',
        ];

        return view('pages.superadmin.detail_permohonan', compact('pengajuan', 'current_skpd'));
    }

    /**
     * Query untuk export, pakai filter bulan/tahun yang sama seperti index(),
     * tanpa paginate karena export butuh semua data yang cocok filter.
     */
    protected function getExportQuery(Request $request)
    {
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');

        $query = PengajuanMagang::with(['bidang.skpd', 'anggota']);

        if ($bulan) {
            $query->whereMonth('tanggal_pengajuan', $bulan);
        }

        if ($tahun) {
            $query->whereYear('tanggal_pengajuan', $tahun);
        }

        return $query->orderBy('tanggal_pengajuan', 'desc')->get();
    }

    /**
     * Hitung status tampilan (termasuk deteksi "Terlambat"), sama persis
     * dengan logic $statusTampilan yang ada di blade.
     */
    protected function statusTampilan(PengajuanMagang $row): string
    {
        $isSlaLewat = in_array($row->status, ['Diajukan', 'Diproses'])
            && \Carbon\Carbon::parse($row->batas_verifikasi)->isPast();

        return $isSlaLewat ? 'Terlambat' : $row->status;
    }

    public function exportCsv(Request $request)
    {
        $antreans = $this->getExportQuery($request);
        $filename = 'permohonan-magang-superadmin-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($antreans) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Nama SKPD', 'Pemohon', 'Tanggal Pengajuan', 'Tenggat Waktu', 'Status']);

            $no = 1;
            foreach ($antreans as $row) {
                $ketua = $row->anggota->first();

                fputcsv($file, [
                    $no++,
                    $row->bidang->skpd->nama_skpd ?? '-',
                    $ketua->nama_lengkap ?? '-',
                    \Carbon\Carbon::parse($row->tanggal_pengajuan)->translatedFormat('d M Y'),
                    \Carbon\Carbon::parse($row->batas_verifikasi)->translatedFormat('d M Y'),
                    $this->statusTampilan($row),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $antreans = $this->getExportQuery($request);
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $pdf = Pdf::loadView('pages.superadmin.exports.permohonan_pdf', [
            'antreans'    => $antreans,
            'bulanLabel'  => $bulan ? ($namaBulan[(int) $bulan] ?? '-') : 'Semua Bulan',
            'tahunLabel'  => $tahun ?: 'Semua Tahun',
            'statusResolver' => fn ($row) => $this->statusTampilan($row),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('permohonan-magang-superadmin-' . now()->format('Y-m-d-His') . '.pdf');
    }
}