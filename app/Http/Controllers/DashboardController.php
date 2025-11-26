<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function getAdminData()
    {
        return response()->json([
            'users' => [
                'total' => User::count(),
                'admin' => User::where('role', 'admin')->count(),
                'guru' => User::where('role', 'guru')->count(),
                'siswa' => User::where('role', 'siswa')->count(),
                'recent' => User::latest()->take(5)->get(['id', 'name', 'email', 'role', 'created_at'])
            ],
            'articles' => [
                'total' => Article::count(),
                'published' => Article::where('status', 'published')->count(),
                'pending' => Article::where('status', 'pending')->count(),
                'recent' => Article::with(['user', 'kategori'])->latest()->take(5)->get()
            ],
            'likes' => [
                'total' => Like::count()
            ],
            'comments' => [
                'total' => Comment::count()
            ],
            'categories' => [
                'total' => Kategori::count(),
                'list' => Kategori::withCount('articles')->get()
            ]
        ]);
    }

    public function getGuruData()
    {
        return response()->json([
            'articles' => [
                'total' => Article::count(),
                'published' => Article::where('status', 'published')->count(),
                'pending' => Article::where('status', 'pending')->count(),
                'recent' => Article::with(['user', 'kategori'])->latest()->take(5)->get()
            ],
            'students' => [
                'total' => User::where('role', 'siswa')->count(),
                'list' => User::where('role', 'siswa')->withCount(['articles'])->latest()->get()
            ],
            'likes' => [
                'total' => Like::count()
            ],
            'pending_articles' => Article::where('status', 'pending')->with(['user', 'kategori'])->latest()->get()
        ]);
    }

    public function getSiswaData()
    {
        $userId = Auth::id();
        
        return response()->json([
            'my_articles' => [
                'total' => Article::where('user_id', $userId)->count(),
                'published' => Article::where('user_id', $userId)->where('status', 'published')->count(),
                'pending' => Article::where('user_id', $userId)->where('status', 'pending')->count(),
                'list' => Article::where('user_id', $userId)->with(['kategori'])->latest()->get()
            ],
            'all_articles' => [
                'total' => Article::where('status', 'published')->count(),
                'recent' => Article::where('status', 'published')->with(['user', 'kategori'])->latest()->take(5)->get()
            ],
            'my_likes' => [
                'total' => Like::where('user_id', $userId)->count()
            ],
            'my_comments' => [
                'total' => Comment::where('user_id', $userId)->count()
            ]
        ]);
    }
}