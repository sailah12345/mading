<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        if ($search) {
            $articles = Article::with(['user', 'kategori', 'likes'])
                ->where('status', 'published')
                ->where(function($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%')
                          ->orWhere('content', 'like', '%' . $search . '%');
                })
                ->latest()
                ->take(10)
                ->get();
        } else {
            $articles = Article::with(['user', 'kategori', 'likes'])
                ->where('status', 'published')
                ->latest()
                ->take(5)
                ->get();
        }
        
        return view('home', compact('articles', 'search'));
    }
}