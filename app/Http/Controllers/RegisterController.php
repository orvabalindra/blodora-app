<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Menampilkan halaman registrasi.
     */
    public function showRegister(Request $request)
    {
        if ($request->user()) {
            return $this->authenticatedDestination($request);
        }

        return view('auth.register');
    }

    /**
     * Memproses registrasi pendonor.
     */
    public function register(Request $request)
    {
        if ($request->user()) {
            return $this->authenticatedDestination($request);
        }

        $request->merge([
            'username' => Str::lower(trim((string) $request->input('username', ''))),
            'email' => Str::lower(trim((string) $request->input('email', ''))),
        ]);

        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:30', 'alpha_dash:ascii', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ], [
            'username.required' => 'Nama pengguna wajib diisi.',
            'username.min' => 'Nama pengguna harus terdiri dari minimal 3 karakter.',
            'username.max' => 'Nama pengguna tidak boleh lebih dari 30 karakter.',
            'username.alpha_dash' => 'Nama pengguna hanya boleh berisi huruf, angka, garis bawah, dan tanda hubung.',
            'username.unique' => 'Nama pengguna sudah digunakan. Silakan pilih nama pengguna lain.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Masukkan alamat email yang valid.',
            'email.max' => 'Alamat email terlalu panjang.',
            'email.unique' => 'Alamat email sudah terdaftar. Silakan masuk atau gunakan alamat email lain.',
            'password.min' => 'Kata sandi harus terdiri dari minimal 8 karakter.',
            'password.max' => 'Kata sandi terlalu panjang.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'pendonor',
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Pendaftaran berhasil. Silakan masuk.');
    }
}
