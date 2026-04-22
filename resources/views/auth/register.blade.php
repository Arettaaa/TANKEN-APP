@extends('layouts.auth')

@section('title', 'Daftar')
@section('left-title', 'Join TANKEN')
@section('left-desc', 'Create an account to unlock exclusive benefits, track orders, and save your favorites.')

@section('content')
@if(session('error'))
    <div class="mb-5 p-3 bg-red-50 text-red-600 text-sm font-semibold rounded-lg border border-red-100 text-center">
        {{ session('error') }}
    </div>
@endif
    <div class="mb-7">
        <h2 class="font-extrabold text-3xl text-gray-900 mb-1">Create Account</h2>
        <p class="text-sm text-gray-400 font-medium">Fill in your details to get started</p>
    </div>

    <form action="{{ route('register') }}" method="POST" class="flex flex-col gap-5">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-gray-800 mb-2">Full Name</label>
            <div class="input-wrap">
                <span class="input-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" width="17" height="17"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
                <input type="text" name="name" placeholder="John Doe" autocomplete="name" required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-800 mb-2">Email Address</label>
            <div class="input-wrap">
                <span class="input-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" width="17" height="17"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>
                <input type="email" name="email" placeholder="you@example.com" autocomplete="email" required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-800 mb-2">Password</label>
            <div class="input-wrap">
                <span class="input-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" width="17" height="17"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
                <input type="password" name="password" id="regPassword" placeholder="••••••••" autocomplete="new-password" required>
                <button type="button" class="eye-toggle" onclick="togglePassword('regPassword', this)">
                    {{-- Default: Mata disilang karena password berbentuk titik-titik --}}
                    <svg id="regPassword-eye-closed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" width="17" height="17"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    {{-- Hidden: Mata terbuka --}}
                    <svg id="regPassword-eye-open" style="display:none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" width="17" height="17"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
        </div>

        <button type="submit" class="w-full bg-black text-white font-bold text-sm py-4 rounded-full flex items-center justify-center gap-2 hover:bg-gray-900 transition-colors mt-1">
            Create Account
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="14" height="14"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-5">
        Already have an account?&nbsp;<a href="{{ route('login') }}" class="font-bold text-gray-900 hover:underline">Sign in</a>
    </p>

    <div class="flex items-center gap-3 my-5">
        <hr class="flex-1 border-gray-200">
        <span class="text-xs text-gray-400 font-medium">Or continue with</span>
        <hr class="flex-1 border-gray-200">
    </div>

    <a href="{{ route('google.login') }}" class="social-btn">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
        <span>Google</span>
    </a>
@endsection