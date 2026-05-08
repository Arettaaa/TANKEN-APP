@extends('layouts.main')

@push('styles')
<style>
    /* Sidebar menu item */
    .menu-item { display: flex; align-items: center; gap: 10px; padding: 11px 16px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; color: #6b7280; cursor: pointer; transition: background 0.15s, color 0.15s; text-decoration: none; white-space: nowrap; flex-shrink: 0; }
    .menu-item:hover { background: #f3f4f6; color: #111; }
    .menu-item.active { background: #111; color: #fff; }
    .menu-item.active svg { stroke: #fff; }
    .menu-item.logout { color: #9ca3af; }
    .menu-item.logout:hover { background: #fee2e2; color: #dc2626; }

    /* Custom Horizontal Scrollbar for Mobile Nav */
    .mobile-nav-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .mobile-nav-scroll::-webkit-scrollbar { height: 8px; }
    .mobile-nav-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 8px; margin: 0 4px; }
    .mobile-nav-scroll::-webkit-scrollbar-thumb { background: #374151; border-radius: 8px; }

    /* Form input (Ditaruh di sini agar bisa dipakai semua form profil) */
    .form-input { width: 100%; padding: 12px 14px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 0.875rem; color: #111; outline: none; transition: border-color 0.2s; background: #fff; }
    .form-input:focus { border-color: #111; }
    .form-input::placeholder { color: #c5c5c5; }
    .form-label { display: block; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #9ca3af; margin-bottom: 6px; }

    /* Avatar */
    .avatar-circle { width: 56px; height: 56px; border-radius: 8px; background: #111; color: #fff; font-size: 1.4rem; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

    /* Alert */
    .alert-success { background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; border-radius: 8px; padding: 12px 16px; font-size: 0.875rem; }
    .alert-error   { background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; border-radius: 8px; padding: 12px 16px; font-size: 0.875rem; }
    
    /* Area khusus tambahan style untuk child view */
    @stack('akun-styles')
</style>
@endpush

@section('content')
<div class="bg-gray-50/30 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 py-8 lg:py-12">

        {{-- Breadcrumb --}}
        <p class="text-[10px] sm:text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Akun</p>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-4">Akun Saya</h1>

        {{-- Tombol Back Tambahan --}}
        <a href="{{ route('pelanggan.home') ?? url('/') }}" class="inline-flex items-center gap-1.5 text-[11px] sm:text-xs font-bold tracking-widest uppercase text-gray-500 hover:text-gray-900 transition-colors mb-6 lg:mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Back
        </a>

        <div class="flex flex-col lg:flex-row gap-6 items-start">

            {{-- ===== SIDEBAR MENU ===== --}}
            <aside class="w-full lg:w-64 flex-shrink-0">
                <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-5">

                    {{-- Avatar & Nama --}}
                    <div class="flex flex-col items-center text-center pb-4 mb-4 border-b border-gray-100 hidden lg:flex">
                        <div class="avatar-circle mb-3">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <p class="font-bold text-sm text-gray-900">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                        <p class="text-xs text-gray-400 mt-0.5 break-all">{{ auth()->user()->email ?? 'email@example.com' }}</p>
                    </div>

                    {{-- Menu Navigasi Otomatis Aktif --}}
                    <nav class="flex flex-row lg:flex-col lg:overflow-visible gap-2 lg:gap-1 pb-3 lg:pb-0 mobile-nav-scroll w-full">
                        <a href="{{ route('pelanggan.profil-edit') }}" class="menu-item {{ request()->routeIs('pelanggan.profil-edit') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Edit Profil
                        </a>
                        <a href="{{ route('pelanggan.profil-password') }}" class="menu-item {{ request()->routeIs('pelanggan.profil-password') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="15" height="15"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            Ganti Password
                        </a>
                        <a href="{{ route('pelanggan.profil-order') }}" class="menu-item {{ request()->routeIs('pelanggan.profil-order') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            Pesanan
                        </a>
                        <a href="{{ route('pelanggan.profil-wishlist') }}" class="menu-item {{ request()->routeIs('pelanggan.profil-wishlist') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            Wishlist
                        </a>
                        <a href="{{ route('pelanggan.profil-alamat') }}" class="menu-item {{ request()->routeIs('pelanggan.profil-alamat') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            Alamat
                        </a>
                        <hr class="border-gray-100 my-1 hidden lg:block">
                        <a href="{{ route('logout') }}" class="menu-item logout hidden lg:flex" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Keluar
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                    </nav>
                </div>
            </aside>

            {{-- ===== MAIN CONTENT KHUSUS (Berubah sesuai halaman) ===== --}}
            <div class="flex-1 min-w-0 w-full">
                <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8">

                    {{-- Pesan Alert Global --}}
                    @if(session('success'))
                        <div class="alert-success mb-6 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M20 6L9 17l-5-5"/></svg>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert-error mb-6">
                            <ul class="flex flex-col gap-0.5 list-disc list-inside text-sm">
                                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Konten tiap halaman masuk ke sini --}}
                    @yield('akun-content')

                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @stack('akun-scripts')
@endpush