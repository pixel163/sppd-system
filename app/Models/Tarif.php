<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tarif extends Model
{
    protected $fillable = [
        'golongan_id',
        'kota_kategori_id',
        // 'travel_scope',
        'makan',
        'dinas',
        'hotel',
    ];

    protected $casts = [
        'makan' => 'decimal:2',
        'dinas' => 'decimal:2',
        'hotel' => 'decimal:2',
    ];

    protected $table = 'tarif';

    public function golongan(): BelongsTo
    {
        return $this->belongsTo(Golongan::class);
    }

    public function kotaKategori(): BelongsTo
    {
        return $this->belongsTo(KotaKategori::class);
    }

    public function detailIlpd(): HasMany
    {
        return $this->hasMany(DetailIlpd::class);
    }
}