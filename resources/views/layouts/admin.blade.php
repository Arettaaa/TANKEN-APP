<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') — TANKEN</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        'admin-sidebar' : '#111111',
                        'admin-hover'   : '#1f1f1f',
                        'admin-active'  : '#2a2a2a',
                        'admin-bg'      : '#f4f4f5',
                    }
                }
            }
        }
    </script>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f4f5;
        }

        .sidebar-link.active {
            background: #ffffff14;
            color: #ffffff !important;
            font-weight: 600;
        }

        .sidebar-link:hover {
            background: #ffffff0d;
        }

        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }

        .badge-green {
            @apply inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700;
        }

        .badge-yellow {
            @apply inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700;
        }

        .badge-blue {
            @apply inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700;
        }

        .badge-red {
            @apply inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-700;
        }

        .badge-purple {
            @apply inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-purple-100 text-purple-700;
        }

        .badge-gray {
            @apply inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-600;
        }

        #sidebar-overlay {
            display: none;
        }

        #sidebar-overlay.show {
            display: block;
        }

        @media (max-width: 768px) {
            #admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                position: fixed;
                z-index: 40;
                height: 100vh;
            }

            #admin-sidebar.open {
                transform: translateX(0);
            }
        }
    </style>
    @stack('styles')
</head>

