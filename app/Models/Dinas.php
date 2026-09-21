<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Dinas extends Model
{
    protected $table = 'dinas';

    protected $fillable = [
        'user_id',
        'no_dinas',
        'status',
    ];

    public function sppd(): HasOne
    {
        return $this->hasOne(Sppd::class, 'dinas_id');
    }

    public function ilpd(): HasOne
    {
        return $this->hasOne(Ilpd::class, 'dinas_id');
    }

    public function getActiveSlaAttribute()
    {
        // Jika masih di tahap Form 1
        if ($this->status == 'Pending SPPD') {
            return $this->sppd->approval?->sla_due_at;
        }

        // Jika masuk ke tahap Form 2
        if ($this->status == 'Pending ILPD' || $this->status == 'Menunggu Approval') {
            return $this->ilpd->approval?->sla_due_at;
        }

        return null; // Jika sudah selesai/approved semua
    }

    public function getSlaInfoAttribute(): array
    {
        // 1. Ambil data approval aktif (ILPD atau SPPD)
        $latestApproval = $this->ilpd?->approval ?? $this->sppd?->approval;
        // $latestApproval = $this->ilpd?->ilpd_approval ?? $this->sppd?->sppd_approval;

        if (!$latestApproval || !$latestApproval->sla_due_at) {
            return ['status' => 'none', 'label' => '-', 'class' => 'bg-slate-50 text-slate-500 border-slate-200'];
        }

        // 2. Jika pengajuan sudah selesai/disetujui
        if ($latestApproval->approved_at) {
            $isLate = $latestApproval->approved_at->gt($latestApproval->sla_due_at);
            return [
                'status' => $isLate ? 'late' : 'completed',
                'label'  => $isLate ? 'Selesai (Terlambat)' : 'Selesai (Tepat Waktu)',
                'class'  => $isLate ? 'bg-rose-50 text-rose-600 border-rose-200' : 'bg-emerald-50 text-emerald-600 border-emerald-200'
            ];
        }

        // 3. Jika masih PENDING, hitung sisa waktu dari jam sekarang
        $dueAt = Carbon::parse($latestApproval->sla_due_at);
        $now = now();

        if ($now->gt($dueAt)) {
            // SLA Terlewati / Breached
            $diff = $now->diffForHumans($dueAt, ['syntax' => Carbon::DIFF_ABSOLUTE, 'parts' => 2]);
            return [
                'status' => 'breached',
                'label'  => 'Lewat ' . $diff,
                'class'  => 'bg-rose-100 text-rose-700 border-rose-300 animate-pulse'
            ];
        }

        // Hitung sisa jam
        $remainingHours = $now->diffInHours($dueAt, false);

        if ($remainingHours <= 4) {
            // Warning (Mepet < 4 Jam)
            return [
                'status' => 'warning',
                'label'  => 'Mepet (' . $now->shortAbsoluteDiffForHumans($dueAt) . ')',
                'class'  => 'bg-amber-100 text-amber-700 border-amber-300'
            ];
        }

        // Normal / On Track
        return [
            'status' => 'on_time',
            'label'  => 'Sisa ' . $now->shortAbsoluteDiffForHumans($dueAt),
            'class'  => 'bg-sky-50 text-sky-600 border-sky-200'
        ];
    }
}