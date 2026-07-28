<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'skpd_id',
        'pengajuan_id',
        'type',
        'judul',
        'pesan',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanMagang::class, 'pengajuan_id');
    }

    public function scopeForSkpd($query, $skpdId)
    {
        return $query->where('skpd_id', $skpdId);
    }

    public function scopeBelumDibaca($query)
    {
        return $query->whereNull('read_at');
    }

    public function tandaiDibaca(): void
    {
        if (! $this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }
}