<body class="flex h-screen overflow-hidden">

    {{-- ====== LOGIKA ROLE ====== --}}
    @php
    $role = auth()->check() ? auth()->user()->role : 'super_admin';
    $isGudang = ($role === 'admin_gudang');
    @endphp

    {{-- ====== SIDEBAR ====== --}}
    <aside id="admin-sidebar"
        class="w-52 flex-shrink-0 bg-admin-sidebar text-white flex flex-col h-screen overflow-y-auto">

        <div class="px-5 py-5 border-b border-white/10">
            <p class="text-base font-extrabold tracking-widest uppercase">TANKEN</p>
            <p class="text-xs text-gray-400 mt-0.5">Admin Panel</p>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-0.5 text-sm">

            {{-- BISA DIAKSES OLEH SEMUA (Gudang & Owner) --}}

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 transition-colors {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8" width="17" height="17">
                    <rect x="3" y="3" width="7" height="7" rx="1" />
                    <rect x="14" y="3" width="7" height="7" rx="1" />
                    <rect x="14" y="14" width="7" height="7" rx="1" />
                    <rect x="3" y="14" width="7" height="7" rx="1" />
                </svg>
                Dashboard
            </a>

            {{-- Products --}}
            <a href="{{ route('admin.products.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 transition-colors {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8" width="17" height="17">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                    <circle cx="7" cy="7" r="1.5" fill="currentColor" />
                </svg>
                Products
            </a>

            {{-- Reviews --}}
            <a href="{{ route('admin.reviews.index') }}"
                class="sidebar-link flex items-center gap-3 pl-9 pr-3 py-2 rounded-lg text-gray-400 text-xs transition-colors {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8" width="14" height="14">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
                Reviews
            </a>

            {{-- Stock --}}
            <a href="{{ route('admin.stock.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 transition-colors {{ request()->routeIs('admin.stock*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8" width="17" height="17">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                Stock
            </a>

            {{-- Orders --}}
            <a href="{{ route('admin.orders.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 transition-colors {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8" width="17" height="17">
                    <circle cx="9" cy="21" r="1" />
                    <circle cx="20" cy="21" r="1" />
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                </svg>
                Orders
            </a>

            {{-- TERKUNCI (Hanya bisa diakses Owner/Super Admin) --}}

            {{-- Payments --}}
            @if($isGudang)
            <div class="flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-500 opacity-60 cursor-not-allowed select-none"
                title="Akses Dibatasi">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8" width="17" height="17">
                        <rect x="1" y="4" width="22" height="16" rx="2" />
                        <line x1="1" y1="10" x2="23" y2="10" />
                    </svg>
                    Payments
                </div>
                <i class="fa-solid fa-lock text-[12px]" style="color: rgb(203, 203, 203);"></i>
            </div>
            @else
            <a href="{{ route('admin.payments.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 transition-colors {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8" width="17" height="17">
                    <rect x="1" y="4" width="22" height="16" rx="2" />
                    <line x1="1" y1="10" x2="23" y2="10" />
                </svg>
                Payments
            </a>
            @endif

            {{-- Reports --}}
            @if($isGudang)
            <div class="flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-500 opacity-60 cursor-not-allowed select-none"
                title="Akses Dibatasi">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8" width="17" height="17">
                        <line x1="18" y1="20" x2="18" y2="10" />
                        <line x1="12" y1="20" x2="12" y2="4" />
                        <line x1="6" y1="20" x2="6" y2="14" />
                    </svg>
                    Reports
                </div>
                <i class="fa-solid fa-lock text-[12px]" style="color: rgb(203, 203, 203);"></i>
            </div>
            @else
            <a href="{{ route('admin.reports.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 transition-colors {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8" width="17" height="17">
                    <line x1="18" y1="20" x2="18" y2="10" />
                    <line x1="12" y1="20" x2="12" y2="4" />
                    <line x1="6" y1="20" x2="6" y2="14" />
                </svg>
                Reports
            </a>
            @endif

            {{-- Users --}}
            @if($isGudang)
            <div class="flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-500 opacity-60 cursor-not-allowed select-none"
                title="Akses Dibatasi">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8" width="17" height="17">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    Users
                </div>
                <i class="fa-solid fa-lock text-[12px]" style="color: rgb(203, 203, 203);"></i>
            </div>
            @else
            <a href="{{ route('admin.users.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 transition-colors {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8" width="17" height="17">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                Users
            </a>
            @endif

            {{-- Promo & Voucher --}}
            @if($isGudang)
            <div class="flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-500 opacity-60 cursor-not-allowed select-none"
                title="Akses Dibatasi">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.8" width="17" height="17">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                        <line x1="7" y1="7" x2="7.01" y2="7" />
                    </svg>
                    Promo & Voucher
                </div>
                <i class="fa-solid fa-lock text-[12px]" style="color: rgb(203, 203, 203);"></i>
            </div>
            @else
            <a href="{{ route('admin.promos.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 transition-colors {{ request()->routeIs('admin.promo*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8" width="17" height="17">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                    <line x1="7" y1="7" x2="7.01" y2="7" />
                </svg>
                Promo & Voucher
            </a>
            @endif
        </nav>

        {{-- Bottom: back to store --}}
        <div class="px-3 py-4 border-t border-white/10">
            <a href="{{ route('pelanggan.home') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 text-xs hover:text-white hover:bg-white/10 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.8" width="15" height="15">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                Lihat Toko
            </a>
        </div>
    </aside>

    {{-- Mobile overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 md:hidden" onclick="toggleSidebar()"></div>

    {{-- ====== MAIN CONTENT ====== --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- ====== TOPBAR ====== --}}
        <header
            class="bg-white border-b border-gray-200 h-14 flex items-center justify-between px-5 flex-shrink-0 z-20">

            <div class="flex items-center gap-4">
                {{-- Hamburger mobile --}}
                <button class="md:hidden text-gray-500 hover:text-black" onclick="toggleSidebar()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2" width="20" height="20">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                {{-- Breadcrumb --}}
                <div>
                    <h1 class="text-base font-bold text-gray-900 leading-tight">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-gray-400 leading-tight">@yield('breadcrumb', 'Home / Dashboard')</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                {{-- Notification bell --}}
                <div class="relative" id="notif-menu">
                    <button onclick="toggleNotif()" class="relative text-gray-500 hover:text-black transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8" width="20" height="20">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                            <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                        </svg>
                        @if($lowStockCount > 0)
                        <span
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                            {{ $lowStockCount > 9 ? '9+' : $lowStockCount }}
                        </span>
                        @endif
                    </button>

                    {{-- Dropdown notifikasi --}}
                    <div id="notif-dropdown"
                        class="hidden absolute right-0 top-full mt-2 w-72 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden">

                        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                            <p class="text-xs font-bold text-gray-900 uppercase tracking-widest">Notifikasi Stok</p>
                            @if($lowStockCount > 0)
                            <span class="text-[10px] bg-red-100 text-red-600 font-bold px-2 py-0.5 rounded-full">
                                {{ $lowStockCount }} produk
                            </span>
                            @endif
                        </div>

                        <div class="max-h-64 overflow-y-auto divide-y divide-gray-50">
                            @forelse($lowStockProducts as $p)
                            @php $stok = $p->stocks->sum('quantity') + $p->stock; @endphp
                            <a href="{{ route('admin.stock.index') }}"
                                class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                                {{-- Foto produk --}}
                                @if($p->main_image)
                                <img src="{{ asset('storage/' . $p->main_image) }}"
                                    class="w-9 h-9 rounded object-cover border border-gray-100 flex-shrink-0">
                                @else
                                <div class="w-9 h-9 rounded bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-shirt text-gray-300 text-xs"></i>
                                </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-gray-900 truncate">{{ $p->name }}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">SKU: {{ $p->sku }}</p>
                                </div>
                                <span
                                    class="text-xs font-bold {{ $stok == 0 ? 'text-red-600' : 'text-orange-500' }} flex-shrink-0">
                                    {{ $stok }} pcs
                                </span>
                            </a>
                            @empty
                            <div class="px-4 py-8 text-center">
                                <i class="fa-solid fa-box-open text-gray-200 text-2xl mb-2 block"></i>
                                <p class="text-xs text-gray-400">Semua stok aman</p>
                            </div>
                            @endforelse
                        </div>

                        <div class="px-4 py-2.5 border-t border-gray-100">
                            <a href="{{ route('admin.stock.index') }}"
                                class="text-xs font-semibold text-gray-500 hover:text-black transition-colors">
                                Lihat semua stok →
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Admin user dropdown --}}
                <div class="relative" id="admin-user-menu">
                    <button onclick="toggleUserMenu()"
                        class="flex items-center gap-2.5 hover:bg-gray-50 rounded-lg px-2 py-1.5 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white"
                                stroke-width="1.8" width="16" height="16">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                        <div class="text-left hidden sm:block">
                            <p class="text-xs font-semibold text-gray-900 leading-tight">{{ auth()->user()->name ??
                                'Admin User' }}</p>
                            <p class="text-[10px] text-gray-400 leading-tight capitalize">
                                {{ str_replace('_', ' ', auth()->user()->role ?? 'Administrator') }}
                            </p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2" width="14" height="14" class="text-gray-400">
                            <path d="M6 9l6 6 6-6" />
                        </svg>
                    </button>

                    {{-- Dropdown menu --}}
                    <div id="user-dropdown"
                        class="hidden absolute right-0 top-full mt-1 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-xs font-semibold text-gray-900">{{ auth()->user()->name ?? 'Admin User' }}
                            </p>
                            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email ?? 'admin@tanken.com' }}
                            </p>
                        </div>
                        <a href="#"
                            class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8" width="15" height="15">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            Edit Profil
                        </a>

                        {{-- TOMBOL LOGOUT --}}
                        <div class="border-t border-gray-100 mt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="1.8" width="15" height="15">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                        <polyline points="16 17 21 12 16 7" />
                                        <line x1="21" y1="12" x2="9" y2="12" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </header>

        {{-- ====== PAGE CONTENT ====== --}}
        <main class="flex-1 overflow-y-auto p-5 md:p-6">

            {{-- Flash messages --}}
            @if(session('success'))
            <div
                class="mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2" width="16" height="16" class="flex-shrink-0">
                    <path d="M20 6L9 17l-5-5" />
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div
                class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2" width="16" height="16" class="flex-shrink-0">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
        const sidebar  = document.getElementById('admin-sidebar');
        const overlay  = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    }

    function toggleUserMenu() {
        document.getElementById('user-dropdown').classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        const menu = document.getElementById('admin-user-menu');
        if (menu && !menu.contains(e.target)) {
            document.getElementById('user-dropdown').classList.add('hidden');
        }
    });

    function toggleNotif() {
    document.getElementById('notif-dropdown').classList.toggle('hidden');
}

document.addEventListener('click', function(e) {
    const menu = document.getElementById('notif-menu');
    if (menu && !menu.contains(e.target)) {
        document.getElementById('notif-dropdown')?.classList.add('hidden');
    }
});
    </script>

    @stack('scripts')
</body>

</html>