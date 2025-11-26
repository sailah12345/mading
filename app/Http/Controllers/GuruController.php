<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function approveArticle($id)
    {
        $article = Article::findOrFail($id);
        $article->update(['status' => 'published']);
        
        // Notifikasi untuk penulis
        NotificationController::createNotification(
            $article->user_id,
            'Artikel Disetujui',
            'Artikel "' . $article->title . '" telah disetujui dan dipublikasikan!',
            'success',
            $article->id
        );
        
        return back()->with('success', 'Artikel berhasil disetujui dan dipublikasikan!');
    }

    public function rejectArticle($id)
    {
        $article = Article::findOrFail($id);
        $article->update(['status' => 'rejected']);
        
        // Notifikasi untuk penulis
        NotificationController::createNotification(
            $article->user_id,
            'Artikel Ditolak',
            'Artikel "' . $article->title . '" ditolak. Silakan perbaiki dan kirim ulang.',
            'danger',
            $article->id
        );
        
        return back()->with('success', 'Artikel ditolak!');
    }
}
