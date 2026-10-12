<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = [
        'kota_id',
        'name',
        // 'kota_kategori_id',
        'created_by',
        'is_active',
    ];

    protected $table = 'Unit';

    public function kota(): BelongsTo
    {
        return $this->belongsTo(Kota::class);
    }

    public function sppd(): HasMany
    {
        return $this->hasMany(Sppd::class);
    }
}