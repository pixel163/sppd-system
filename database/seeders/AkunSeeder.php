<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AkunSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'staff',
                'email' => 'staff@gmail.com',
                'nik' => '123456789',
                'role_id' => 2,
                'department_id' => 1,
                'jabatan_id' => 1,
                'golongan_id' => 1,
                'password' => Hash::make('123'),
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name' => 'manager',
                'email' => 'manager@gmail.com',
                'nik' => '987654321',
                'role_id' => 3,
                'department_id' => 1,
                'jabatan_id' => 2,
                'golongan_id' => 2,
                'password' => Hash::make('123'),
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name' => 'hrga',
                'email' => 'hrga@gmail.com',
                'nik' => '456789123',
                'role_id' => 1,
                'department_id' => 2,
                'jabatan_id' => 3,
                'golongan_id' => 2,
                'password' => Hash::make('123'),
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['name' => $user['name']],
                $user                      
            );
        }
    }
}