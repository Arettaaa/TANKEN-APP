@extends('layouts.auth')

@section('title', 'Reset Password')
@section('left-title', 'New Password')
@section('left-desc', 'Almost there! Set a strong new password for your account.')

@section('content')
    @if(session('error'))
        <div class="mb-5 p-3 bg-red-50 text-red-600 text-sm font-semibold rounded-lg border border-red-100 text-center">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
    <div class="mb-5 p-3 bg-red-50 text-red-600 text-sm font-semibold rounded-lg border border-red-100 text-center">
        {{ $errors->first() }}
    </div>
    @endif

    <div class="mb-7">
        <h2 class="font-extrabold text-3xl text-gray-900 mb-1">Reset Password</h2>
        <p class="text-sm text-gray-400 font-medium">
            Setting new password for
            <span class="font-semibold text-gray-700">{{ session('reset_email') }}</span>
        </p>
    </div>

    <form action="{{ route('password.reset') }}" method="POST" class="flex flex-col gap-5">
        @csrf

        {{-- New Password --}}
        <div>
            <label class="block text-sm font-semibold text-gray-800 mb-2">New Password</label>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" width="17" height="17">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </span>
                <input type="password" name="password" id="newPassword"
                       placeholder="Min. 8 characters" autocomplete="new-password" required
                       oninput="checkStrength(this.value); checkMatch()">
                <button type="button" class="eye-toggle" onclick="togglePassword('newPassword', this)">
                    <svg id="newPassword-eye-closed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" width="17" height="17">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                        <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                    <svg id="newPassword-eye-open" style="display:none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" width="17" height="17">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>

            {{-- Strength Bar --}}
            <div class="mt-2">
                <div class="flex gap-1 mb-1">
                    <div id="bar1" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors duration-300"></div>
                    <div id="bar2" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors duration-300"></div>
                    <div id="bar3" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors duration-300"></div>
                    <div id="bar4" class="h-1 flex-1 rounded-full bg-gray-200 transition-colors duration-300"></div>
                </div>
                <p id="strength-label" class="text-xs text-gray-400"></p>
            </div>
        </div>

        {{-- Confirm Password --}}
        <div>
            <label class="block text-sm font-semibold text-gray-800 mb-2">Confirm New Password</label>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" width="17" height="17">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </span>
                <input type="password" name="password_confirmation" id="confirmPassword"
                       placeholder="Repeat new password" autocomplete="new-password" required
                       oninput="checkMatch()">
                <button type="button" class="eye-toggle" onclick="togglePassword('confirmPassword', this)">
                    <svg id="confirmPassword-eye-closed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" width="17" height="17">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                        <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                    <svg id="confirmPassword-eye-open" style="display:none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" width="17" height="17">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            <p id="match-label" class="text-xs mt-1.5 min-h-[16px]"></p>
        </div>

        <button type="submit"
                class="w-full bg-black text-white font-bold text-sm py-4 rounded-full flex items-center justify-center gap-2 hover:bg-gray-900 transition-colors">
            Save New Password
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="14" height="14">
                <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-5">
        Wrong email?&nbsp;
        <a href="{{ route('password.forgot') }}" class="font-bold text-gray-900 hover:underline">Go back</a>
    </p>

<script>
    function checkStrength(val) {
        let score = 0;
        if (val.length >= 8)           score++;
        if (/[A-Z]/.test(val))         score++;
        if (/[0-9]/.test(val))         score++;
        if (/[^A-Za-z0-9]/.test(val))  score++;

        const bars   = ['bar1','bar2','bar3','bar4'].map(id => document.getElementById(id));
        const label  = document.getElementById('strength-label');
        const colors = ['','bg-red-400','bg-orange-400','bg-yellow-400','bg-green-500'];
        const labels = ['','Sangat lemah','Lemah','Cukup kuat','Kuat'];
        const texts  = ['','text-red-400','text-orange-400','text-yellow-400','text-green-500'];

        bars.forEach((bar, i) => {
            bar.className = 'h-1 flex-1 rounded-full transition-colors duration-300 ' +
                (i < score ? colors[score] : 'bg-gray-200');
        });

        label.textContent = val.length === 0 ? '' : labels[score];
        label.className   = 'text-xs ' + (val.length === 0 ? 'text-gray-400' : texts[score]);
    }

    function checkMatch() {
        const pw      = document.getElementById('newPassword').value;
        const confirm = document.getElementById('confirmPassword').value;
        const label   = document.getElementById('match-label');

        if (confirm.length === 0) { label.textContent = ''; return; }

        if (pw === confirm) {
            label.textContent = '✓ Password cocok';
            label.className   = 'text-xs mt-1.5 text-green-500 font-medium';
        } else {
            label.textContent = '✗ Password tidak cocok';
            label.className   = 'text-xs mt-1.5 text-red-500 font-medium';
        }
    }
</script>

@endsection