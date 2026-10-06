<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\SppdApprovalStatus;

class SppdApproval extends Model
{
    protected $table = 'sppd_approval';

    protected $fillable = [
        'sppd_id',
        'approver_id',
        'status',
        'signature',
        'sla_due_at',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'status' => SppdApprovalStatus::class,
    ];

    public function sppd(): BelongsTo
    {
        return $this->belongsTo(Sppd::class);
    }

    public function sppd_approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function getSlaStatusAttribute(): string
    {
        if (!$this->sla_due_at) {
            return 'none';
        }

        if ($this->approved_at) {
            return $this->approved_at->gt($this->sla_due_at) ? 'breached' : 'on_time';
        }

        if (now()->gt($this->sla_due_at)) {
            return 'breached';
        }

        if (now()->diffInHours($this->sla_due_at, false) <= 4) {
            return 'warning';
        }

        return 'on_time';
    }
}