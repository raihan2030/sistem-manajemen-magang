<?php

namespace App\Services;

use App\Models\PengajuanMagang;

class PermohonanStatsService
{
    public function hitung(int $skpdId): array
    {
        return [
            'countSemua' => PengajuanMagang::forSkpd($skpdId)
                ->whereIn('status', ['Diajukan', 'Diproses', 'Revisi'])
                ->count(),

            'countMendesak' => PengajuanMagang::forSkpd($skpdId)->mendesak()->count(),

            'countTerlambat' => PengajuanMagang::forSkpd($skpdId)->terlambat()->count(),

            'countRevisi' => PengajuanMagang::forSkpd($skpdId)
                ->where('status', 'Revisi')
                ->count(),
        ];
    }
}
