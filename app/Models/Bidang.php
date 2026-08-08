<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bidang extends Model
{
    protected $table = 'bidang';
    public $timestamps = false;
    protected $fillable = ['skpd_id', 'nama_bidang', 'kuota_total', 'sisa_kuota'];

    public function skpd(): BelongsTo
    {
        return $this->belongsTo(Skpd::class, 'skpd_id');
    }

    public function pengajuan(): HasMany
    {
        return $this->hasMany(PengajuanMagang::class, 'bidang_id');
    }

    public function getSisaKuotaAttribute(): int
    {
        $pesertaAktif = $this->pengajuan
            ->filter(function ($item) {
                $statusAktif = in_array($item->status, ['Diterima', 'Diproses']);
                $sudahDibatalkan = $item->dataMagang && $item->dataMagang->status === 'Dibatalkan';

                return $statusAktif && !$sudahDibatalkan;
            })
            ->sum(fn ($item) => $item->anggota->count());

        return max(0, $this->kuota_total - $pesertaAktif);
    }
}