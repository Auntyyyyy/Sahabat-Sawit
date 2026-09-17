<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * @param  string  ...$roles  Role yang diizinkan, mis: 'admin', 'hr'
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! auth()->check()) {
            abort(403, 'Unauthorized.');
        }

        // Kalau tidak ada role spesifik disebutkan, default izinkan admin, general_officer, hr
        $allowed = $roles ?: ['admin', 'general_officer', 'hr'];

        if (! in_array(auth()->user()->role, $allowed, true)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}