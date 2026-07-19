<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bidang extends Model
{
    protected $table = 'bidang';
    public $timestamps = false;
    protected $fillable = ['skpd_id', 'nama_bidang', 'banner_path', 'kuota_total', 'sisa_kuota'];

    public function skpd(): BelongsTo
    {
        return $this->belongsTo(Skpd::class, 'skpd_id');
    }

    public function pengajuan(): HasMany
    {
        return $this->hasMany(PengajuanMagang::class, 'bidang_id');
    }
}
