<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TarifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tarif = [];

        // Map nominal berdasarkan [golongan_id][kota_kategori_id]
        $nominalTarif = [
            1 => [ // Golongan 1
                1 => ['makan' => 150000, 'dinas' => 75000, 'hotel' => 450000], // Kategori 1
                2 => ['makan' => 150000, 'dinas' => 75000, 'hotel' => 300000], // Kategori 2
            ],
            2 => [ // Golongan 2
                1 => ['makan' => 150000, 'dinas' => 100000, 'hotel' => 500000], // Kategori 1
                2 => ['makan' => 150000, 'dinas' => 100000, 'hotel' => 350000], // Kategori 2
            ],
        ];

        for ($golongan = 1; $golongan <= 2; $golongan++) { // Cuma golongan 1 & 2
            for ($kategori = 1; $kategori <= 2; $kategori++) {
                $item = [
                    'golongan_id'      => $golongan,
                    'kota_kategori_id' => $kategori,
                    'makan'            => $nominalTarif[$golongan][$kategori]['makan'],
                    'dinas'            => $nominalTarif[$golongan][$kategori]['dinas'],
                    'hotel'            => $nominalTarif[$golongan][$kategori]['hotel'],
                    'created_by'       => 3,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];

                // Pilihan A: Menggunakan updateOrInsert agar tidak duplicate jika di-seed berulang kali
                DB::table('tarif')->updateOrInsert(
                    [
                        'golongan_id'      => $golongan,
                        'kota_kategori_id' => $kategori
                    ], // Composite key sebagai acuan
                    $item
                );
            }
        }
    }
}