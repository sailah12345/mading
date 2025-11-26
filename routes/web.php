<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Test route untuk debugging like feature
Route::get('/test-like', function() {
    return view('test-like');
})->name('test.like');

// Test route untuk debugging create article
Route::get('/test-create-article', function() {
    return view('test-create-article');
})->name('test.create.article');

// Test route untuk debugging artikel
Route::get('/test-articles-json', function() {
    $articles = App\Models\Article::with(['user', 'kategori', 'likes'])->where('status', 'published')->latest()->get();
    return response()->json([
        'total_articles' => App\Models\Article::count(),
        'published_articles' => App\Models\Article::where('status', 'published')->count(),
        'articles' => $articles->map(function($article) {
            return [
                'id' => $article->id,
                'title' => $article->title,
                'status' => $article->status,
                'author' => $article->user->name ?? 'Unknown',
                'category' => $article->kategori->nama_kategori ?? 'No Category',
                'likes_count' => $article->likes->count(),
                'created_at' => $article->created_at->format('Y-m-d H:i:s')
            ];
        })
    ]);
});

Route::get('/test-articles-page', function() {
    return view('test-articles');
});

Route::get('/test-notifications', function() {
    return view('test-notification');
})->name('test.notifications');




// Authentication Routes
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::get('/refresh-csrf', function() {
    return response()->json(['token' => csrf_token()]);
});

Route::middleware('auth')->group(function () {
    Route::get('/articles/create', [App\Http\Controllers\ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [App\Http\Controllers\ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [App\Http\Controllers\ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [App\Http\Controllers\ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [App\Http\Controllers\ArticleController::class, 'destroy'])->name('articles.destroy');
    
    // Like routes
    Route::post('/like/{articleId}', [App\Http\Controllers\SimpleLikeController::class, 'like'])->name('simple.like')->where('articleId', '[0-9]+');
    
    // Comment routes
    Route::post('/articles/{articleId}/comments', [App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Notification routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::get('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/api/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/api/notifications/recent', [App\Http\Controllers\NotificationController::class, 'getRecent'])->name('notifications.recent');
});

// Article Routes
Route::get('/articles', [App\Http\Controllers\ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/search', [App\Http\Controllers\ArticleController::class, 'search'])->name('articles.search');
Route::get('/articles/kategori/{id}', function($id) {
    $kategori = App\Models\Kategori::findOrFail($id);
    $articles = App\Models\Article::with(['user', 'kategori'])->where('id_kategori', $id)->where('status', 'published')->latest()->get();
    return view('articles.kategori', compact('articles', 'kategori'));
})->name('articles.kategori');
Route::get('/articles/{article}', [App\Http\Controllers\ArticleController::class, 'show'])->name('articles.show')->where('article', '[0-9]+');
Route::get('/articles/{id}/download', [App\Http\Controllers\ArticleController::class, 'downloadPdf'])->name('articles.download')->where('id', '[0-9]+');

Route::middleware('auth')->group(function () {
    // Dashboard routes
    Route::get('/admin', function() {
        return view('dashboard.admin-new');
    })->name('admin.dashboard');
    
    Route::get('/dashboard/admin', function() {
        return view('dashboard.admin-new');
    })->name('dashboard.admin');
    
    Route::get('/dashboard/guru', function() {
        return view('dashboard.guru');
    })->name('dashboard.guru');
    
    Route::get('/dashboard/siswa', function() {
        return view('dashboard.siswa');
    })->name('dashboard.siswa');
    
    // Admin User Management
    Route::get('/admin/users/create', [App\Http\Controllers\AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/users/store', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/admin/users/{id}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.users.delete');
    
    // Admin Article Moderation
    Route::post('/admin/articles/{id}/approve', [App\Http\Controllers\AdminController::class, 'approveArticle'])->name('admin.articles.approve');
    Route::post('/admin/articles/{id}/reject', [App\Http\Controllers\AdminController::class, 'rejectArticle'])->name('admin.articles.reject');
    
    // Guru Article Moderation
    Route::post('/guru/articles/{id}/approve', [App\Http\Controllers\GuruController::class, 'approveArticle'])->name('guru.articles.approve');
    Route::post('/guru/articles/{id}/reject', [App\Http\Controllers\GuruController::class, 'rejectArticle'])->name('guru.articles.reject');
    
    // Dashboard routes with proper data grouping
    Route::get('/dashboard', function() {
        $user = Auth::user();
        if ($user->role == 'admin') {
            return redirect()->route('dashboard.admin');
        } elseif ($user->role == 'guru') {
            return redirect()->route('dashboard.guru');
        } else {
            return redirect()->route('dashboard.siswa');
        }
    })->name('dashboard');
    
    // Admin Reports
    Route::get('/admin/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('admin.reports');
    Route::post('/admin/reports/artikel', [App\Http\Controllers\ReportController::class, 'artikelReport'])->name('admin.reports.artikel');
    Route::post('/admin/reports/aktivitas', [App\Http\Controllers\ReportController::class, 'aktivitasReport'])->name('admin.reports.aktivitas');
    
    // Dashboard data API routes
    Route::get('/api/dashboard/admin-data', [App\Http\Controllers\DashboardController::class, 'getAdminData'])->name('api.dashboard.admin');
    Route::get('/api/dashboard/guru-data', [App\Http\Controllers\DashboardController::class, 'getGuruData'])->name('api.dashboard.guru');
    Route::get('/api/dashboard/siswa-data', [App\Http\Controllers\DashboardController::class, 'getSiswaData'])->name('api.dashboard.siswa');
});