<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Dinas extends Model
{
    protected $table = 'dinas';

    protected $fillable = [
        'no_dinas',
        'status',
    ];

    public function sppd(): HasOne
    {
        return $this->hasOne(Sppd::class);
    }

    public function ilpd(): HasOne
    {
        return $this->hasOne(Ilpd::class);
    }
}