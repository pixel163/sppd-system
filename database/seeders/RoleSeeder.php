<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name'        => 'Admin',
                'created_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'User',
                'created_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            // [
            //     'name'        => 'Approver',
            //     'created_by'  => 3,
            //     'created_at'  => now(),
            //     'updated_at'  => now(),
            // ],
        ];

        foreach ($roles as $role) {
            DB::table('role')->updateOrInsert(
                ['name' => $role['name']],
                $role                      
            );
        }
    }
}