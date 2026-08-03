<?php

namespace App\Http\Controllers;

use App\Models\DataMagang;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PesertaMagangController extends Controller
{
    /**
     * Helper untuk menghitung status peserta secara dinamis berdasarkan periode magang.
     */
    private function calculateStatus(DataMagang $dataMagang): string
    {
        // Jika dari database sudah eksplisit 'Selesai' (misal dari sertifikat), pertahankan
        if ($dataMagang->status === 'Selesai') {
            return 'Selesai';
        }

        $pengajuan = $dataMagang->pengajuan;
        if (! $pengajuan || ! $pengajuan->tanggal_mulai || ! $pengajuan->tanggal_selesai) {
            return $dataMagang->status ?? 'Terdaftar';
        }

        $today = Carbon::today();
        $start = Carbon::parse($pengajuan->tanggal_mulai)->startOfDay();
        $end   = Carbon::parse($pengajuan->tanggal_selesai)->endOfDay();

        if ($today->gt($end)) {
            return 'Selesai';
        }

        if ($today->gte($start) && $today->lte($end)) {
            return 'Berlangsung';
        }

        return 'Terdaftar';
    }

    public function index(): View
    {
        $user = Auth::user();

        $dataMagangs = DataMagang::with(['pengajuan.anggota', 'pengajuan.bidang'])
            ->whereHas('pengajuan.bidang', function ($q) use ($user) {
                $q->where('skpd_id', $user->skpd_id);
            })
            ->get();

        // Satu baris = satu pengajuan/tim.
        $pesertas = $dataMagangs->map(function ($dataMagang) {
            $pengajuan    = $dataMagang->pengajuan;
            $semuaAnggota = $pengajuan->anggota;
            $ketua        = $semuaAnggota->first();
            $anggotaLain  = $semuaAnggota->skip(1);

            return [
                'pengajuan_id'      => $pengajuan->id,
                'name'              => $ketua->nama_lengkap ?? '-',
                'nim'               => $ketua->nim_nisn ?? '-',
                'institusi_asal'    => $pengajuan->institusi_asal,
                'jurusan_prodi'     => $ketua->jurusan_prodi ?? '-',
                'tanggal_mulai'     => $pengajuan->tanggal_mulai,
                'tanggal_selesai'   => $pengajuan->tanggal_selesai,
                'status'            => $this->calculateStatus($dataMagang), // Status dinamis
                'tipe'              => $semuaAnggota->count() > 1 ? 'Kelompok' : 'Individu',
                'total_anggota'     => $semuaAnggota->count(),
                'nama_anggota_lain' => $anggotaLain->pluck('nama_lengkap')->implode(', '),
            ];
        })->values();

        // Hitung statistik berdasarkan status dinamis
        $stats = [
            'total_peserta' => $pesertas->sum('total_anggota'),
            'berlangsung'   => $pesertas->where('status', 'Berlangsung')->sum('total_anggota'),
            'selesai'       => $pesertas->where('status', 'Selesai')->sum('total_anggota'),
        ];

        return view('pages.admin.peserta', compact('pesertas', 'stats'));
    }

    protected function getPesertaExportData(Request $request)
    {
        $skpdId = Auth::user()->skpd_id;
        $statusFilter = $request->query('status', 'all');

        $query = DataMagang::query()
            ->with(['pengajuan.anggota', 'pengajuan.bidang'])
            ->whereHas('pengajuan.bidang', fn ($q) => $q->where('skpd_id', $skpdId));

        $mappedData = $query->get()->map(function ($dataMagang) {
            $pengajuan   = $dataMagang->pengajuan;
            $anggotaList = $pengajuan->anggota;
            $ketua       = $anggotaList->first();
            $anggotaLain = $anggotaList->skip(1)->pluck('nama_lengkap')->implode(', ');

            return [
                'name'              => $ketua->nama_lengkap ?? '-',
                'tipe'              => $anggotaList->count() > 1 ? 'Kelompok' : 'Individu',
                'nim'               => $ketua->nim_nisn ?? '-',
                'total_anggota'     => $anggotaList->count(),
                'nama_anggota_lain' => $anggotaLain,
                'institusi_asal'    => $pengajuan->institusi_asal ?? '-',
                'jurusan_prodi'     => $ketua->jurusan_prodi ?? '-',
                'tanggal_mulai'     => $pengajuan->tanggal_mulai,
                'tanggal_selesai'   => $pengajuan->tanggal_selesai,
                'status'            => $this->calculateStatus($dataMagang), // Status dinamis
                'pengajuan_id'      => $pengajuan->id,
            ];
        });

        // Filter berdasarkan status hasil perhitungan dinamis
        if ($statusFilter !== 'all') {
            $mappedData = $mappedData->where('status', $statusFilter);
        }

        return $mappedData->values();
    }

    public function exportCsv(Request $request)
    {
        $pesertas = $this->getPesertaExportData($request);
        $filename = 'daftar-peserta-magang-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($pesertas) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nama Peserta', 'Tipe', 'NIM/NISN', 'Anggota Lain', 'Institusi', 'Jurusan', 'Tanggal Mulai', 'Tanggal Selesai', 'Status']);

            foreach ($pesertas as $row) {
                fputcsv($file, [
                    $row['name'],
                    $row['tipe'],
                    $row['nim'],
                    $row['nama_anggota_lain'] ?: '-',
                    $row['institusi_asal'],
                    $row['jurusan_prodi'],
                    $row['tanggal_mulai'] ? Carbon::parse($row['tanggal_mulai'])->translatedFormat('d M Y') : '-',
                    $row['tanggal_selesai'] ? Carbon::parse($row['tanggal_selesai'])->translatedFormat('d M Y') : '-',
                    $row['status'],
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $pesertas = $this->getPesertaExportData($request);
        $status   = $request->query('status', 'all');

        $pdf = Pdf::loadView('pages.admin.exports.peserta_pdf', [
            'pesertas'     => $pesertas,
            'statusFilter' => $status,
            'skpdNama'     => Auth::user()->skpd->nama_skpd ?? '-',
        ])->setPaper('a4', 'landscape');

        return $pdf->download('daftar-peserta-magang-' . now()->format('Y-m-d-His') . '.pdf');
    }
}