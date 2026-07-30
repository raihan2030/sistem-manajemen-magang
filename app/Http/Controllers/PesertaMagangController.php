<?php

namespace App\Http\Controllers;

use App\Models\DataMagang;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PesertaMagangController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $dataMagangs = DataMagang::with(['pengajuan.anggota', 'pengajuan.bidang'])
            ->whereHas('pengajuan.bidang', function ($q) use ($user) {
                $q->where('skpd_id', $user->skpd_id);
            })
            ->get();

        // Satu baris = satu pengajuan/tim. Ketua (anggota pertama) jadi perwakilan tampilan.
        $pesertas = $dataMagangs->map(function ($dataMagang) {
            $pengajuan = $dataMagang->pengajuan;
            $semuaAnggota = $pengajuan->anggota;
            $ketua = $semuaAnggota->first();
            $anggotaLain = $semuaAnggota->skip(1);

            return [
                'pengajuan_id'      => $pengajuan->id,
                'name'              => $ketua->nama_lengkap ?? '-',
                'nim'               => $ketua->nim_nisn ?? '-',
                'institusi_asal'    => $pengajuan->institusi_asal,
                'jurusan_prodi'     => $ketua->jurusan_prodi ?? '-',
                'tanggal_mulai'     => $pengajuan->tanggal_mulai,
                'tanggal_selesai'   => $pengajuan->tanggal_selesai,
                'status'            => $dataMagang->status,
                'tipe'              => $semuaAnggota->count() > 1 ? 'Kelompok' : 'Individu',
                'total_anggota'     => $semuaAnggota->count(),
                'nama_anggota_lain' => $anggotaLain->pluck('nama_lengkap')->implode(', '),
            ];
        })->values();

        $stats = [
            'total_peserta' => $dataMagangs->flatMap(fn ($dm) => $dm->pengajuan->anggota)->count(),
            'berlangsung'   => $dataMagangs->flatMap(fn ($dm) => $dm->status === 'Berlangsung' ? $dm->pengajuan->anggota : collect())->count(),
            'selesai'       => $dataMagangs->flatMap(fn ($dm) => $dm->status === 'Selesai' ? $dm->pengajuan->anggota : collect())->count(),
        ];

        return view('pages.admin.peserta', compact('pesertas', 'stats'));
    }

    protected function getPesertaExportData(Request $request)
    {
        $skpdId = Auth::user()->skpd_id;
        $status = $request->query('status', 'all');

        $query = \App\Models\DataMagang::query()
            ->with(['pengajuan.anggota', 'pengajuan.bidang'])
            ->whereHas('pengajuan.bidang', fn ($q) => $q->where('skpd_id', $skpdId));

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        return $query->get()->map(function ($dataMagang) {
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
                'status'            => $dataMagang->status,
                'pengajuan_id'      => $pengajuan->id,
            ];
        });
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
                    $row['tanggal_mulai'] ? \Carbon\Carbon::parse($row['tanggal_mulai'])->translatedFormat('d M Y') : '-',
                    $row['tanggal_selesai'] ? \Carbon\Carbon::parse($row['tanggal_selesai'])->translatedFormat('d M Y') : '-',
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
            'pesertas' => $pesertas,
            'statusFilter' => $status,
            'skpdNama' => Auth::user()->skpd->nama_skpd ?? '-',
        ])->setPaper('a4', 'landscape');

        return $pdf->download('daftar-peserta-magang-' . now()->format('Y-m-d-His') . '.pdf');
    }
}