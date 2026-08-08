<?php

namespace App\Console\Commands;

use App\Models\DataMagang;
use Illuminate\Console\Command;

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
        $dataMagangs = DataMagang::with('pengajuan')
            ->where('status', 'Berlangsung')
            ->whereHas('pengajuan', function ($q) {
                $q->whereDate('tanggal_selesai', '<', now()->toDateString());
            })
            ->get();

        foreach ($dataMagangs as $dataMagang) {
            $dataMagang->update([
                'status' => 'Selesai',
                'tanggal_selesai_aktual' => $dataMagang->pengajuan->tanggal_selesai,
            ]);
        }

        return $dataMagangs->count();
    }
}