<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== 'admin') {
            $user = $request->user();

            if ($user->role === 'pendonor') {
                return $user->pendonor
                    ? redirect()->route('dashboard.pendonor')
                    : redirect()->route('pendonor.profile.create');
            }

            if ($user->role === 'rumah_sakit' && $user->rumahSakit) {
                return redirect()->route('faskes.permintaan.index');
            }

            return redirect()->route('home');
        }

        return $next($request);
    }
}
