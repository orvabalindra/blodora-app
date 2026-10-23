<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedUser(
            'admin',
            'admin@blodora.test',
            'admin',
            config('services.blodora.admin_password'),
            'BLODORA_ADMIN_PASSWORD'
        );

        $this->seedUser(
            'pendonor',
            'pendonor@blodora.test',
            'pendonor',
            config('services.blodora.donor_password'),
            'BLODORA_DONOR_PASSWORD'
        );
    }

    private function seedUser(string $username, string $email, string $role, mixed $password, string $passwordKey): void
    {
        if (User::query()->where('username', $username)->exists()) {
            return;
        }

        if (! is_string($password) || $password === '') {
            throw new RuntimeException("{$passwordKey} wajib diatur sebelum membuat akun {$role}.");
        }

        User::firstOrCreate(
            ['username' => $username],
            [
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $role,
            ]
        );
    }
}
