<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $articleId)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $article = Article::findOrFail($articleId);

        Comment::create([
            'user_id' => Auth::id(),
            'article_id' => $article->id,
            'content' => $request->content
        ]);

        return redirect()->route('articles.show', $article->id)->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id && Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus komentar ini.');
        }

        $comment->delete();
        return redirect()->back()->with('success', 'Komentar berhasil dihapus!');
    }
}