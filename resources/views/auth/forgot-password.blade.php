@extends('layouts.auth')

@section('title', 'Lupa Kata Sandi - Blodora')

@section('content')

    <div class="auth-topbar">

        <a
            href="{{ route('login') }}"
            class="auth-back"
        >
            ← Kembali
        </a>

        <div class="auth-switch">

            <span>
                Ingat kata sandi Anda?
            </span>

            <a
                href="{{ route('login') }}"
                class="auth-login-link"
            >
                Masuk
            </a>

        </div>

    </div>


    <div class="auth-card">

        <div class="auth-header">

            <img
                src="{{ asset('images/Blodora2.png') }}"
                alt="Blodora"
                class="auth-logo"
            >

            <h1 class="auth-title">
                Lupa Kata Sandi?
            </h1>

            <p class="auth-subtitle">
                Masukkan alamat email untuk menerima tautan pengaturan ulang kata sandi
            </p>

        </div>


        @if (session('status'))

            <div class="auth-success">
                {{ session('status') }}
            </div>

        @endif


        @if ($errors->any())

            <div class="auth-error">
                {{ $errors->first() }}
            </div>

        @endif


        <form
            action="{{ route('password.email') }}"
            method="POST"
            class="auth-form"
        >

            @csrf


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
                    value="{{ old('email') }}"
                    placeholder="Masukkan alamat email"
                    required
                    autofocus
                >

            </div>


            <button
                type="submit"
                class="btn-blodora"
            >

                <span class="btn-text">
                    Kirim Tautan Pengaturan Ulang
                </span>

                <span class="btn-loading d-none">
                    <span class="auth-spinner"></span>
                    Mengirim...
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
