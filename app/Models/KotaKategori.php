<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KotaKategori extends Model
{
    protected $fillable = [
        'name',
    ];

    public function kota(): HasMany
    {
        return $this->hasMany(Kota::class);
    }

    public function tarif(): HasMany
    {
        return $this->hasMany(Tarif::class);
    }
}