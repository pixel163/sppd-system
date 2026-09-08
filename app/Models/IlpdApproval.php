<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IlpdApproval extends Model
{
    protected $table = 'ilpd_approval';

    protected $fillable = [
        'ilpd_id',
        'approver_id',
        'status',
        'signature',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function ilpd(): BelongsTo
    {
        return $this->belongsTo(Ilpd::class);
    }

    public function ilpd_approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}