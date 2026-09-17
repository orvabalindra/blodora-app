@extends('layouts.auth')

@section('title', 'Reset Password - Blodora')

@section('content')

    <div class="auth-topbar">

        <a
            href="{{ route('login') }}"
            class="auth-back"
        >
            ← Back
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
                Reset Password
            </h1>

            <p class="auth-subtitle">
                Create a new password for your account
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
                    value="{{ old('email', $email) }}"
                    placeholder="Enter your email"
                    required
                >

            </div>


            <!-- New Password -->

            <div class="auth-field">

                <label
                    for="password"
                    class="auth-label"
                >
                    New Password
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="auth-input"
                        placeholder="Enter your new password"
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
                    Confirm New Password
                </label>

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="auth-input"
                        placeholder="Confirm your new password"
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
                    Reset Password
                </span>

                <span class="btn-loading d-none">
                    <span class="auth-spinner"></span>
                    Updating password...
                </span>

            </button>

        </form>


        <div class="auth-footer">

            Remember your password?

            <a href="{{ route('login') }}">
                Login
            </a>

        </div>

    </div>

@endsection