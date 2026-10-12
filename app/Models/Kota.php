<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kota extends Model
{
    protected $fillable = [
        'area_id',
        'name',
        'kota_kategori_id',
        'created_by',
        'is_active',
    ];

    protected $table = 'kota';

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function unit(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KotaKategori::class, 'kota_kategori_id');
    }

    public function sppd(): HasMany
    {
        return $this->hasMany(Sppd::class);
    }
}