<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KotaKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kotakategoris = [
            [
                'name'        => 'A',
                'created_by'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'B',
                'created_by'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        // Gunakan updateOrInsert agar saat re-seed tidak error duplicate entry
        foreach ($kotakategoris as $kotakategori) {
            DB::table('kota_kategori')->updateOrInsert(
                ['name' => $kotakategori['name']], // Key acuan pengecekan
                $kotakategori                      // Data yang diinsert/update
            );
        }
    }
}
