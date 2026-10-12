<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name'        => 'All',
                'created_by'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            // [
            //     'name'        => 'General Affair',
            //     'created_by'  => 3,
            //     'created_at'  => now(),
            //     'updated_at'  => now(),
            // ],
            // [
            //     'name'        => 'Finance',
            //     'created_by'  => 3,
            //     'created_at'  => now(),
            //     'updated_at'  => now(),
            // ],
        ];

        foreach ($departments as $department) {
            DB::table('department')->updateOrInsert(
                ['name' => $department['name']],
                $department                      
            );
        }
    }
}
