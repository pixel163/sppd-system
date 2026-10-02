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
        // 1. Data mapping kota berdasarkan ID kategori
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

        // 2. Loop dan insert/update menggunakan updateOrInsert
        // Menggunakan updateOrInsert mencegah error "Duplicate entry" karena kolom 'name' bertipe UNIQUE
        foreach ($dataKota as $kategoriId => $daftarKota) {
            foreach ($daftarKota as $namaKota) {
                DB::table('kota')->updateOrInsert(
                    ['name' => $namaKota], // Acuan pengecekan (Unique Column)
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
