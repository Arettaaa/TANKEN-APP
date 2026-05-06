<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TANKEN — Move With Style')</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- Bootstrap Icons CDN (Untuk ikon Keranjang Troli) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    {{-- FontAwesome CDN (Global) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        heading: ['Inter', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'tanken-black': '#0a0a0a',
                        'tanken-dark': '#111111',
                        'tanken-gray': '#1a1a1a',
                        'tanken-light': '#f5f5f5',
                        'tanken-muted': '#888888',
                        'tanken-accent': '#e8e8e8',
                    },
                }
            }
        }
    </script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: #111111;
            overflow-x: hidden;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 2px;
        }

        /* Navbar */
        #navbar {
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        #navbar.scrolled {
            background-color: rgba(255, 255, 255, 0.98) !important;
            box-shadow: 0 1px 12px rgba(0, 0, 0, 0.08);
        }

        /* Icon underline on hover */
        .nav-icon {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding-bottom: 3px;
            cursor: pointer;
            background: none;
            border: none;
            color: #111;
            transition: color 0.2s ease;
        }

        .nav-icon::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 1.5px;
            background: #111;
            transition: width 0.22s ease;
        }

        .nav-icon:hover::after {
            width: 100%;
        }

        .nav-icon:hover {
            color: #111;
        }

        /* Search expand (Desktop) */
        .search-wrapper {
            display: flex;
            align-items: center;
            position: relative;
        }

        .search-input-box {
            width: 0;
            opacity: 0;
            overflow: hidden;
            transition: width 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
            pointer-events: none;
        }

        .search-input-box.open {
            width: 180px;
            opacity: 1;
            pointer-events: all;
        }

        .search-input-box input {
            width: 100%;
            height: 32px;
            background: #f3f3f3;
            border: none;
            border-radius: 20px;
            padding: 0 14px;
            font-family: 'Inter', sans-serif;
            font-size: 0.8rem;
            color: #111;
            outline: none;
        }

        .search-input-box input::placeholder {
            color: #aaa;
        }

        /* Hover underline nav links */
        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: #111;
            transition: width 0.25s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* General Transitions */
        .product-card:hover .product-img {
            transform: scale(1.04);
        }

        .product-img {
            transition: transform 0.45s ease;
        }

        .collection-card:hover .collection-img {
            transform: scale(1.06);
        }

        .collection-img {
            transition: transform 0.5s ease;
        }

        /* Scroll reveal — global */
        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.55s ease, transform 0.55s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive Mobile Touches */
        @media (max-width: 768px) {
            .nav-icon {
                min-width: 36px;
                min-height: 36px;
            }
        }

        @media (max-width: 640px) {
            footer .flex.items-center.gap-2 input[type=email] {
                width: 100%;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="font-body flex flex-col min-h-screen">

    {{-- ====== NAVBAR ====== --}}
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-[100] bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-5 lg:px-10 relative">
            <div class="flex items-center justify-between h-14 md:h-16">

                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center flex-shrink-0">
                    <img src="{{ asset('images/logo-tanken.png') }}" alt="TANKEN" class="h-8 md:h-10 w-auto">
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-8">
                    {{-- Pengecekan route aman --}}
                    <a href="{{ Route::has('pelanggan.katalog') ? route('pelanggan.katalog') : url('/katalog') }}"
                        class="nav-link text-sm font-medium text-gray-800 hover:text-black">Shop</a>
                    <a href="{{ route('pelanggan.katalog', ['gender' => 'women']) }}"
                        class="nav-link text-sm font-medium text-gray-800 hover:text-black">Women</a>
                    <a href="{{ route('pelanggan.katalog', ['gender' => 'men']) }}"
                        class="nav-link text-sm font-medium text-gray-800 hover:text-black">Men</a>
                    <a href="{{ Route::has('help') ? route('help') : url('/help') }}"
                        class="nav-link text-sm font-medium text-gray-800 hover:text-black">Help</a>
                </div>

                {{-- Icons --}}
                <div class="flex items-center gap-2 md:gap-5">

                    {{-- Search expandable (Desktop) --}}
                    <div class="search-wrapper hidden md:flex items-center gap-2">
                        <div class="search-input-box" id="searchBox">
                            <input type="text" id="searchInput" placeholder="Cari produk...">
                        </div>
                        <button id="searchToggle" class="nav-icon" aria-label="Search">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8" width="18" height="18">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.35-4.35" />
                            </svg>
                        </button>
                    </div>
                    <a href="{{ Route::has('pelanggan.profil-wishlist') ? route('pelanggan.profil-wishlist') : url('/akun/wishlist') }}"
                        class="nav-icon relative hidden md:inline-flex" aria-label="Wishlist">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8" width="18" height="18">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                        </svg>
                        <span id="wishlist-badge-desktop"
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-[9px] font-bold min-w-[16px] h-4 px-1 rounded-full flex items-center justify-center  leading-none hidden">0</span>
                    </a>
                    <a href="{{ Route::has('pelanggan.keranjang.index') ? route('pelanggan.keranjang.index') : url('/keranjang') }}"
                        class="nav-icon relative" aria-label="Keranjang" id="cart-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8" width="18" height="18">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 0 1-8 0" />
                        </svg>
                        <span id="cart-badge"
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-[9px] font-bold min-w-[16px] h-4 px-1 rounded-full flex items-center justify-center leading-none hidden">0</span>
                    </a>

                    {{-- ===== LOGIKA AUTENTIKASI: DESKTOP ===== --}}
                    @guest
                    <a href="{{ Route::has('login') ? route('login') : url('/login') }}"
                        class="nav-icon hidden md:inline-flex items-center gap-1.5 text-sm font-medium text-gray-800"
                        style="padding-bottom:3px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8" width="17" height="17">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        <span>Masuk / Daftar</span>
                    </a>
                    @else
                    <div class="relative hidden md:flex items-center h-full group cursor-pointer">
                        <button class="nav-icon flex items-center gap-1.5 text-sm font-medium text-gray-800 h-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8" width="17" height="17">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </button>

                        {{-- Dropdown Akun Saya (Desktop) --}}
                        <div
                            class="absolute right-0 top-[100%] pt-2 w-44 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="bg-white border border-gray-100 rounded-md shadow-lg py-1 overflow-hidden">
                                <a href="{{ Route::has('pelanggan.profil-edit') ? route('pelanggan.profil-edit') : url('/akun/profil') }}"
                                    class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-black transition-colors">Akun
                                    Saya</a>
                                <a href="{{ Route::has('pelanggan.profil-order') ? route('pelanggan.profil-order') : url('/akun/pesanan') }}"
                                    class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-black transition-colors">Pesanan
                                    Saya</a>
                                <hr class="border-gray-100 my-1">
                                <form method="POST"
                                    action="{{ Route::has('logout') ? route('logout') : url('/logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">Keluar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endguest

                    {{-- ===== LOGIKA AUTENTIKASI: MOBILE ICON ===== --}}
                    @guest
                    <a href="{{ Route::has('login') ? route('login') : url('/login') }}" class="nav-icon md:hidden"
                        aria-label="Masuk">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8" width="18" height="18">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </a>
                    @else
                    <a href="{{ Route::has('pelanggan.profil-edit') ? route('pelanggan.profil-edit') : url('/akun/profil') }}"
                        class="nav-icon md:hidden" aria-label="Akun Saya">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8" width="18" height="18">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </a>
                    @endguest

                    {{-- Hamburger (Mobile) --}}
                    <button id="hamburger" class="md:hidden nav-icon ml-1" aria-label="Menu">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8" width="20" height="20">
                            <path d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- ===== MOBILE MENU ===== --}}
            <div id="mobile-menu"
                class="hidden md:hidden absolute left-0 right-0 top-[100%] bg-white border-t border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.08)] z-[90]">
                <div class="px-5 py-5 flex flex-col gap-4">

                    <div class="relative w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2" width="16" height="16"
                            class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.35-4.35" />
                        </svg>
                        <input type="text" id="searchInputMobile" placeholder="Cari produk..."
                            class="w-full bg-gray-50 border border-gray-200 rounded-full py-2.5 pl-9 pr-4 text-sm focus:outline-none focus:border-gray-400 transition-colors">
                    </div>

                    <a href="{{ Route::has('pelanggan.katalog') ? route('pelanggan.katalog') : url('/katalog') }}"
                        class="text-sm font-medium text-gray-800 hover:text-black py-1">Shop</a>
                    <a href="{{ Route::has('women') ? route('women') : url('/women') }}"
                        class="text-sm font-medium text-gray-800 hover:text-black py-1">Women</a>
                    <a href="{{ Route::has('men') ? route('men') : url('/men') }}"
                        class="text-sm font-medium text-gray-800 hover:text-black py-1">Men</a>
                    <a href="{{ Route::has('help') ? route('help') : url('/help') }}"
                        class="text-sm font-medium text-gray-800 hover:text-black py-1">Help</a>
                    <hr class="border-gray-100">

                    {{-- Wishlist Khusus Mobile --}}
                    <a href="{{ Route::has('pelanggan.profil-wishlist') ? route('pelanggan.profil-wishlist') : url('/akun/wishlist') }}"
                        class="flex items-center justify-between text-sm font-medium text-gray-800 hover:text-black py-1">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8" width="16" height="16">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                            </svg>
                            Wishlist
                        </div>
                        <span id="wishlist-badge-mobile"
                            class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full hidden">0</span>
                    </a>

                    {{-- ===== LOGIKA AUTENTIKASI: MOBILE MENU ===== --}}
                    @guest
                    <a href="{{ Route::has('login') ? route('login') : url('/login') }}"
                        class="flex items-center gap-2 text-sm font-medium text-gray-800 hover:text-black py-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8" width="16" height="16">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        Masuk / Daftar
                    </a>
                    @else
                    <a href="{{ Route::has('pelanggan.profil-edit') ? route('pelanggan.profil-edit') : url('/akun/profil') }}"
                        class="flex items-center gap-2 text-sm font-medium text-gray-800 hover:text-black py-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.8" width="16" height="16">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        Pengaturan Akun
                    </a>
                    <form method="POST" action="{{ Route::has('logout') ? route('logout') : url('/logout') }}"
                        class="mt-1 pb-2">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2 text-sm font-medium text-red-600 w-full text-left py-1 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8" width="16" height="16">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" y1="12" x2="9" y2="12" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                    @endguest

                </div>
            </div>
        </div>
    </nav>

    {{-- ====== MAIN CONTENT ====== --}}
    <main class="pt-[56px] md:pt-[64px] flex-grow">
        @yield('content')
    </main>

    {{-- ====== FOOTER ====== --}}
    <footer class="bg-tanken-dark text-white mt-auto">
        <div class="max-w-7xl mx-auto px-6 lg:px-10 py-10 md:py-14">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-8 md:gap-10">

                <div class="sm:col-span-2 lg:col-span-4">
                    <span class="text-xl font-extrabold tracking-widest text-white uppercase"
                        style="font-family:'Inter',sans-serif;">TANKEN</span>
                    <p class="mt-3 text-sm text-gray-400 leading-relaxed pr-0 md:pr-4">
                        Move with style. Premium athletic and casual pants designed for the modern lifestyle. Quality
                        you can feel, style you can see.
                    </p>
                    <div class="mt-5 flex flex-col gap-3 text-sm text-gray-400">
                        <div class="flex items-start gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.6" width="15" height="15"
                                class="mt-0.5 flex-shrink-0">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            <span>123 Fashion Street, New York, NY 10001</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.6" width="15" height="15">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.18 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.58a16 16 0 0 0 6 6l.95-1.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                            </svg>
                            <span>1-800-TANKEN-001</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.6" width="15" height="15">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                            <span>support@tanken.com</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 lg:col-start-6">
                    <h4 class="text-xs font-bold tracking-widest uppercase text-white mb-4"
                        style="font-family:'Inter',sans-serif;">Shop</h4>
                    <ul class="flex flex-col gap-2.5 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">All Products</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Women</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Men</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Under</a></li>
                    </ul>
                </div>

                <div class="lg:col-span-2">
                    <h4 class="text-xs font-bold tracking-widest uppercase text-white mb-4"
                        style="font-family:'Inter',sans-serif;">Help</h4>
                    <ul class="flex flex-col gap-2.5 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">My Orders</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Shipping Info</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Returns</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Size Guide</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>

                <div class="lg:col-span-2">
                    <h4 class="text-xs font-bold tracking-widest uppercase text-white mb-4"
                        style="font-family:'Inter',sans-serif;">Company</h4>
                    <ul class="flex flex-col gap-2.5 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Sustainability</a></li>
                    </ul>
                </div>
            </div>

            <div
                class="mt-10 pt-8 border-t border-gray-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-8 md:gap-6">
                <div class="w-full md:w-auto">
                    <p class="text-xs text-gray-400 mb-3 uppercase tracking-widest font-semibold">Follow us</p>
                    <div class="flex items-center gap-3">
                        <a href="#"
                            class="w-10 h-10 md:w-9 md:h-9 rounded-full border border-gray-600 flex items-center justify-center text-gray-400 hover:border-white hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.6" width="16" height="16">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                                <circle cx="12" cy="12" r="4" />
                                <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor" stroke="none" />
                            </svg>
                        </a>
                        <a href="#"
                            class="w-10 h-10 md:w-9 md:h-9 rounded-full border border-gray-600 flex items-center justify-center text-gray-400 hover:border-white hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="16"
                                height="16">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="w-10 h-10 md:w-9 md:h-9 rounded-full border border-gray-600 flex items-center justify-center text-gray-400 hover:border-white hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.6" width="16" height="16">
                                <path
                                    d="M23 3a10.9 10.9 0 0 1-3.14 1.53A4.48 4.48 0 0 0 22.43 1a9 9 0 0 1-2.88 1.1A4.52 4.52 0 0 0 16.11 0c-2.5 0-4.52 2.02-4.52 4.52 0 .35.04.7.11 1.03C7.69 5.37 4.07 3.58 1.64.9a4.52 4.52 0 0 0-.61 2.27c0 1.57.8 2.95 2.01 3.76a4.5 4.5 0 0 1-2.05-.57v.06c0 2.19 1.56 4.02 3.63 4.43a4.54 4.54 0 0 1-2.04.08 4.53 4.53 0 0 0 4.22 3.14A9.07 9.07 0 0 1 0 19.54a12.8 12.8 0 0 0 6.92 2.03c8.3 0 12.85-6.88 12.85-12.85 0-.2 0-.39-.01-.58A9.17 9.17 0 0 0 23 3z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="w-full md:w-auto">
                    <p class="text-xs text-gray-400 mb-3 uppercase tracking-widest font-semibold">Subscribe to our
                        newsletter</p>
                    <div class="flex items-center gap-2 w-full">
                        <input type="email" placeholder="Enter your email"
                            class="bg-transparent border border-gray-600 rounded-full px-4 py-2.5 md:py-2 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-gray-400 w-full md:w-56 transition-colors">
                        <button
                            class="bg-white text-black text-sm font-semibold px-5 py-2.5 md:py-2 rounded-full hover:bg-gray-200 transition-colors whitespace-nowrap">Subscribe</button>
                    </div>
                </div>
            </div>

            <div
                class="mt-8 pt-6 border-t border-gray-800 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500">
                <span>© 2026 TANKEN. All rights reserved.</span>
                <div class="flex flex-wrap items-center justify-center gap-4 md:gap-6">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-white transition-colors">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Script Navigasi --}}
    <script>
        const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 10);
    });

    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobile-menu');
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    const searchToggle = document.getElementById('searchToggle');
    const searchBox    = document.getElementById('searchBox');
    const searchInput  = document.getElementById('searchInput');

    if (searchToggle && searchBox && searchInput) {
        let searchOpen = false;

        searchToggle.addEventListener('click', () => {
            searchOpen = !searchOpen;
            if (searchOpen) {
                searchBox.classList.add('open');
                setTimeout(() => searchInput.focus(), 50);
            } else {
                searchBox.classList.remove('open');
                searchInput.value = '';
            }
        });

        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && searchInput.value.trim() !== '') {
                window.location.href = '{{ route("pelanggan.katalog") }}?search=' + encodeURIComponent(searchInput.value.trim());
            }
        });

        document.addEventListener('click', (e) => {
            if (searchOpen && !searchToggle.contains(e.target) && !searchBox.contains(e.target)) {
                searchOpen = false;
                searchBox.classList.remove('open');
                searchInput.value = '';
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && searchOpen) {
                searchOpen = false;
                searchBox.classList.remove('open');
                searchInput.value = '';
            }
        });
    }

    // Search Mobile
    const searchInputMobile = document.getElementById('searchInputMobile');
    if (searchInputMobile) {
        searchInputMobile.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && searchInputMobile.value.trim() !== '') {
                window.location.href = '{{ route("pelanggan.katalog") }}?search=' + encodeURIComponent(searchInputMobile.value.trim());
            }
        });
    }
    </script>

    <script>
        function updateCartBadge(count) {
        const badge = document.getElementById('cart-badge');
        if (!badge) return;
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    function updateWishlistBadge(count) {
        const badgeDesktop = document.getElementById('wishlist-badge-desktop');
        const badgeMobile  = document.getElementById('wishlist-badge-mobile');
        [badgeDesktop, badgeMobile].forEach(badge => {
            if (!badge) return;
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        @auth
        updateCartBadge({{ \App\Models\CartItem::where('user_id', auth()->id())->count() }});
        updateWishlistBadge({{ auth()->user()->wishlists()->count() }});
        @else
        updateCartBadge(0);
        updateWishlistBadge(0);
        @endauth
    });

    window.addEventListener('wishlistUpdated', (e) => updateWishlistBadge(e.detail?.count ?? 0));
    </script>

    @auth
    <script>
        // Inject jumlah wishlist dari server supaya badge langsung muncul
    document.addEventListener('DOMContentLoaded', () => {
        const count = {{ auth()->user()->wishlists()->count() }};
        const badgeDesktop = document.getElementById('wishlist-badge-desktop');
        const badgeMobile  = document.getElementById('wishlist-badge-mobile');
        [badgeDesktop, badgeMobile].forEach(badge => {
            if (!badge) return;
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.classList.remove('hidden');
            }
        });
    });
    </script>
    @endauth

    @stack('scripts')
</body>

</html>