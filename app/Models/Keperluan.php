<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keperluan extends Model
{
    protected $fillable = [
        'name',
        'created_by',
        'is_active',
    ];

    protected $table = 'keperluan';

    public function sppd(): HasMany
    {
        return $this->hasMany(Sppd::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
}