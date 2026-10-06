<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\SppdStatus;

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
        'keperluan_lainnya',
        'transport_lainnya',
        'durasi',
        'tugas',
        'status',
    ];

    protected $casts = [
        'keperluan_id' => 'array',
        'transport_id' => 'array',
        'status' => SppdStatus::class,
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

    public function approval()
    {
        return $this->hasOne(SppdApproval::class, 'sppd_id')->latestOfMany();
    }

    public function getKeperluanListAttribute()
    {
        $rawIds = $this->keperluan_id;
        $ids = is_string($rawIds) ? (json_decode($rawIds, true) ?? []) : ($rawIds ?? []);
        $ids = is_array($ids) ? $ids : [$ids];

        $names = \App\Models\Keperluan::whereIn('id', $ids)->pluck('name')->toArray();

        if (!empty($this->keperluan_lainnya)) {
            $names[] = trim($this->keperluan_lainnya);
        }

        return !empty($names) ? implode(', ', $names) : '-';
    }

    public function getTransportListAttribute()
    {
        $rawIds = $this->transport_id;
        $ids = is_string($rawIds) ? (json_decode($rawIds, true) ?? []) : ($rawIds ?? []);
        $ids = is_array($ids) ? $ids : [$ids];

        $names = \App\Models\Transport::whereIn('id', $ids)->pluck('name')->toArray();

        if (!empty($this->transport_lainnya)) {
            $names[] = trim($this->transport_lainnya);
        }

        return !empty($names) ? implode(', ', $names) : '-';
    }

    protected $appends = ['keperluan_list', 'transport_list'];
}