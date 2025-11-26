<?php
// Test script untuk memastikan like system berfungsi
// Jalankan dengan: php test-like.php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Article;
use App\Models\Like;
use App\Models\User;

echo "=== TESTING LIKE SYSTEM ===\n\n";

// Test 1: Cek koneksi database
try {
    $articlesCount = Article::count();
    echo "✅ Database connected. Total articles: $articlesCount\n";
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
    exit;
}

// Test 2: Cek model relationships
try {
    $article = Article::with(['likes', 'user'])->first();
    if ($article) {
        echo "✅ Article model working. Title: " . $article->title . "\n";
        echo "✅ Likes relationship: " . $article->likes->count() . " likes\n";
        echo "✅ User relationship: " . $article->user->name . "\n";
    } else {
        echo "⚠️  No articles found in database\n";
    }
} catch (Exception $e) {
    echo "❌ Model error: " . $e->getMessage() . "\n";
}

// Test 3: Cek Like model
try {
    $likesCount = Like::count();
    echo "✅ Like model working. Total likes: $likesCount\n";
} catch (Exception $e) {
    echo "❌ Like model error: " . $e->getMessage() . "\n";
}

// Test 4: Test like creation (jika ada user dan artikel)
$user = User::first();
$article = Article::first();

if ($user && $article) {
    echo "\n=== TESTING LIKE CREATION ===\n";
    
    // Hapus like existing jika ada
    Like::where('user_id', $user->id)->where('article_id', $article->id)->delete();
    
    // Test create like
    $like = Like::create([
        'user_id' => $user->id,
        'article_id' => $article->id
    ]);
    
    if ($like) {
        echo "✅ Like created successfully\n";
        
        // Test count
        $count = Like::where('article_id', $article->id)->count();
        echo "✅ Like count: $count\n";
        
        // Test delete
        $like->delete();
        $countAfter = Like::where('article_id', $article->id)->count();
        echo "✅ Like deleted. Count after: $countAfter\n";
    }
} else {
    echo "⚠️  No users or articles found for testing\n";
}

echo "\n=== TEST COMPLETED ===\n";