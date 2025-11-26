<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle($articleId)
    {
        $article = Article::findOrFail($articleId);
        $userId = Auth::id();
        
        $like = Like::where('user_id', $userId)
                   ->where('article_id', $articleId)
                   ->first();
        
        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $userId,
                'article_id' => $articleId
            ]);
        }
        
        return redirect()->back();
    }
}