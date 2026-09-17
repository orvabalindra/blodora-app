<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'email' => 'admin@blodora.test',
            'password' => Hash::make('BlodoraTest#2026!'),
            'role' => 'admin',
        ]);

        User::create([
            'username' => 'pendonor',
            'email' => 'pendonor@blodora.test',
            'password' => Hash::make('BlodoraTest#2026!'),
            'role' => 'pendonor',
        ]);
    }
}