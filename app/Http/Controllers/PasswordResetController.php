<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    /**
     * Menampilkan form meminta link reset password.
     */
    public function request()
    {
        return view('auth.forgot-password');
    }

    /**
     * Mengirim link reset password.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        Password::sendResetLink(
            $request->only('email')
        );

        return back()->with(
            'status',
            'Jika alamat email terdaftar, tautan pengaturan ulang kata sandi akan dikirim. Periksa kotak masuk dan folder spam.'
        );
    }

    /**
     * Menampilkan form password baru.
     */
    public function resetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Memproses password baru.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'Kata sandi berhasil diubah. Silakan masuk kembali.'
                );
        }

        return back()->withErrors([
            'email' => __($status),
        ]);
    }
}
