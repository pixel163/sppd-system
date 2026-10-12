<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Import;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MasterImport implements Import, WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            // 2 => new TarifImport(),
            1 => new AreaImport(),
            0 => new KaryawanImport(),
        ];
    }
}


// namespace App\Imports;

// use Maatwebsite\Excel\Concerns\WithMultipleSheets;
// use Maatwebsite\Excel\Concerns\Import;
// use Maatwebsite\Excel\Concerns\ToCollection; // Tambahkan ini jika pakai collection di sub-sheet
// use Illuminate\Support\Collection;

// class MasterImport implements WithMultipleSheets
// {
//     public function sheets(): array
//     {
//         return [
//             // Diproses berurutan: Sheet 2 (Lokasi) dulu, baru Sheet 1 (Karyawan)
//             1 => new AreaImport(),
//             0 => new KaryawanImport(),
//         ];
//     }
// }