<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sppd extends Model
{
    protected $table = 'sppd';

    protected $fillable = [
        'dinas_id',
        'no_sppd',
        'user_id',
        'kota_id',
        'keperluan_id',
        'transport_id',
        'durasi',
        'tugas',
        'status',
    ];

    public function Dinas(): BelongsTo
    {
        return $this->belongsTo(Dinas::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kota(): BelongsTo
    {
        return $this->belongsTo(Kota::class);
    }

    public function keperluan(): BelongsTo
    {
        return $this->belongsTo(Keperluan::class);
    }

    public function transport(): BelongsTo
    {
        return $this->belongsTo(Transport::class);
    }

    public function ilpd(): HasOne
    {
        return $this->hasOne(Ilpd::class);
    }

    public function sppd_approval(): HasMany
    {
        return $this->hasMany(SppdApproval::class);
    }
}