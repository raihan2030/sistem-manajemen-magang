<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sertifikat extends Model
{
    protected $table = 'sertifikat';
    public $timestamps = false;
    
    protected $fillable = ['anggota_id', 'nomor_sertifikat', 'file_path', 'qr_code_token', 'generated_at'];

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(AnggotaMagang::class, 'anggota_id');
    }
}
