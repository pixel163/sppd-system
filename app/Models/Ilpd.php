<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\IlpdStatus;

class Ilpd extends Model
{
    protected $table = 'ilpd';

    protected $fillable = [
        'dinas_id',
        'sppd_id',
        'no_ilpd',
        'status',
        'tanggal_awal',
        'tanggal_akhir',
    ];

    protected $casts = [
        'tanggal_awal' => 'date',
        'tanggal_akhir' => 'date',
        'status' => IlpdStatus::class,
    ];

    public function Dinas(): BelongsTo
    {
        return $this->belongsTo(Dinas::class);
    }

    public function sppd(): BelongsTo
    {
        return $this->belongsTo(Sppd::class);
    }

    public function detail_ilpd(): HasOne
    {
        return $this->hasOne(DetailIlpd::class);
    }

    public function tiket(): HasMany
    {
        return $this->hasMany(Tiket::class);
    }

    public function ilpd_approval(): HasMany
    {
        return $this->hasMany(IlpdApproval::class);
    }

    public function approval()
    {
        return $this->hasOne(IlpdApproval::class, 'ilpd_id')->latestOfMany();
    }

    public function laporan()
    {
        return $this->hasOne(Laporan::class, 'ilpd_id');
    }
}