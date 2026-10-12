<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    protected $fillable = [
        'name',
        'created_by',
        'is_active',
    ];

    protected $table = 'area';

    public function kota(): HasMany
    {
        return $this->hasMany(Kota::class);
    }

    public function sppd(): HasMany
    {
        return $this->hasMany(Sppd::class);
    }
}