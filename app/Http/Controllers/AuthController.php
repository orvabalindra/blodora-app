<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLogin(Request $request)
    {
        if ($request->user()) {
            return $this->authenticatedDestination($request);
        }

        return view('auth.login');
    }

    /**
     * Memproses login.
     */
    public function login(Request $request)
    {
        if ($request->user()) {
            return $this->authenticatedDestination($request);
        }

        $request->merge([
            'username' => trim((string) $request->input('username', '')),
        ]);

        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ], [
            'username.required' => 'Nama pengguna wajib diisi.',
            'username.max' => 'Nama pengguna terlalu panjang.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.max' => 'Kata sandi terlalu panjang.',
        ]);

        $remember = $request->boolean('remember');
        $loginSuccessful = Auth::attempt($credentials, $remember);

        if (! $loginSuccessful) {
            $normalizedCredentials = $credentials;
            $normalizedCredentials['username'] = Str::lower($credentials['username']);

            if ($normalizedCredentials['username'] !== $credentials['username']) {
                $loginSuccessful = Auth::attempt($normalizedCredentials, $remember);
            }
        }

        if ($loginSuccessful) {
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
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'username' => 'Peran akun tidak dikenali.',
                ]);
        }

        return back()
            ->withErrors([
                'username' => 'Nama pengguna atau kata sandi salah.',
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
