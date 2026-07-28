<?php

namespace App\Console\Commands;

use App\Services\NotifikasiService;
use Illuminate\Console\Command;

class CekNotifikasiPermohonan extends Command
{
    protected $signature = 'notifikasi:cek-permohonan';

    protected $description = 'Cek pengajuan yang mendesak/terlambat SLA dan buat notifikasi untuk admin terkait';

    public function handle(NotifikasiService $service): int
    {
        $hasil = $service->cekMendesakDanTerlambat();

        $this->info("Notifikasi baru dibuat -> Mendesak: {$hasil['mendesak']}, Terlambat: {$hasil['terlambat']}");

        return self::SUCCESS;
    }
}
