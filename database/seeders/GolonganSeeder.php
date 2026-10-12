<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GolonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $golongans = [
            [
                'name'        => 'I',
                'created_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'II',
                'created_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'III',
                'created_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'IV',
                'created_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        foreach ($golongans as $golongan) {
            DB::table('golongan')->updateOrInsert(
                ['name' => $golongan['name']], 
                $golongan                      
            );
        }
    }
}