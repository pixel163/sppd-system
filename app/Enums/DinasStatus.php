<?php

namespace App\Enums;

enum DinasStatus: int
{
    case DRAFT = 0;
    case MENUNGGU_APPROVAL = 1;
    case SEDANG_DIPROSES = 2;
    case DISETUJUI = 3;
    case DITOLAK = 4;
    case LAPORAN = 5;
    case SELESAI = 6;

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::MENUNGGU_APPROVAL => 'Menunggu Approval',
            self::SEDANG_DIPROSES => 'Sedang Diproses',
            self::DISETUJUI => 'Disetujui',
            self::DITOLAK => 'Ditolak',
            self::LAPORAN => 'Menunggu Laporan',
            self::SELESAI => 'Selesai',
        };
    }
}