<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RumahSakitMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(
            $request->user()?->role === 'rumah_sakit' && $request->user()->rumahSakit,
            403,
        );

        return $next($request);
    }
}
