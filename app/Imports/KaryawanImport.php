<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Department;
use App\Models\Golongan;
use App\Models\Jabatan;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class KaryawanImport implements ToModel
{
    public function model(array $row): Model|array|null
    {
        // 1. Abaikan baris header (misal baris pertama bertuliskan 'NIK' atau kosong)
        if (empty($row[1]) || strtolower(trim($row[1])) === 'nik') {
            return null;
        }

        // 2. Sinkronisasi Golongan (Index 5)
        // $golongan = null;
        // if (!empty($row[5])) {
        //     // Bersihkan jika ada teks rumus atau string aneh, ambil teks standarnya
        //     $golonganName = trim($row[5]);
        //     $golongan = Golongan::firstOrCreate([
        //         'name' => $golonganName,
        //     ]);
        // }
        $namaGolongan = trim($row[5]); // sesuaikan indeks kolom golongan

        // Abaikan jika kosong, mengandung rumus excel (diawali '='), atau terlalu panjang
        if (empty($namaGolongan) || str_starts_with($namaGolongan, '=') || strlen($namaGolongan) > 10) {
            return null;
        }

        $golongan = Golongan::firstOrCreate([
            'name' => strtoupper($namaGolongan),
        ]);

        // 3. Sinkronisasi Departemen (Index 3)
        $department = null;
        if (!empty($row[3])) {
            $department = Department::firstOrCreate([
                'name' => trim($row[3]),
            ]);
        }

        // 4. Sinkronisasi Jabatan 
        $jabatan = null;
        if (!empty($row[4])) {
            $jabatan = Jabatan::firstOrCreate([
                'name' => trim($row[4]), // <-- Kunci pengecekan duplikat: Cukup namanya saja!
            ], [
                'department_id' => $department ? $department->id : null,
                'golongan_id'   => $golongan ? $golongan->id : null,
            ]);
        }
        // $jabatan = null;
        // if (!empty($row[4])) {
        //     $jabatan = Jabatan::firstOrCreate([
        //         'name' => trim($row[4]),
        //     ], [
        //         'golongan_id' => $golongan ? $golongan->id : null,
        //     ]);
        // }

        // 5. Sinkronisasi User / Karyawan (Index 1 = NIK, Index 2 = Nama, Index 6 = Email)
        User::updateOrCreate([
            'nik' => trim($row[1]),
        ], [
            'name'          => trim($row[2] ?? 'Karyawan Tanpa Nama'),
            'email'         => trim($row[6] ?? $row[1] . '@sppd.com'),
            'password'      => Hash::make('123'), // Password default
            'department_id' => $department ? $department->id : null,
            'jabatan_id'    => $jabatan ? $jabatan->id : null,
            'golongan_id'   => $golongan ? $golongan->id : null,
        ]);

        return null;
    }
}

// namespace App\Imports;

// use App\Models\User;
// use App\Models\Department;
// use App\Models\Golongan;
// use App\Models\Jabatan;
// use Maatwebsite\Excel\Concerns\ToModel;
// // use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\Hash;

// class KaryawanImport implements ToModel
// // class KaryawanImport implements ToModel, WithHeadingRow
// {
//     public function model(array $row): Model|array|null
//     {
//         logger($row);

//         if (empty($row['nik']) || empty($row['nama'])) {
//             return null;
//         }

//         // 1. Golongan (Induk untuk Jabatan)
//         $golongan = null;
//         if (!empty($row['golongan'])) {
//             $golongan = Golongan::firstOrCreate([
//                 'name' => trim($row['golongan']),
//             ]);
//         }

//         // 2. Departemen (Mandiri)
//         $department = null;
//         if (!empty($row['departemen'])) {
//             $department = Department::firstOrCreate([
//                 'name' => trim($row['departemen']),
//             ]);
//         }

//         // 3. Jabatan (Butuh golongan_id)
//         $jabatan = null;
//         if (!empty($row['jabatan'])) {
//             $jabatan = Jabatan::firstOrCreate([
//                 'name' => trim($row['jabatan']),
//             ], [
//                 'golongan_id' => $golongan ? $golongan->id : null,
//             ]);
//         }

//         // 4. User / Karyawan (Butuh department_id, jabatan_id, golongan_id)
//         User::updateOrCreate([
//             'nik' => trim($row['nik']),
//         ], [
//             'name'          => trim($row['nama']),
//             'email'         => trim($row['email'] ?? $row['nik'] . '@sppd.com'),
//             'password'      => Hash::make('password123'),
//             'department_id' => $department ? $department->id : null,
//             'jabatan_id'    => $jabatan ? $jabatan->id : null,
//             'golongan_id'   => $golongan ? $golongan->id : null,
//         ]);

//         return null;
//     }
// }

// namespace App\Imports;

// use App\Models\Karyawan;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithHeadingRow; // Agar membaca baris pertama sebagai header
// use Illuminate\Database\Eloquent\Model;

// class KaryawanImport implements ToModel, WithHeadingRow
// {
//     public function model(array $row)
//     {
//         return new Karyawan([
//             'nik'            => $row['nik'],
//             'nama'           => $row['nama_karyawan'],
//             'departemen'     => $row['departemen'],
//             'jabatan'        => $row['jabatan'],
//             'golongan'       => $row['golongan'],
//             'email'          => $row['email_kantor'],
//             // 'status'         => $row['status_karyawan'],
//             // 'password'       => bcrypt($row['password_default'] ?? 'Password123!'),
//         ]);
//     }
// }