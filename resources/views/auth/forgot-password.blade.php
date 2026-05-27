@extends('layouts.auth')

@section('title', 'Lupa Password')
@section('left-title', 'Reset Password')
@section('left-desc', 'Enter your email and we will help you set a new password - no old password needed.')
@section('content')
    @if(session('error'))
        <div class="mb-5 p-3 bg-red-50 text-red-600 text-sm font-semibold rounded-lg border border-red-100 text-center">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="mb-5 p-3 bg-green-50 text-green-600 text-sm font-semibold rounded-lg border border-green-100 text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-7">
        <h2 class="font-extrabold text-3xl text-gray-900 mb-1">Forgot Password</h2>
        <p class="text-sm text-gray-400 font-medium">We'll check if your email is registered</p>
    </div>

    <form action="{{ route('password.check-email') }}" method="POST" class="flex flex-col gap-5">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-gray-800 mb-2">Email Address</label>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" width="17" height="17">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                </span>
                <input type="email" name="email" placeholder="you@example.com"
                       value="{{ old('email') }}" autocomplete="email" required>
            </div>
        </div>

        <button type="submit"
                class="w-full bg-black text-white font-bold text-sm py-4 rounded-full flex items-center justify-center gap-2 hover:bg-gray-900 transition-colors">
            Continue
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="14" height="14">
                <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-5">
        Remember your password?&nbsp;
        <a href="{{ route('login') }}" class="font-bold text-gray-900 hover:underline">Sign In</a>
    </p>
@endsection