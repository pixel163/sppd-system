<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approval extends Model
{
    protected $table = 'sppd_approval';

    protected $fillable = [
        'sppd_id',
        'approver_id',
        'status',
        'signature',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function sppd(): BelongsTo
    {
        return $this->belongsTo(Sppd::class);
    }

    public function sppd_approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}