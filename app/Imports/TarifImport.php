<?php

namespace App\Imports;

use App\Models\Tarif;
use App\Models\Golongan;
use App\Models\KotaKategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TarifImport implements ToModel
{
    public function model(array $row): Model|array|null
    {
        // logger($row);

        // logger([
        //     'raw' => $row[4], 
        //     'type' => gettype($row[4])
        // ]);

        // logger([
        //     'dinas_raw' => $row[4],
        //     'dinas_clean' => $cleanNumber($row[4]),
        //     'makan_raw' => $row[3],
        //     'hotel_raw' => $row[5],
        // ]);

        // Lewati baris kosong atau baris header/judul (jika kolom 0 berisi teks judul)
        if (empty($row[1]) || strtolower(trim($row[1])) === 'golongan') {
            return null;
        }

        // 1. Cari / Ambil ID Golongan berdasarkan nama di Kolom 0 (misal index 0)
        $golonganId = null;
        if (!empty($row[1])) {
            $golongan = Golongan::firstOrCreate([
                'name' => trim($row[1])
            ]);
            $golonganId = $golongan->id;
        }

        // 2. Cari / Ambil ID Kota Kategori berdasarkan nama di Kolom 1 (misal index 1)
        $kotaKategoriId = null;
        if (!empty($row[2])) {
            $kotaKategori = KotaKategori::firstOrCreate([
                'name' => trim($row[2])
            ]);
            $kotaKategoriId = $kotaKategori->id;
        }

        // 3. Simpan atau Update data Tarif berdasarkan kombinasi golongan & kategori kota
        // Fungsi helper untuk membersihkan format mata uang dari Excel
        $cleanNumber = function($value) {
            if (empty($value)) return 0;
            // Hapus huruf (seperti 'Rp'), spasi, titik, dan koma
            $clean = preg_replace('/[^\d]/', '', $value);
            return intval($clean);
        };

        logger([
            'dinas_raw' => $row[4],
            'dinas_clean' => $cleanNumber($row[4]),
            'makan_raw' => $row[3],
            'hotel_raw' => $row[5],
        ]);

        $spreadsheetValue = $row[4];

        logger([
            'value' => $spreadsheetValue,
            'type' => gettype($spreadsheetValue),
        ]);

        Tarif::updateOrCreate(
            [
                'golongan_id'      => $golonganId,
                'kota_kategori_id' => $kotaKategoriId,
            ],
            [
                'makan'            => $cleanNumber($row[3]),
                'dinas'            => $cleanNumber($row[4]),
                'hotel'            => $cleanNumber($row[5]),
            ]
        );
        // Tarif::updateOrCreate(
        //     [
        //         'golongan_id'      => $golonganId,
        //         'kota_kategori_id' => $kotaKategoriId,
        //     ],
        //     [
        //         'makan'            => isset($row[3]) ? intval(str_replace(['.', ','], '', $row[3])) : 0, 
        //         'dinas'            => isset($row[4]) ? intval(str_replace(['.', ','], '', $row[4])) : 0, 
        //         'hotel'            => isset($row[5]) ? intval(str_replace(['.', ','], '', $row[5])) : 0,

        //         // 'makan'            => isset($row[3]) ? round(floatval($row[3]), 2) : 0, 
        //         // 'dinas'            => isset($row[4]) ? round(floatval($row[4]), 2) : 0, 
        //         // 'hotel'            => isset($row[5]) ? round(floatval($row[5]), 2) : 0,
        //         // 'created_by'       => Auth::id() ?? 1,
        //     ]
        // );

        return null;
    }
}