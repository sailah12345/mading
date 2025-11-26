<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Models\Like;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $totalArticles = Article::count();
        $publishedArticles = Article::where('status', 'published')->count();
        $pendingArticles = Article::where('status', 'pending')->count();
        $totalUsers = User::count();
        $totalLikes = Like::count();
        $kategoris = Kategori::all();
        
        return view('admin.reports.index', compact(
            'totalArticles', 'publishedArticles', 'pendingArticles', 
            'totalUsers', 'totalLikes', 'kategoris'
        ));
    }

    public function artikelReport(Request $request)
    {
        $month = $request->input('month');
        $kategori = $request->input('kategori');
        $format = $request->input('format', 'pdf');
        
        $query = Article::with(['user', 'kategori', 'likes'])
                       ->where('status', 'published');
        
        if ($month) {
            $query->whereMonth('created_at', $month)
                  ->whereYear('created_at', date('Y'));
        }
        
        if ($kategori) {
            $query->where('id_kategori', $kategori);
        }
        
        $articles = $query->orderBy('created_at', 'desc')->get();
        
        $data = [
            'articles' => $articles,
            'month' => $month ? Carbon::create()->month($month)->format('F') : 'Semua Bulan',
            'kategori' => $kategori ? Kategori::find($kategori)->nama_kategori : 'Semua Kategori',
            'generated_at' => now()->format('d/m/Y H:i:s')
        ];
        
        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.artikel-pdf', $data);
            return $pdf->download('laporan-artikel-' . date('Y-m-d') . '.pdf');
        }
        
        return view('admin.reports.artikel', $data);
    }

    public function aktivitasReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());
        $format = $request->input('format', 'pdf');
        
        $activities = [
            'articles_created' => Article::whereBetween('created_at', [$startDate, $endDate])->count(),
            'articles_published' => Article::where('status', 'published')
                                          ->whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_likes' => Like::whereBetween('created_at', [$startDate, $endDate])->count(),
            'new_users' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'top_articles' => Article::withCount('likes')
                                    ->where('status', 'published')
                                    ->whereBetween('created_at', [$startDate, $endDate])
                                    ->orderBy('likes_count', 'desc')
                                    ->limit(10)
                                    ->get(),
            'articles_by_category' => Article::join('kategori', 'articles.id_kategori', '=', 'kategori.id_kategori')
                                           ->selectRaw('kategori.nama_kategori, COUNT(*) as total')
                                           ->where('articles.status', 'published')
                                           ->whereBetween('articles.created_at', [$startDate, $endDate])
                                           ->groupBy('kategori.nama_kategori')
                                           ->get()
        ];
        
        $data = [
            'activities' => $activities,
            'start_date' => Carbon::parse($startDate)->format('d/m/Y'),
            'end_date' => Carbon::parse($endDate)->format('d/m/Y'),
            'generated_at' => now()->format('d/m/Y H:i:s')
        ];
        
        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.aktivitas-pdf', $data);
            return $pdf->download('laporan-aktivitas-' . date('Y-m-d') . '.pdf');
        }
        
        return view('admin.reports.aktivitas', $data);
    }
}