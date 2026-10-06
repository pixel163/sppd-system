<?php

namespace App\Enums;

enum SppdApprovalStatus: int
{
    case DRAFT = 0;
    case MENUNGGU_APPROVAL = 1;
    case DISETUJUI = 2;
    case DITOLAK = 3;
    case SELESAI = 4;

    // Helper untuk menampilkan label yang ramah di UI/Blade
    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::MENUNGGU_APPROVAL => 'Menunggu Approval',
            self::DISETUJUI => 'Disetujui',
            self::DITOLAK => 'Ditolak',
            self::SELESAI => 'Selesai',
        };
    }
}