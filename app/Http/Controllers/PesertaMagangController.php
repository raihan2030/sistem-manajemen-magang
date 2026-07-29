<?php

namespace App\Http\Controllers;

use App\Models\DataMagang;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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

        // Ratakan (flatten) data: satu baris tabel = satu anggota individu,
        // bukan satu pengajuan/tim. Setiap anggota mewarisi status & periode
        // dari data_magang & pengajuan induknya.
        $pesertas = $dataMagangs->flatMap(function ($dataMagang) {
            $pengajuan = $dataMagang->pengajuan;

            return $pengajuan->anggota->map(function ($anggota) use ($dataMagang, $pengajuan) {
                return [
                    // id pengajuan dipakai untuk link ke detail permohonan
                    'pengajuan_id'    => $pengajuan->id,
                    'name'            => $anggota->nama_lengkap,
                    'nim'             => $anggota->nim_nisn,
                    'institusi_asal'  => $pengajuan->institusi_asal,
                    'jurusan_prodi'   => $anggota->jurusan_prodi,
                    'tanggal_mulai'   => $pengajuan->tanggal_mulai,
                    'tanggal_selesai' => $pengajuan->tanggal_selesai,
                    'status'          => $dataMagang->status,
                ];
            });
        })->values();

        $stats = [
            'total_peserta' => $pesertas->count(),
            'berlangsung'   => $pesertas->where('status', 'Berlangsung')->count(),
            'selesai'       => $pesertas->where('status', 'Selesai')->count(),
        ];

        return view('pages.admin.peserta', compact('pesertas', 'stats'));
    }
}