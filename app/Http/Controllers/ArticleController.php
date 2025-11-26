<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Kategori;
use App\Models\User;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ArticleController extends Controller
{
    public function index()
    {
        try {
            \Log::info('Articles index accessed');
            $articles = Article::with(['user', 'kategori', 'likes'])->where('status', 'published')->latest()->get();
            \Log::info('Articles loaded', ['count' => $articles->count()]);
            return view('articles.index', compact('articles'));
        } catch (\Exception $e) {
            \Log::error('Articles index error', ['error' => $e->getMessage()]);
            return view('articles.index', ['articles' => collect()]);
        }
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('articles.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Article store request', $request->all());
            
            $request->validate([
                'title' => 'required|max:255',
                'content' => 'required',
                'id_kategori' => 'required|exists:kategori,id_kategori',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('articles', 'public');
                \Log::info('Photo uploaded', ['path' => $photoPath]);
            }

            $article = Article::create([
                'title' => $request->title,
                'content' => $request->content,
                'id_kategori' => $request->id_kategori,
                'user_id' => Auth::id(),
                'photo' => $photoPath,
                'status' => 'pending'
            ]);
            
            \Log::info('Article created', ['id' => $article->id, 'title' => $article->title]);

            // Notifikasi untuk semua user kecuali penulis
            $allUsers = User::where('id', '!=', Auth::id())->get();
            foreach ($allUsers as $user) {
                // Pesan berbeda sesuai konteks user
                if ($user->role == 'admin' || $user->role == 'guru') {
                    $title = 'Artikel Baru Menunggu Persetujuan';
                    $message = 'Artikel "' . $article->title . '" dari ' . Auth::user()->name . ' menunggu persetujuan.';
                } else {
                    $title = 'Artikel Baru';
                    $message = Auth::user()->name . ' membuat artikel baru "' . $article->title . '".';
                }
                
                NotificationController::createNotification(
                    $user->id,
                    $title,
                    $message,
                    'info',
                    $article->id
                );
            }

            return redirect()->route('articles.index')->with('success', 'Artikel berhasil dibuat dan menunggu persetujuan.');
            
        } catch (\Exception $e) {
            \Log::error('Article creation failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Gagal membuat artikel: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            \Log::info('Article show accessed', ['id' => $id]);
            $article = Article::with(['user', 'kategori', 'likes', 'comments.user'])->findOrFail($id);
            \Log::info('Article found', ['title' => $article->title, 'status' => $article->status]);
            return view('articles.show', compact('article'));
        } catch (\Exception $e) {
            \Log::error('Article show error', ['error' => $e->getMessage(), 'article_id' => $id]);
            return redirect()->route('articles.index')->with('error', 'Artikel tidak ditemukan.');
        }
    }

    public function edit(Article $article)
    {
        // Check if user can edit this article
        if (Auth::user()->role == 'siswa' && $article->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit artikel ini.');
        }
        
        $kategoris = Kategori::all();
        return view('articles.edit', compact('article', 'kategoris'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $photoPath = $article->photo;
        if ($request->hasFile('photo')) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('articles', 'public');
        }

        $article->update([
            'title' => $request->title,
            'content' => $request->content,
            'id_kategori' => $request->id_kategori,
            'photo' => $photoPath,
            'status' => $request->status ?? ($article->status == 'published' ? 'published' : 'pending')
        ]);

        if(Auth::user()->role == 'guru') {
            return redirect()->route('dashboard.guru')->with('success', 'Artikel berhasil diperbarui.');
        } elseif(Auth::user()->role == 'siswa') {
            return redirect()->route('dashboard.siswa')->with('success', 'Artikel berhasil diperbarui.');
        }
        return redirect()->route('admin.dashboard')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article)
    {
        // Check if user can delete this article
        if (Auth::user()->role == 'siswa' && $article->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus artikel ini.');
        }
        
        if ($article->photo) {
            Storage::disk('public')->delete($article->photo);
        }
        
        $article->delete();
        
        if(Auth::user()->role == 'siswa') {
            return redirect()->route('dashboard.siswa')->with('success', 'Artikel berhasil dihapus.');
        }
        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dihapus.');
    }

    public function downloadPdf($id)
    {
        $article = Article::with(['user', 'kategori'])->findOrFail($id);
        
        $pdf = Pdf::loadView('articles.pdf', compact('article'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Arial'
            ]);
        
        return $pdf->download('artikel-' . \Str::slug($article->title) . '.pdf');
    }
}