<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\Kota;
use App\Models\Unit;
use App\Models\KotaKategori; // Pastikan model ini ada
use Maatwebsite\Excel\Concerns\ToModel;
// Hapus WithHeadingRow karena data kamu dibaca sebagai indeks angka (tanpa header teks)
use Illuminate\Database\Eloquent\Model;

class AreaImport implements ToModel
{
    public function model(array $row): Model|array|null
    {
        // Lewati baris jika kosong atau berisi teks header/judul
        if (empty($row[1]) || empty($row[3]) || strtolower(trim($row[1])) === 'area' || strtolower(trim($row[3])) === 'kota') {
            return null;
        }
        // if (empty($row[1]) || empty($row[3])) {
        //     return null;
        // }

        // 1. Buat / Ambil Data Area (Index 1)
        $area = Area::firstOrCreate([
            'name' => trim(strtoupper($row[1])),
        ]);

        // 2. Buat / Ambil Data Kota Kategori (Index 4, jika ada)
        $kotaKategoriId = null;
        if (!empty($row[4])) {
            $kotaKategori = KotaKategori::firstOrCreate([
                'name' => trim(strtoupper($row[4])),
            ]);
            $kotaKategoriId = $kotaKategori->id;
        }

        // 3. Buat / Ambil Data Kota (Index 3, butuh area_id & kota_kategori_id)
        $kota = Kota::firstOrCreate([
            'name' => trim($row[3]),
        ], [
            'area_id' => $area->id,
            'kota_kategori_id' => $kotaKategoriId,
        ]);

        // 4. Buat / Ambil Data Unit (Index 2, butuh kota_id & area_id)
        if (!empty($row[2])) {
            Unit::firstOrCreate([
                'name' => trim($row[2]),
            ], [
                'kota_id' => $kota->id,
                'area_id' => $area->id,
            ]);
        }

        return null;
    }
}

// namespace App\Imports;

// use App\Models\Area;
// use App\Models\Kota;
// use App\Models\Unit;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Illuminate\Database\Eloquent\Model;

// class AreaImport implements ToModel, WithHeadingRow
// {
//     public function model(array $row): Model|array|null
//     {
//         logger($row);

//         if (empty($row['area']) || empty($row['kota'])) {
//             return null;
//         }

//         // 1. Buat / Ambil Area (Induk utama)
//         $area = Area::firstOrCreate([
//             'name' => trim(strtoupper($row['area'])),
//         ]);

//         // 2. Buat / Ambil Kota (Butuh area_id)
//         $kota = Kota::firstOrCreate([
//             'name' => trim($row['kota']),
//         ], [
//             'area_id' => $area->id,
//             'provinsi' => trim($row['provinsi'] ?? ''),
//         ]);

//         // 3. Buat / Ambil Unit (Jika di excel ada kolom unit, dan butuh kota_id / area_id)
//         if (!empty($row['unit'])) {
//             Unit::firstOrCreate([
//                 'name' => trim($row['unit']),
//             ], [
//                 'kota_id' => $kota->id, // Menyesuaikan kebutuhan tabel unit kamu
//                 'area_id' => $area->id,
//             ]);
//         }

//         return null;
//     }
// }

// use App\Models\Kota;
// use App\Models\Area;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Illuminate\Database\Eloquent\Model;

// class AreaImport implements ToModel, WithHeadingRow
// {
//     // public function model(array $row)
//     public function model(array $row): Model|array|null
//     {
//         if (empty($row['area']) || empty($row['kota'])) {
//             return null;
//         }

//         // 1. Buat / Ambil Data Area
//         $area = Area::firstOrCreate([
//             'name' => trim(strtoupper($row['area'])),
//         ], [
//             'unit' => trim($row['unit'] ?? ''),
//         ]);

//         // 2. Buat / Ambil Data Kota
//         return Kota::firstOrCreate([
//             'name' => trim($row['kota']),
//         ], [
//             'area_id'  => $area->id,
//             'provinsi' => trim($row['provinsi'] ?? ''),
//         ]);
//     }
// }

// namespace App\Imports;

// use App\Models\Area;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;

// class AreaImport implements ToModel, WithHeadingRow
// {
//     public function model(array $row)
//     {
//         return new Area([
//             'area'       => $row['area'],
//             'unit'       => $row['unit'],
//             'provinsi'   => $row['provinsi'],
//             'kota'       => $row['kota'],
//         ]);
//     }
// }

// namespace App\Imports;

// use App\Models\Area;
// use Illuminate\Database\Eloquent\Model;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;

// class AreaImport implements ToModel, WithHeadingRow
// {
//     public function model(array $row): Model|null
//     {
//         return new Area([
//             //
//         ]);
//     }
// }
