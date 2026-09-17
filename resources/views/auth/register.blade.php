@extends('layouts.auth')

@section('title', 'Register - Blodora')

@section('content')

    <div class="auth-topbar">

        <a href="/" class="auth-back">
            ← Back
        </a>

        <div class="auth-switch">

            <span>
                Sudah punya akun?
            </span>

            <a
                href="{{ route('login') }}"
                class="auth-login-link"
            >
                Login
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
                Create Account
            </h1>

            <p class="auth-subtitle">
                Register to get started
            </p>

        </div>


        @if ($errors->any())

            <div class="auth-error">

                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach

            </div>

        @endif


        <form
            action="{{ route('register.process') }}"
            method="POST"
            class="auth-form"
        >

            @csrf


            <!-- Username -->

            <div class="auth-field">

                <label
                    for="username"
                    class="auth-label"
                >
                    Username
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    class="auth-input"
                    value="{{ old('username') }}"
                    placeholder="Choose a username"
                    required
                    autofocus
                >

            </div>


            <!-- Email -->

            <div class="auth-field">

                <label
                    for="email"
                    class="auth-label"
                >
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    class="auth-input"
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    required
                >

            </div>


            <!-- Password -->

            <div class="auth-field">

                <label
                    for="password"
                    class="auth-label"
                >
                    Password
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="auth-input"
                        placeholder="Create a password"
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


            <!-- Confirm Password -->

            <div class="auth-field">

                <label
                    for="password_confirmation"
                    class="auth-label"
                >
                    Confirm Password
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="auth-input"
                        placeholder="Confirm your password"
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
                    Register
                </span>

                <span class="btn-loading d-none">
                    <span class="auth-spinner"></span>
                    Creating account...
                </span>
            </button>

        </form>


        <div class="auth-divider">
            OR
        </div>


        <button
            type="button"
            class="btn-google"
        >
            <span class="google-icon">
                G
            </span>

            Continue with Google
        </button>


        <div class="auth-footer">
            By registering, you agree to our terms.
        </div>

    </div>

@endsection