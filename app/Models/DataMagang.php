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
}