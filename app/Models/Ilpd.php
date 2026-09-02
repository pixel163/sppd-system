<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ilpd extends Model
{
    protected $table = 'ilpd';

    protected $fillable = [
        'dinas_id',
        'sppd_id',
        'no_ilpd',
        'tanggal_awal',
        'tanggal_akhir',
    ];

    protected $casts = [
        'tanggal_awal' => 'date',
        'tanggal_akhir' => 'date',
    ];

    public function Dinas(): BelongsTo
    {
        return $this->belongsTo(Dinas::class);
    }

    public function sppd(): BelongsTo
    {
        return $this->belongsTo(Sppd::class);
    }

    public function detailIlpd(): HasOne
    {
        return $this->hasOne(DetailIlpd::class);
    }

    public function ticket(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function ilpd_approval(): HasMany
    {
        return $this->hasMany(IlpdApproval::class);
    }
}