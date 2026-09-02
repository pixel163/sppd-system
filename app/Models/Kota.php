<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kota extends Model
{
    protected $fillable = [
        'kota_kategori_id',
        'name',
    ];

    protected $table = 'kota';

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KotaKategori::class, 'kota_kategori_id');
    }

    public function sppd(): HasMany
    {
        return $this->hasMany(Sppd::class);
    }
}