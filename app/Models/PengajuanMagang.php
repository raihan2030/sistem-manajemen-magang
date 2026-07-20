<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengajuanMagang extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_magang';
    public $timestamps = false;
    
    protected $fillable = [
        'perwakilan_user_id', 'bidang_id', 'status', 'komentar_revisi', 'surat_permohonan',
        'tanggal_mulai', 'tanggal_selesai', 'nama_pembimbing',
        'tanggal_pengajuan', 'batas_verifikasi', 'is_warned'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_pengajuan' => 'datetime',
        'batas_verifikasi' => 'datetime',
    ];

    public function perwakilan()
    {
        return $this->belongsTo(User::class, 'perwakilan_user_id');
    }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

    public function anggota(): HasMany
    {
        return $this->hasMany(AnggotaMagang::class, 'pengajuan_id');
    }
}
