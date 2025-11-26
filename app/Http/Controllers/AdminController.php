<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // MANAJEMEN USER
    public function createUser()
    {
        return view('admin.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:siswa,guru',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User berhasil ditambahkan!');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:siswa,guru,admin',
        ]);

        $user->update([ 
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        // Update password jika diisi
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'User berhasil diupdate!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect()->route('admin.dashboard')->with('success', 'User berhasil dihapus!');
    }

    // MODERASI ARTIKEL
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
