<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (auth()->check()) {
            $aksi = $this->getAksi($request);
            if ($aksi) {
                LogAktivitas::log($aksi);
            }
        }

        return $response;
    }

    private function getAksi(Request $request)
    {
        $method = $request->method();
        $route = $request->route()->getName() ?? $request->path();

        switch (true) {
            case str_contains($route, 'login'):
                return 'Login ke sistem';
            case str_contains($route, 'logout'):
                return 'Logout dari sistem';
            case str_contains($route, 'articles.store'):
                return 'Membuat artikel baru';
            case str_contains($route, 'articles.update'):
                return 'Mengupdate artikel';
            case str_contains($route, 'articles.destroy'):
                return 'Menghapus artikel';
            case str_contains($route, 'dashboard'):
                return 'Mengakses dashboard';
            default:
                return null;
        }
    }
}