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
                'name'        => 'Staff',
                'created_by'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Manager',
                'created_by'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'HRGA',
                'created_by'  => 3,
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
