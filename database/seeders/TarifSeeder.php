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

        $nominalTarif = [
            1 => [
                1 => ['makan' => 150000, 'dinas' => 75000, 'hotel' => 450000], 
                2 => ['makan' => 150000, 'dinas' => 75000, 'hotel' => 300000], 
            ],
            2 => [
                1 => ['makan' => 150000, 'dinas' => 100000, 'hotel' => 500000], 
                2 => ['makan' => 150000, 'dinas' => 100000, 'hotel' => 350000], 
            ],
        ];

        for ($golongan = 1; $golongan <= 2; $golongan++) {
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

                DB::table('tarif')->updateOrInsert(
                    [
                        'golongan_id'      => $golongan,
                        'kota_kategori_id' => $kategori
                    ],
                    $item
                );
            }
        }
    }
}