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
        $dataTarif = [
            ['golongan' => 'I',   'kategori' => 'A', 'makan' => 150000, 'dinas' => 75000,  'hotel' => 450000],
            ['golongan' => 'I',   'kategori' => 'B', 'makan' => 150000, 'dinas' => 75000,  'hotel' => 300000],
            ['golongan' => 'II',  'kategori' => 'A', 'makan' => 150000, 'dinas' => 100000, 'hotel' => 500000],
            ['golongan' => 'II',  'kategori' => 'B', 'makan' => 150000, 'dinas' => 100000, 'hotel' => 350000],
            ['golongan' => 'III', 'kategori' => 'A', 'makan' => 150000, 'dinas' => 100000, 'hotel' => 600000],
            ['golongan' => 'III', 'kategori' => 'B', 'makan' => 150000, 'dinas' => 100000, 'hotel' => 400000],
            ['golongan' => 'IV',  'kategori' => 'A', 'makan' => 200000, 'dinas' => 100000, 'hotel' => 700000],
            ['golongan' => 'IV',  'kategori' => 'B', 'makan' => 200000, 'dinas' => 100000, 'hotel' => 500000],
        ];

        foreach ($dataTarif as $item) {
            $golongan = \App\Models\Golongan::firstOrCreate([
                'name' => $item['golongan'],
            ]);

            $kategori = \App\Models\KotaKategori::firstOrCreate([
                'name' => $item['kategori'],
            ]);

            DB::table('tarif')->updateOrInsert(
                [
                    'golongan_id' => $golongan->id,
                    'kota_kategori_id' => $kategori->id,
                ],
                [
                    'makan' => $item['makan'],
                    'dinas' => $item['dinas'],
                    'hotel' => $item['hotel'],
                    'created_by' => 3,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    // public function run(): void
    // {
    //     DB::table('tarif')->updateOrInsert(
    //         [
    //             'golongan_id'      => null,
    //             'kota_kategori_id' => null
    //         ],
    //         [
    //             'golongan_id'      => null,
    //             'kota_kategori_id' => null,
    //             'makan'            => 150000,
    //             'dinas'            => 75000,
    //             'hotel'            => 450000,
    //             'created_by'       => 3,
    //             'created_at'       => now(),
    //             'updated_at'       => now(),
    //         ]
    //     );
    // }
}
    // public function run(): void
    // {
    //     $tarif = [];

    //     $nominalTarif = [
    //         1 => [
    //             1 => ['makan' => 150000, 'dinas' => 75000, 'hotel' => 450000], 
    //             2 => ['makan' => 150000, 'dinas' => 75000, 'hotel' => 300000], 
    //         ],
    //         2 => [
    //             1 => ['makan' => 150000, 'dinas' => 100000, 'hotel' => 500000], 
    //             2 => ['makan' => 150000, 'dinas' => 100000, 'hotel' => 350000], 
    //         ],
    //     ];

    //     for ($golongan = 1; $golongan <= 2; $golongan++) {
    //         // for ($kategori = 1; $kategori <= 2; $kategori++) {
    //             $item = [
    //                 'golongan_id'      => $golongan,
    //                 'kota_kategori_id' => null,
    //                 'makan'            => $nominalTarif[$golongan]['makan'],
    //                 'dinas'            => $nominalTarif[$golongan]['dinas'],
    //                 'hotel'            => $nominalTarif[$golongan]['hotel'],
    //                 'created_by'       => 3,
    //                 'created_at'       => now(),
    //                 'updated_at'       => now(),
    //             ];

    //             DB::table('tarif')->updateOrInsert(
    //                 [
    //                     'golongan_id'      => $golongan,
    //                     'kota_kategori_id' => null
    //                 ],
    //                 $item
    //             );
    //         }
    //     }
    // }
// }