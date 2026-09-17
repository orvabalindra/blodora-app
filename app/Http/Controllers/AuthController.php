<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Memproses login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // =========================
            // ADMIN
            // =========================

            if ($user->role === 'admin') {
                return redirect()->route('dashboard');
            }

            // =========================
            // PENDONOR
            // =========================

            if ($user->role === 'pendonor') {

                // Kalau profil belum dibuat
                if (!$user->pendonor) {
                    return redirect()->route('pendonor.profile.create');
                }

                // Kalau profil sudah dibuat
                return redirect()->route('dashboard.pendonor');
            }

            // =========================
            // ROLE TIDAK DIKENAL
            // =========================

            Auth::logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'username' => 'Role akun tidak dikenali.',
                ]);
        }

        return back()
            ->withErrors([
                'username' => 'Username atau password salah.',
            ])
            ->onlyInput('username');
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}