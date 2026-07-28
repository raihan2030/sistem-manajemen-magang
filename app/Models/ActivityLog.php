<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'aktivitas',
        'skpd_nama',
        'status',
        // kolom lainnya sesuai DB kalian
    ];

    /**
     * Accessor otomatis untuk menentukan Warna Badge dari Status DB
     */
    public function getStatusColorAttribute()
    {
        return match (strtoupper($this->status ?? '')) {
            'TERTUNDA', 'PENDING'  => 'yellow',
            'SELESAI', 'APPROVED' => 'green',
            'PENUH', 'GAGAL'      => 'red',
            default               => 'blue',
        };
    }

    /**
     * Accessor otomatis untuk menentukan Tipe Tindakan/Tombol dari Status DB
     */
    public function getActionTypeAttribute()
    {
        return match (strtoupper($this->status ?? '')) {
            'TERTUNDA', 'PENUH'  => 'notifikasi',
            'SELESAI'            => 'detail',
            default              => 'text',
        };
    }
}