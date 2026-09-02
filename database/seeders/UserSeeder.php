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
            'name' => 'staff',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'manajer',
            'email' => 'manajer@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'ga',
            'email' => 'ga@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}