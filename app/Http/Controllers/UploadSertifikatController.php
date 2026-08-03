<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DataMagang;
use App\Models\Sertifikat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UploadSertifikatController extends Controller
{
    /**
     * Tampilkan daftar peserta yang magangnya sudah Selesai & siap diterbitkan sertifikatnya.
     */
    public function index(): View
    {
        $user = Auth::user();

        $dataMagangs = DataMagang::with([
            'pengajuan.anggota.sertifikat',
            'pengajuan.bidang.skpd',
        ])
            ->whereHas('pengajuan.bidang', function ($q) use ($user) {
                $q->where('skpd_id', $user->skpd_id);
            })
            ->orderBy('tanggal_selesai_aktual', 'desc')
            ->get();

        // Bentuk data untuk view (menggantikan dummy $peserta sebelumnya)
        $peserta = $dataMagangs->map(function ($dm) {
            $pengajuan = $dm->pengajuan;
            $semuaAnggota = $pengajuan->anggota; // termasuk ketua (index 0)
            $ketua = $semuaAnggota->first();

            return [
                // id di sini adalah id data_magang, jadi acuan form submit
                'id'            => $dm->id,
                'status'        => $dm->status,
                'name'          => $ketua->nama_lengkap ?? '-',
                'nim'           => $ketua->nim_nisn ?? '-',
                'tipe'          => $semuaAnggota->count() > 1 ? 'Kelompok' : 'Individu',
                'instansi_asal' => ($pengajuan->bidang->skpd->nama_skpd ?? '-') . ' (' . ($pengajuan->bidang->nama_bidang ?? '-') . ')',
                'total_anggota' => $semuaAnggota->count(),
                // Semua anggota (termasuk ketua), masing-masing dengan status sertifikat sendiri
                'anggota'       => $semuaAnggota->map(fn($a) => [
                    'anggota_id'        => $a->id,
                    'name'              => $a->nama_lengkap,
                    'nim'               => $a->nim_nisn,
                    'sudah_terbit'      => $a->sertifikat !== null,
                    'nomor_sertifikat'  => $a->sertifikat->nomor_sertifikat ?? null,
                ])->values(),
                // Semua anggota sudah punya sertifikat?
                'semua_terbit'  => $semuaAnggota->every(fn($a) => $a->sertifikat !== null),
            ];
        });

        return view('pages.admin.upload_sertifikat', compact('peserta'));
    }

    /**
     * Simpan sertifikat untuk masing-masing anggota dalam satu data_magang.
     * Menerima banyak file sekaligus: sertifikat[anggota_id] => file
     */
    public function store(Request $request, DataMagang $dataMagang): RedirectResponse
    {
        abort_unless(
            $dataMagang->pengajuan->bidang->skpd_id === Auth::user()->skpd_id,
            403
        );

        $request->validate([
            'sertifikat'    => ['required', 'array'],
            'sertifikat.*'  => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'catatan'       => ['nullable', 'array'],
            'catatan.*'     => ['nullable', 'string', 'max:500'],
        ]);

        $validAnggotaIds = $dataMagang->pengajuan->anggota->pluck('id')->toArray();

        $anggotaIds = array_intersect(
            array_keys($request->file('sertifikat', [])),
            $validAnggotaIds
        );
        
        $diterbitkan = 0;

        DB::transaction(function () use ($request, $anggotaIds, &$diterbitkan) {
            foreach ($anggotaIds as $anggotaId) {
                $file = $request->file("sertifikat.$anggotaId");
                if (! $file) continue;

                $path = $file->store('sertifikat', 'minio');

                Sertifikat::updateOrCreate(
                    ['anggota_id' => $anggotaId],
                    [
                        'nomor_sertifikat' => $this->generateNomorSertifikat($anggotaId),
                        'file_path'        => $path,
                        'qr_code_token'    => (string) Str::uuid(),
                        'generated_at'     => now(),
                        'catatan'          => $request->input("catatan.$anggotaId"),
                    ]
                );
                $diterbitkan++;
            }
        });

        if ($diterbitkan === 0) {
            return redirect()
                ->route('admin.upload_sertifikat')
                ->with('error', 'Tidak ada file yang diunggah. Pilih minimal satu sertifikat untuk diterbitkan.');
        }

        return redirect()
            ->route('admin.upload_sertifikat')
            ->with('success', "Berhasil menerbitkan {$diterbitkan} sertifikat.");
    }

    /**
     * Generate nomor sertifikat.
     * NOTE: format ini sementara -- sesuaikan dengan format resmi instansi.
     */
    private function generateNomorSertifikat(int $anggotaId): string
    {
        return 'SERT/' . now()->format('Y') . '/' . str_pad($anggotaId, 5, '0', STR_PAD_LEFT);
    }
}
