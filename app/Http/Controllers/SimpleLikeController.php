<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimpleLikeController extends Controller
{
    public function like(Request $request, $articleId)
    {
        try {
            \Log::info('Like request received', ['articleId' => $articleId, 'userId' => Auth::id()]);
            
            // SOAL1: TIPE DATA BOOLEAN - Auth::check() return boolean
            if (!Auth::check()) {
                \Log::warning('Unauthenticated like attempt');
                // SOAL5: HASIL EKSEKUSI - JSON response untuk error
                return response()->json(['success' => false, 'message' => 'Login required'], 401);
            }
            
            // Validasi artikel exists
            $article = Article::find($articleId);
            if (!$article) {
                \Log::error('Article not found', ['articleId' => $articleId]);
                return response()->json(['success' => false, 'message' => 'Article not found'], 404);
            }
            
            // SOAL1: TIPE DATA INTEGER - Auth::id() return integer
            $userId = Auth::id();
            
            // SOAL2: AKSES STRUKTUR DATA - Query dengan where() method
            $existingLike = Like::where('user_id', $userId)
                               ->where('article_id', $articleId)
                               ->first();
            
            // SOAL1: CONTROL PROGRAM - Conditional if/else
            if ($existingLike) {
                $existingLike->delete();
                $liked = false; // SOAL1: TIPE DATA BOOLEAN
                \Log::info('Like removed', ['userId' => $userId, 'articleId' => $articleId]);
            } else {
                // SOAL2: STRUKTUR DATA - Create new record
                Like::create([
                    'user_id' => $userId,
                    'article_id' => $articleId
                ]);
                $liked = true; // SOAL1: TIPE DATA BOOLEAN
                \Log::info('Like added', ['userId' => $userId, 'articleId' => $articleId]);
            }
            
            // SOAL1: TIPE DATA INTEGER - count() return integer
            // SOAL2: AKSES STRUKTUR DATA - Query count
            $likesCount = Like::where('article_id', $articleId)->count();
            
            \Log::info('Like operation completed', ['liked' => $liked, 'likesCount' => $likesCount]);
            
            // SOAL5: HASIL EKSEKUSI - JSON response untuk success
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $likesCount
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Like operation failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            // SOAL5: ERROR HANDLING - Menangani exception
            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}