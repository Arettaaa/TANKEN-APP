@extends('layouts.akun-pelanggan')

@section('title', 'Edit Profil — TANKEN')

@section('akun-content')
<div>
    <p class="text-[10px] sm:text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Personal</p>
    <h2 class="text-lg sm:text-xl font-extrabold text-gray-900 mb-6">Edit Profil</h2>

    <form action="{{ route('pelanggan.profil.simpan') }}" method="POST" class="flex flex-col gap-4 sm:gap-5">
        @csrf
        @method('PUT')

        <div>
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-input" value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="Nama lengkap kamu">
        </div>

        <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="email@example.com">
        </div>

        <div>
            <label class="form-label">Nomor Telepon</label>
            <input type="tel" name="phone" class="form-input" value="{{ old('phone', auth()->user()->phone ?? '') }}" placeholder="+62 812 3456 7890">
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full sm:w-auto bg-gray-900 text-white text-[10px] sm:text-xs font-bold tracking-widest uppercase px-7 py-3.5 rounded-md hover:bg-gray-700 transition-colors shadow-sm">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection