<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->with('article')
            ->latest()
            ->paginate(20);
            
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->update(['is_read' => true]);
        
        return back()->with('success', 'Notifikasi ditandai sudah dibaca');
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca');
    }

    public function getUnreadCount()
    {
        try {
            $count = Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();
                
            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            \Log::error('Error in getUnreadCount: ' . $e->getMessage());
            return response()->json(['count' => 0], 500);
        }
    }

    public function getRecent()
    {
        try {
            $notifications = Notification::where('user_id', Auth::id())
                ->latest()
                ->take(5)
                ->get()
                ->map(function($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'message' => Str::limit($notification->message, 60),
                        'type' => $notification->type,
                        'is_read' => $notification->is_read,
                        'created_at' => $notification->created_at->diffForHumans()
                    ];
                });
                
            $unreadCount = Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();
                
            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getRecent notifications: ' . $e->getMessage());
            return response()->json([
                'notifications' => [],
                'unread_count' => 0,
                'error' => 'Gagal memuat notifikasi'
            ], 500);
        }
    }

    public static function createNotification($userId, $title, $message, $type = 'info', $articleId = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'article_id' => $articleId
        ]);
    }
}