<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;

class HandleCsrfException
{
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (TokenMismatchException $e) {
            // Jika CSRF error pada logout, tetap lakukan logout
            if ($request->is('logout') && $request->isMethod('post')) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect('/')->with('success', 'Berhasil logout!');
            }
            
            throw $e;
        }
    }
}