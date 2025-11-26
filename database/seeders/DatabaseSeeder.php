<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Guru Bahasa',
            'email' => 'guru@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'guru',
        ]);

        User::create([
            'name' => 'Siswa Aktif',
            'email' => 'siswa@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'siswa',
        ]);

        // Buat kategori default
        \App\Models\Kategori::create([
            'nama_kategori' => 'Informasi',
            'deskripsi' => 'Informasi umum sekolah'
        ]);

        \App\Models\Kategori::create([
            'nama_kategori' => 'Prestasi',
            'deskripsi' => 'Prestasi siswa dan sekolah'
        ]);

        \App\Models\Kategori::create([
            'nama_kategori' => 'Kegiatan',
            'deskripsi' => 'Kegiatan sekolah'
        ]);
    }
}