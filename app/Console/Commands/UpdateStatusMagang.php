<?php

namespace App\Console\Commands;

use App\Models\DataMagang;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateStatusMagang extends Command
{
    protected $signature = 'magang:update-status';

    protected $description = 'Update status pelaksanaan magang secara otomatis: Terdaftar->Berlangsung (saat tanggal_mulai tiba) dan Berlangsung->Selesai (saat tanggal_selesai lewat)';

    public function handle(): int
    {
        $jumlahMulai = $this->updateTerdaftarKeBerlangsung();
        $jumlahSelesai = $this->updateBerlangsungKeSelesai();

        $this->info("Diperbarui -> Terdaftar->Berlangsung: {$jumlahMulai}, Berlangsung->Selesai: {$jumlahSelesai}");

        return self::SUCCESS;
    }

    private function updateTerdaftarKeBerlangsung(): int
    {
        $dataMagangs = DataMagang::with('pengajuan')
            ->where('status', 'Terdaftar')
            ->whereHas('pengajuan', function ($q) {
                $q->whereDate('tanggal_mulai', '<=', now()->toDateString());
            })
            ->get();

        foreach ($dataMagangs as $dataMagang) {
            $dataMagang->update(['status' => 'Berlangsung']);
        }

        return $dataMagangs->count();
    }

    private function updateBerlangsungKeSelesai(): int
    {
        $dataMagangs = DataMagang::with(['pengajuan.anggota', 'pengajuan.bidang'])
            ->where('status', 'Berlangsung')
            ->whereHas('pengajuan', function ($q) {
                $q->whereDate('tanggal_selesai', '<', now()->toDateString());
            })
            ->get();

        foreach ($dataMagangs as $dataMagang) {
            DB::transaction(function () use ($dataMagang) {
                $pengajuan = $dataMagang->pengajuan;
                $jumlahAnggota = $pengajuan->anggota->count();

                $dataMagang->update([
                    'status' => 'Selesai',
                    'tanggal_selesai_aktual' => $pengajuan->tanggal_selesai,
                ]);

                $pengajuan->bidang()->increment('sisa_kuota', $jumlahAnggota);
            });
        }

        return $dataMagangs->count();
    }
}