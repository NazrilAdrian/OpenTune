<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Dipasang SETELAH middleware 'auth', jadi guest sudah dialihkan ke login lebih dulu.
        abort_unless($request->user()?->isAdmin(), 403, 'Halaman ini khusus admin.');

        return $next($request);
    }
}