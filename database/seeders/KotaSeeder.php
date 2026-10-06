<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataKota = [
            1 => [
                'Balikpapan', 'Pontianak', 'Samarinda', 'Palangkaraya', 'Manado', 'Gorontalo',
                'Medan', 'Pekanbaru', 'Jayapura', 'Jakarta', 'Surabaya', 'NTT/NTB', 'Bandung', 
                'Padang Sidempuan', 'Anyer/Cilegon', 'Batam', 'Denpasar'
            ],
            2 => [
                'Yogyakarta', 'Serang', 'Banda Aceh', 'Palembang', 'Padang', 'Ambon', 'Semarang', 
                'Anambas', 'Cirebon', 'Solo', 'Malang', 'Jambi', 'Bengkulu', 'Tj.Karang', 'Palu'
            ],
        ];

        foreach ($dataKota as $kategoriId => $daftarKota) {
            foreach ($daftarKota as $namaKota) {
                DB::table('kota')->updateOrInsert(
                    ['name' => $namaKota],
                    [
                        'kota_kategori_id' => $kategoriId,
                        'created_by'       => 3,
                        'is_active'        => true,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]
                );
            }
        }
    }
}
