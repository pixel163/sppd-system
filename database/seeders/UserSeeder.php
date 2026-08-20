<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Staff',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // CS 1
        User::create([
            'name' => 'manajer',
            'email' => 'manajer@example.com',
            'password' => Hash::make('password'),
            'role' => 'manajer',
        ]);

        User::create([
            'name' => 'ga',
            'email' => 'ga@example.com',
            'password' => Hash::make('password'),
            'role' => 'ga',
        ]);
    }
}