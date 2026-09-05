<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // ROLES
        // =========================
        DB::table('role')->insert([
            ['name' => 'pemohon'],
            ['name' => 'approval'],
        ]);

        // =========================
        // DEPARTMENTS
        // =========================
        DB::table('department')->insert([
            ['name' => 'IT'],
            ['name' => 'GA'],
            // ['name' => 'Finance'],
            ]);

        // =========================
        // POSITIONS / JABATAN
        // =========================
        DB::table('jabatan')->insert([
            ['name' => 'Staff'],
            ['name' => 'Manager'],
            ['name' => 'HRGA'],
        ]);

        // =========================
        // GOLONGAN
        // =========================
        DB::table('golongan')->insert([
            ['name' => 'I'],
            ['name' => 'II'],
            ['name' => 'III'],
            ['name' => 'IV'],
            ]);

        // =========================
        // KOTA KATEGORI
        // =========================
        DB::table('kota_kategori')->insert([
            ['name' => 'A'],
            ['name' => 'B'],
            ]);
            
        // =========================
        // KOTA
        // =========================
        // DB::table('kota')->insert([
        //     [
        //         'kota_kategori_id' => 1,
        //         'name' => 'Pontianak',
        //     ],
        //     [
        //         'kota_kategori_id' => 1,
        //         'name' => 'Pekanbaru',
        //     ],
        //     [
        //         'kota_kategori_id' => 1,
        //         'name' => 'Jayapura',
        //     ],
        //     [
        //         'kota_kategori_id' => 2,
        //         'name' => 'Yogyakarta',
        //     ],
        //     [
        //         'kota_kategori_id' => 2,
        //         'name' => 'Padang',
        //     ],
        //     [
        //         'kota_kategori_id' => 2,
        //         'name' => 'Ambon',
        //     ],
        //     ]);
        // 1. Definisikan daftar kota berdasarkan kategori ID-nya
        $dataKota = [
            1 => [
                'Balikpapan', 'Pontianak', 'Samarinda', 'Palangkaraya', 'Manadao', 'Gorontalo',
                'Medan', 'Pekanbaru', 'Jayapura', 'Jakarta', 'Surabaya', 'NTT/NTB', 'Bandung', 
                'Padang Sidempuan', 'Medan', 'Anyer/Cilegon', 'Batam', 'Denpasar'
            ],
            2 => [
                'Yogyakarta', 'Serang', 'Banda Aceh', 'Palembang', 'Padang', 'Ambon', 'Semarang', 
                'Anambas', 'Cirebon', 'Solo', 'Malang', 'Bandung', 'Cirebon', 'Jambi', 'Bengkulu',
                'Tj.Karang', 'Palu', 'Ambon'
            ],
        ];

        $insertData = [];

        // 2. Format menjadi array yang siap di-insert
        foreach ($dataKota as $kategoriId => $daftarKota) {
            foreach ($daftarKota as $namaKota) {
                $insertData[] = [
                    'kota_kategori_id' => $kategoriId,
                    'name'             => $namaKota,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            }
        }

        // 3. Insert sekaligus dalam 1 query
        DB::table('kota')->insert($insertData);
                
        // =========================
        // KEPERLUAN
        // =========================
        DB::table('keperluan')->insert([
            ['name' => 'Meeting'],
            ['name' => 'Training'],
            ['name' => 'Customer Call'],
            ['name' => 'Intasllation/Maintenance'],
        ]);

        // =========================
        // TRANSPORT
        // =========================
        DB::table('transport')->insert([
            ['name' => 'Pesawat'],
            ['name' => 'Bus'],
            ['name' => 'Ferry'],
            ['name' => 'Kereta'],
            ['name' => 'Kendaraan Dinas'],
        ]);
        
        // =========================
        // TARIF
        // =========================
        // Contoh data dummy.
        // Nanti nominalnya tinggal diganti sesuai data kantor.
        $tarif = [];

        for ($golongan = 1; $golongan <= 4; $golongan++) {
            for ($kategori = 1; $kategori <= 2; $kategori++) {
                $tarif[] = [
                    'golongan_id' => $golongan,
                    'kota_kategori_id' => $kategori,
                    'makan' => 100000,
                    'dinas' => 150000,
                    'hotel' => 300000,
                ];
            }
        }
                
        DB::table('tarif')->insert($tarif);
        
        // =========================
        // USERS
        // =========================
        
        // Staff IT - Pemohon
        User::create([
            'name' => 'Eko Saputra',
            'email' => 'eko@example.com',
            'nik' => '123456789',
            'role_id' => 1,
            'department_id' => 1,
            'jabatan_id' => 1,
            'golongan_id' => 1,
            'password' => Hash::make('password'),
            ]);
            
        // Manager IT - Approval
        User::create([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'nik' => '987654321',
            'role_id' => 2,
            'department_id' => 1,
            'jabatan_id' => 2,
            'golongan_id' => 2,
            'password' => Hash::make('password'),
        ]);
        
        // Staff GA - Pemohon
        User::create([
            'name' => 'Citra',
            'email' => 'citra@example.com',
            'nik' => '456789123',
            'role_id' => 2,
            'department_id' => 2,
            'jabatan_id' => 3,
            'golongan_id' => 2,
            'password' => Hash::make('password'),
            ]);
        
        // Manager GA - Approval
        User::create([
            'name' => 'Andi',
            'email' => 'andi@example.com',
            'nik' => '210987654',
            'role_id' => 1,
            'department_id' => 2,
            'jabatan_id' => 3,
            'golongan_id' => 1,
            'password' => Hash::make('password'),
            ]);    
        }
    }

    // class DatabaseSeeder extends Seeder
    // {
    //     use WithoutModelEvents;
    
    //     /**
    //      * Seed the application's database.
    //      */
    //     public function run(): void
    //     {
    //         // User::factory(10)->create();
    
    //         User::factory()->create([
    //             'name' => 'Test User',
    //             'email' => 'test@example.com',
    //         ]);
    //     }
    // }