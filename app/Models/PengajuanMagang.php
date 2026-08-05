<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class PengajuanMagang extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_magang';
    public $timestamps = false;

    protected $fillable = [
        'perwakilan_user_id',
        'bidang_id',
        'status',
        'komentar_revisi',
        'surat_permohonan',
        'surat_balasan',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_pengajuan',
        'batas_verifikasi',
        'is_warned',
        'jenjang_pendidikan',
        'institusi_asal',
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

    public function dataMagang(): HasOne
    {
        return $this->hasOne(DataMagang::class, 'pengajuan_id');
    }

    public static function hitungBatasVerifikasi(): \Carbon\Carbon
    {
        $now = now();

        if ($now->isFriday() || $now->isSaturday() || $now->isSunday()) {
            return (clone $now)->next(\Carbon\Carbon::TUESDAY)->startOfDay();
        }

        return (clone $now)->addHours(24);
    }

    public function scopeForSkpd($query, $skpdId)
    {
        return $query->whereHas('bidang', fn($q) => $q->where('skpd_id', $skpdId));
    }

    public function scopeMendesak($query)
    {
        return $query->whereIn('status', ['Diajukan', 'Diproses'])
            ->where('batas_verifikasi', '>', now())
            ->where('batas_verifikasi', '<=', now()->addHours(6)->addMinutes(59));
    }

    public function scopeTerlambat($query)
    {
        return $query->whereIn('status', ['Diajukan', 'Diproses'])
            ->where('batas_verifikasi', '<', now());
    }

    public function getSuratPermohonanUrlAttribute(): ?string
    {
        return $this->surat_permohonan
            ? Storage::disk('minio')->url($this->surat_permohonan)
            : null;
    }

    public function getSuratBalasanUrlAttribute(): ?string
    {
        return $this->surat_balasan
            ? Storage::disk('minio')->url($this->surat_balasan)
            : null;
    }
}
