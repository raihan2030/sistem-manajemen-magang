<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AnggotaMagang extends Model
{
    use HasFactory;

    protected $table = 'anggota_magang';
    public $timestamps = false;
    
    protected $fillable = ['pengajuan_id', 'nim_nisn', 'nama_lengkap', 'kartu_identitas'];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanMagang::class, 'pengajuan_id');
    }

    public function sertifikat(): HasOne
    {
        return $this->hasOne(Sertifikat::class, 'anggota_id');
    }
}
