<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@webmading.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Guru
        User::create([
            'name' => 'Guru Contoh',
            'email' => 'guru@webmading.com',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
            'email_verified_at' => now(),
        ]);

        // Siswa
        User::create([
            'name' => 'Siswa Contoh',
            'email' => 'siswa@webmading.com',
            'password' => Hash::make('siswa123'),
            'role' => 'siswa',
            'email_verified_at' => now(),
        ]);
    }
}