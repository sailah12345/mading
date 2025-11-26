<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@gmail.com')->first();
        
        if ($admin) {
            $articles = [
                [
                    'title' => 'Selamat Datang di Web Mading Digital',
                    'content' => 'Web mading digital adalah platform untuk berbagi informasi, prestasi, dan kegiatan sekolah.',
                    'status' => 'published',
                    'user_id' => $admin->id,
                    'id_kategori' => 1
                ],
                [
                    'title' => 'Juara Olimpiade Matematika Tingkat Provinsi',
                    'content' => 'Siswa kelas XII berhasil meraih juara 1 olimpiade matematika tingkat provinsi.',
                    'status' => 'published',
                    'user_id' => $admin->id,
                    'id_kategori' => 2
                ],
                [
                    'title' => 'Festival Seni Budaya 2024',
                    'content' => 'Acara tahunan festival seni budaya akan dilaksanakan bulan depan dengan berbagai pertunjukan menarik.',
                    'status' => 'published',
                    'user_id' => $admin->id,
                    'id_kategori' => 4
                ],
                [
                    'title' => 'Pentingnya Literasi Digital di Era Modern',
                    'content' => 'Opini tentang pentingnya literasi digital bagi siswa dalam menghadapi tantangan teknologi.',
                    'status' => 'published',
                    'user_id' => $admin->id,
                    'id_kategori' => 3
                ]
            ];

            foreach ($articles as $article) {
                Article::create($article);
            }
        }
    }
}