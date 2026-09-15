<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || $request->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman admin.');
        }

        return $next($request);
    }
}

// Daftarkan middleware ini di bootstrap/app.php (Laravel 11) dengan alias 'admin', contoh:
// $middleware->alias(['admin' => \App\Http\Middleware\EnsureUserIsAdmin::class]);
// Jangan lupa tambahkan kolom 'role' pada migration tabel users (string, default 'admin').
