<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailIlpd extends Model
{
    protected $table = 'detail_ilpd';

    protected $fillable = [
        'ilpd_id',
        'tarif_id',
        'makan',
        'dinas',
        'hotel',
        'laundry',
        'bbm',
        'transport_lokal',
        'visa',
        'fiskal',
        'airport_tax',
        'parkir&toll',
        'entertaiment',
        'dll',
        'total',
        'uang_muka',
    ];

    protected $casts = [
        'makan' => 'decimal:2',
        'dinas' => 'decimal:2',
        'hotel' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function ilpd(): BelongsTo
    {
        return $this->belongsTo(Ilpd::class);
    }

    public function tarif(): BelongsTo
    {
        return $this->belongsTo(Tarif::class);
    }
}