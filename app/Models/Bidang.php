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

    /**
     * Hitung sisa kuota/slot secara otomatis & real-time
     * Memperhitungkan jumlah peserta dari pengajuan yang statusnya masih aktif magang.
     */
    public function getSisaKuotaAttribute(): int
    {
        $pesertaAktif = $this->pengajuan()
            ->whereIn('status', ['Diterima', 'Diproses']) 
            ->withCount('anggota')
            ->get()
            ->sum('anggota_count');

        return max(0, $this->kuota_total - $pesertaAktif);
    }
}