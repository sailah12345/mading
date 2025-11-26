<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama_kategori' => 'Informasi Sekolah'],
            ['nama_kategori' => 'Prestasi'],
            ['nama_kategori' => 'Opini'],
            ['nama_kategori' => 'Kegiatan'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}