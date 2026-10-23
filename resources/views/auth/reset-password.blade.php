@extends('layouts.auth')

@section('title', 'Atur Ulang Kata Sandi - Blodora')

@section('content')

    <div class="auth-topbar">

        <a
            href="{{ route('login') }}"
            class="auth-back"
        >
            ← Kembali
        </a>

    </div>


    <div class="auth-card">

        <div class="auth-header">

            <img
                src="{{ asset('images/Blodora2.png') }}"
                alt="Blodora"
                class="auth-logo"
            >

            <h1 class="auth-title">
                Atur Ulang Kata Sandi
            </h1>

            <p class="auth-subtitle">
                Buat kata sandi baru untuk akun Anda
            </p>

        </div>


        @if ($errors->any())

            <div class="auth-error">
                {{ $errors->first() }}
            </div>

        @endif


        <form
            action="{{ route('password.update') }}"
            method="POST"
            class="auth-form"
        >

            @csrf


            <input
                type="hidden"
                name="token"
                value="{{ $token }}"
            >


            <!-- Alamat email -->

            <div class="auth-field">

                <label
                    for="email"
                    class="auth-label"
                >
                    Alamat email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    class="auth-input"
                    value="{{ old('email', $email) }}"
                    placeholder="Masukkan alamat email"
                    required
                >

            </div>


            <!-- New Kata sandi -->

            <div class="auth-field">

                <label
                    for="password"
                    class="auth-label"
                >
                    Kata sandi baru
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="auth-input"
                    placeholder="Masukkan kata sandi baru"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        data-password-toggle="password"
                        aria-label="Tampilkan password"
                    >
                        <i class="bi bi-eye"></i>
                    </button>

                </div>

            </div>


            <!-- Confirm Kata sandi -->

            <div class="auth-field">

                <label
                    for="password_confirmation"
                    class="auth-label"
                >
                    Konfirmasi kata sandi baru
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="auth-input"
                        placeholder="Masukkan kembali kata sandi baru"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        data-password-toggle="password_confirmation"
                        aria-label="Tampilkan konfirmasi password"
                    >
                        <i class="bi bi-eye"></i>
                    </button>

                </div>

            </div>


            <!-- Submit -->

            <button
                type="submit"
                class="btn-blodora"
            >

                <span class="btn-text">
                    Atur Ulang Kata Sandi
                </span>

                <span class="btn-loading d-none">
                    <span class="auth-spinner"></span>
                    Memperbarui kata sandi...
                </span>

            </button>

        </form>


        <div class="auth-footer">

            Ingat kata sandi Anda?

            <a href="{{ route('login') }}">
                Masuk
            </a>

        </div>

    </div>

@endsection
