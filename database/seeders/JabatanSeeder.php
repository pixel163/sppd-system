<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            [
                'golongan_id'  => '1',
                'name'        => 'HRGA',
                'created_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            // [
            //     'golongan_id'  => '2',
            //     'name'        => 'Manager',
            //     'created_by'  => 3,
            //     'created_at'  => now(),
            //     'updated_at'  => now(),
            // ],
            [
                'golongan_id'  => '1',
                'name'        => 'Staff',
                'created_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        foreach ($jabatans as $jabatan) {
            DB::table('jabatan')->updateOrInsert(
                ['name' => $jabatan['name']],
                $jabatan                      
            );
        }
    }
}
