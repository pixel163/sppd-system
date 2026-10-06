<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeperluanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keperluans = [
            [
                'name'        => 'Meeting',
                'created_by'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Training',
                'created_by'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Customer Call',
                'created_by'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Installation/Maintenance',
                'created_by'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        foreach ($keperluans as $keperluan) {
            DB::table('keperluan')->updateOrInsert(
                ['name' => $keperluan['name']],
                $keperluan                      
            );
        }
    }
}
