<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transport extends Model
{
    protected $fillable = [
        'name',
        'created_by',
        'is_active',
    ];

    protected $table = 'transport';

    public function sppd(): HasMany
    {
        return $this->hasMany(Sppd::class);
    }
}