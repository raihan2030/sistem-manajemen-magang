<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Skpd extends Model
{
    protected $table = 'skpd';
    public $timestamps = false;
    protected $fillable = ['kode_skpd', 'nama_skpd', 'banner_path', 'aturan_kerja'];

    public function bidang(): HasMany
    {
        return $this->hasMany(Bidang::class, 'skpd_id');
    }

    public function bidangs(): HasMany
    {
        return $this->hasMany(Bidang::class, 'skpd_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'skpd_id');
    }

    public function adminSkpd(): HasOne
    {
        return $this->hasOne(User::class, 'skpd_id')->where('role_id', 2);
    }
}