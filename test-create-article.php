<?php
// Test script untuk membuat artikel
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Article;
use App\Models\User;
use App\Models\Kategori;

echo "=== TESTING ARTICLE CREATION ===\n\n";

// Test data
$user = User::first();
$kategori = Kategori::first();

if (!$user) {
    echo "❌ No users found\n";
    exit;
}

if (!$kategori) {
    echo "❌ No categories found\n";
    exit;
}

echo "✅ User found: " . $user->name . " (ID: " . $user->id . ")\n";
echo "✅ Category found: " . $kategori->nama_kategori . " (ID: " . $kategori->id_kategori . ")\n\n";

try {
    $article = Article::create([
        'title' => 'Test Artikel - ' . date('Y-m-d H:i:s'),
        'content' => 'Ini adalah konten test artikel yang dibuat secara otomatis untuk testing.',
        'id_kategori' => $kategori->id_kategori,
        'user_id' => $user->id,
        'photo' => null,
        'status' => 'pending'
    ]);
    
    echo "✅ Article created successfully!\n";
    echo "   ID: " . $article->id . "\n";
    echo "   Title: " . $article->title . "\n";
    echo "   Status: " . $article->status . "\n";
    echo "   Category: " . $article->kategori->nama_kategori . "\n";
    echo "   Author: " . $article->user->name . "\n";
    
} catch (Exception $e) {
    echo "❌ Failed to create article: " . $e->getMessage() . "\n";
    echo "   Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== TEST COMPLETED ===\n";