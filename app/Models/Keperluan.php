<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Keperluan extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $table = 'keperluan';

    public function sppd(): HasMany
    {
        return $this->hasMany(Sppd::class);
    }
}