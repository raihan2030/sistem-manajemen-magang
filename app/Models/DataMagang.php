<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataMagang extends Model
{
    protected $table = 'data_magang';

    protected $fillable = [
        'pengajuan_id',
        'status',
        'nama_pembimbing',
        'no_hp_pembimbing',
        'tanggal_selesai_aktual',
        'catatan',
    ];

    protected $casts = [
        'tanggal_selesai_aktual' => 'date',
    ];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanMagang::class, 'pengajuan_id');
    }

    public function getWhatsappPembimbingUrlAttribute(): ?string
    {
        if (!$this->no_hp_pembimbing) {
            return null;
        }

        $nomor = preg_replace('/\D/', '', $this->no_hp_pembimbing);

        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }

        return "https://wa.me/{$nomor}";
    }
}