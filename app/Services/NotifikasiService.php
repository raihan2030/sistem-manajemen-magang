<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\PengajuanMagang;

class NotifikasiService
{
    /**
     * Dipanggil saat ada pengajuan baru masuk (dari PengajuanMagangController::store()).
     */
    public function buatNotifikasiPermohonanBaru(PengajuanMagang $pengajuan): void
    {
        $skpdId = $pengajuan->bidang->skpd_id;
        $ketua = $pengajuan->anggota->first();

        Notifikasi::firstOrCreate(
            ['pengajuan_id' => $pengajuan->id, 'type' => 'baru'],
            [
                'skpd_id' => $skpdId,
                'judul'   => 'Permohonan Magang Baru',
                'pesan'   => 'Permohonan magang dari ' . ($ketua->nama_lengkap ?? 'peserta') . ' telah masuk dan menunggu verifikasi.',
            ]
        );
    }

    /**
     * Dipanggil superadmin untuk mengirim notifikasi manual (reminder) ke admin SKPD
     * terkait sebuah pengajuan yang masih menunggu tindak lanjut.
     * Tidak pakai firstOrCreate — sengaja boleh dikirim berkali-kali sebagai reminder.
     */
    public function kirimNotifikasiManual(PengajuanMagang $pengajuan, ?string $pesanKustom = null): Notifikasi
    {
        $ketua = $pengajuan->anggota->first();

        return Notifikasi::create([
            'skpd_id'      => $pengajuan->bidang->skpd_id,
            'pengajuan_id' => $pengajuan->id,
            'type'         => 'manual',
            'judul'        => 'Peringatan dari Superadmin',
            'pesan'        => $pesanKustom
                ?? 'Segera tindak lanjuti permohonan dari ' . ($ketua->nama_lengkap ?? 'peserta')
                    . ' yang masih berstatus "' . $pengajuan->status . '".',
        ]);
    }

    /**
     * Dipanggil secara berkala (scheduled command) untuk mengecek pengajuan
     * yang statusnya berubah jadi Mendesak atau Terlambat karena berjalannya waktu.
     */
    public function cekMendesakDanTerlambat(): array
    {
        $countMendesak = $this->buatNotifikasiMendesak();
        $countTerlambat = $this->buatNotifikasiTerlambat();

        return [
            'mendesak'  => $countMendesak,
            'terlambat' => $countTerlambat,
        ];
    }

    private function buatNotifikasiMendesak(): int
    {
        $pengajuans = PengajuanMagang::with('bidang', 'anggota')->mendesak()->get();
        $count = 0;

        foreach ($pengajuans as $pengajuan) {
            $ketua = $pengajuan->anggota->first();

            $notif = Notifikasi::firstOrCreate(
                ['pengajuan_id' => $pengajuan->id, 'type' => 'mendesak'],
                [
                    'skpd_id' => $pengajuan->bidang->skpd_id,
                    'judul'   => 'Permohonan Mendesak',
                    'pesan'   => 'Permohonan dari ' . ($ketua->nama_lengkap ?? 'peserta') . ' harus segera direspon, SLA tersisa kurang dari 7 jam.',
                ]
            );

            if ($notif->wasRecentlyCreated) {
                $count++;
            }
        }

        return $count;
    }

    private function buatNotifikasiTerlambat(): int
    {
        $pengajuans = PengajuanMagang::with('bidang', 'anggota')->terlambat()->get();
        $count = 0;

        foreach ($pengajuans as $pengajuan) {
            $ketua = $pengajuan->anggota->first();

            $notif = Notifikasi::firstOrCreate(
                ['pengajuan_id' => $pengajuan->id, 'type' => 'terlambat'],
                [
                    'skpd_id' => $pengajuan->bidang->skpd_id,
                    'judul'   => 'Batas Waktu Verifikasi Terlewat',
                    'pesan'   => 'Permohonan dari ' . ($ketua->nama_lengkap ?? 'peserta') . ' sudah melewati batas waktu (SLA) verifikasi.',
                ]
            );

            if ($notif->wasRecentlyCreated) {
                $count++;
            }
        }

        return $count;
    }
}