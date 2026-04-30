@extends('layouts.akun-pelanggan')

@section('title', 'Ganti Password — TANKEN')

@push('akun-styles')
<style>
    /* Password eye toggle khusus halaman ini */
    .pw-wrap { position: relative; }
    .pw-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #bbb; background: none; border: none; display: flex; align-items: center; transition: color 0.2s; }
    .pw-toggle:hover { color: #555; }
</style>
@endpush

@section('akun-content')
<div>
    <p class="text-[10px] sm:text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Keamanan</p>
    <h2 class="text-lg sm:text-xl font-extrabold text-gray-900 mb-6">Ganti Password</h2>

    <form action="{{ route('pelanggan.ganti-password.simpan') }}" method="POST" class="flex flex-col gap-4 sm:gap-5">
        @csrf
        @method('PUT')

        <div>
            <label class="form-label">Password Saat Ini</label>
            <div class="pw-wrap">
                <input type="password" name="current_password" id="pwCurrent" class="form-input" placeholder="••••••••" style="padding-right:44px;">
                <button type="button" class="pw-toggle" onclick="togglePw('pwCurrent','eyeCurrent')">
                    <svg id="eyeCurrentOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" width="16" height="16"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg id="eyeCurrentClosed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" width="16" height="16" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
        </div>

        <div>
            <label class="form-label">Password Baru</label>
            <div class="pw-wrap">
                <input type="password" name="password" id="pwNew" class="form-input" placeholder="Min. 8 karakter" style="padding-right:44px;">
                <button type="button" class="pw-toggle" onclick="togglePw('pwNew','eyeNew')">
                    <svg id="eyeNewOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" width="16" height="16"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg id="eyeNewClosed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" width="16" height="16" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
        </div>

        <div>
            <label class="form-label">Konfirmasi Password Baru</label>
            <div class="pw-wrap">
                <input type="password" name="password_confirmation" id="pwConfirm" class="form-input" placeholder="Ulangi password baru" style="padding-right:44px;">
                <button type="button" class="pw-toggle" onclick="togglePw('pwConfirm','eyeConfirm')">
                    <svg id="eyeConfirmOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" width="16" height="16"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg id="eyeConfirmClosed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" width="16" height="16" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full sm:w-auto bg-gray-900 text-white text-[10px] sm:text-xs font-bold tracking-widest uppercase px-7 py-3.5 rounded-md hover:bg-gray-700 transition-colors shadow-sm">
                Perbarui Password
            </button>
        </div>
    </form>
</div>
@endsection

@push('akun-scripts')
<script>
    function togglePw(inputId, eyePrefix) {
        const input = document.getElementById(inputId);
        const isPass = input.type === 'password';
        input.type = isPass ? 'text' : 'password';
        document.getElementById(eyePrefix + 'Open').style.display   = isPass ? 'none'  : 'block';
        document.getElementById(eyePrefix + 'Closed').style.display = isPass ? 'block' : 'none';
    }
</script>
@endpush