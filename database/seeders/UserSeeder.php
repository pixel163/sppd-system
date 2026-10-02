<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    // public function run()
    // {
    //     // Admin
    //     User::create([
    //         'name' => 'staff',
    //         'email' => 'staff@example.com',
    //         'password' => Hash::make('password'),
    //     ]);

    //     User::create([
    //         'name' => 'manajer',
    //         'email' => 'manajer@example.com',
    //         'password' => Hash::make('password'),
    //     ]);

    //     User::create([
    //         'name' => 'ga',
    //         'email' => 'ga@example.com',
    //         'password' => Hash::make('password'),
    //     ]);
    // }

    public function run(): void
    {
        $users = [
            [
                'name' => 'Eko Saputra',
                'email' => 'eko@example.com',
                'nik' => '123456789',
                'role_id' => 2,
                'department_id' => 1,
                'jabatan_id' => 1,
                'golongan_id' => 1,
                'password' => Hash::make('password'),
                // 'created_by'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name' => 'Budi',
                'email' => 'budi@example.com',
                'nik' => '987654321',
                'role_id' => 3,
                'department_id' => 1,
                'jabatan_id' => 2,
                'golongan_id' => 2,
                'password' => Hash::make('password'),
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name' => 'Citra',
                'email' => 'citra@example.com',
                'nik' => '456789123',
                'role_id' => 1,
                'department_id' => 2,
                'jabatan_id' => 3,
                'golongan_id' => 2,
                'password' => Hash::make('password'),
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name' => 'Andi',
                'email' => 'andi@example.com',
                'nik' => '210987654',
                'role_id' => 2,
                'department_id' => 2,
                'jabatan_id' => 1,
                'golongan_id' => 1,
                'password' => Hash::make('password'),
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name' => 'Suci',
                'email' => 'suci@example.com',
                'nik' => '192837456',
                'role_id' => 3,
                'department_id' => 2,
                'jabatan_id' => 2,
                'golongan_id' => 2,
                'password' => Hash::make('password'),
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        // Gunakan updateOrInsert agar saat re-seed tidak error duplicate entry
        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['name' => $user['name']], // Key acuan pengecekan
                $user                      // Data yang diinsert/update
            );
        }
    }
}