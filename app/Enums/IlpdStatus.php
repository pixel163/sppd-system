<?php

namespace App\Enums;

enum IlpdStatus: int
{
    case MENUNGGU_APPROVAL = 1;
    case SEDANG_DIPROSES = 2;
    case DISETUJUI = 3;
    case DITOLAK = 4;
    case SELESAI = 5;

    public function label(): string
    {
        return match($this) {
            self::MENUNGGU_APPROVAL => 'Menunggu Approval',
            self::SEDANG_DIPROSES => 'Sedang Diproses',
            self::DISETUJUI => 'Disetujui',
            self::DITOLAK => 'Ditolak',
            self::SELESAI => 'Selesai',
        };
    }
}