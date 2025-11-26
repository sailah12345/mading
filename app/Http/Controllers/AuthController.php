<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\LogAktivitas;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }



    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            
            // Log aktivitas login
            LogAktivitas::log('Login ke sistem', $user->id);
            
            if ($user->role == 'admin') {
                return redirect('/admin');
            } elseif ($user->role == 'guru') {
                return redirect('/dashboard/guru');
            } else {
                return redirect('/dashboard/siswa');
            }
        }
        
        return back()->with('error', 'Email atau password salah!');
    }



    public function logout(Request $request)
    {
        try {
            // Log aktivitas logout
            if (Auth::check()) {
                LogAktivitas::log('Logout dari sistem', Auth::id());
            }
            
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect('/')->with('success', 'Berhasil logout!');
        } catch (\Exception $e) {
            // Jika terjadi error (termasuk CSRF), tetap logout paksa
            Auth::logout();
            return redirect('/')->with('success', 'Berhasil logout!');
        }
    }
}