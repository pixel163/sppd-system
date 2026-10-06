<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;
use App\Enums\DinasStatus;

class Dinas extends Model
{
    protected $table = 'dinas';

    protected $fillable = [
        'user_id',
        'no_dinas',
        'status',
        'overall_sla',
        'status_sla',
    ];

    protected $casts = [
        'status' => DinasStatus::class,
    ];

    public function sppd(): HasOne
    {
        return $this->hasOne(Sppd::class, 'dinas_id');
    }

    public function ilpd(): HasOne
    {
        return $this->hasOne(Ilpd::class, 'dinas_id');
    }

    public function getSlaInfoAttribute(): array
    {
        if (!$this->overall_sla) {
            return ['status' => 'none', 'label' => '-', 'class' => 'bg-slate-50 text-slate-500 border-slate-200'];
        }

        if ($this->status === 'Disetujui') {
            $isLate = $this->status_sla === 'LATE';
            return [
                'status' => $isLate ? 'late' : 'completed',
                'label'  => $isLate ? 'Selesai (Terlambat)' : 'Selesai (Tepat Waktu)',
                'class'  => $isLate ? 'bg-rose-50 text-rose-600 border-rose-200' : 'bg-emerald-50 text-emerald-600 border-emerald-200'
            ];
        }

        $dueAt = Carbon::parse($this->overall_sla);
        $now = now();

        if ($now->gt($dueAt)) {
            $diff = $now->diffForHumans($dueAt, ['syntax' => Carbon::DIFF_ABSOLUTE, 'parts' => 2]);
            return [
                'status' => 'breached',
                'label'  => 'Terlewat ' . $diff,
                'class'  => 'bg-rose-100 text-rose-700 border-rose-300 animate-pulse'
            ];
        }

        $remainingHours = $now->diffInHours($dueAt, false);
        if ($remainingHours <= 4) {
            return [
                'status' => 'warning',
                'label'  => 'Segera (' . $now->shortAbsoluteDiffForHumans($dueAt) . ')',
                'class'  => 'bg-amber-100 text-amber-700 border-amber-300'
            ];
        }

        return [
            'status' => 'on_time',
            'label'  => 'Sisa ' . $now->shortAbsoluteDiffForHumans($dueAt),
            'class'  => 'bg-sky-50 text-sky-600 border-sky-200'
        ];
    }
}