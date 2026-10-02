<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transports = [
            [
                'name'        => 'Pesawat',
                'created_by'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Bus',
                'created_by'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Ferry',
                'created_by'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Kereta',
                'created_by'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Kendaraan Dinas',
                'created_by'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        // Gunakan updateOrInsert agar saat re-seed tidak error duplicate entry
        foreach ($transports as $transport) {
            DB::table('transport')->updateOrInsert(
                ['name' => $transport['name']], // Key acuan pengecekan
                $transport                      // Data yang diinsert/update
            );
        }
    }
}
