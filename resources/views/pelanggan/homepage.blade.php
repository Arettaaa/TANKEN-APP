@extends('layouts.main')

@section('title', 'TANKEN — Define Your Motion')

@push('styles')
<style>
    /* Hero */
    .hero-section {
        position: relative;
        min-height: 92vh;
        display: flex;
        align-items: center;
        overflow: hidden;
    }
    .hero-bg {
        position: absolute;
        inset: 0;
        background-image: url('{{ asset("images/Nature-1.jpg") }}');
        background-size: cover;
        background-position: center 20%;
        filter: brightness(0.45);
    }
    .hero-section:hover .hero-bg {
        /* no zoom animation */
    }

    /* Hero heading */
    .hero-heading {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        /* Ubah baris ini: ukuran minimal 2.5rem, dinamis 5vw, maksimal 4.5rem */
        font-size: clamp(2.5rem, 5vw, 4.5rem); 
        line-height: 1.1; /* Agak dilonggarkan sedikit biar nggak terlalu numpuk */
        letter-spacing: -0.02em;
        text-transform: uppercase;
        color: #ffffff;
        text-shadow: 0 4px 24px rgba(0,0,0,0.4); /* Shadow disesuaikan biar teks lebih terbaca */
    }

    /* Collection grid */
    .collection-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    /* Featured card overlay */
    .collection-card {
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    .collection-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.1) 50%, transparent 100%);
    }
    .collection-card:hover .collection-overlay {
        background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.15) 50%, transparent 100%);
    }

    /* Ticker */
    .ticker-wrap {
        overflow: hidden;
        background: #111111;
        padding: 10px 0;
    }
    .ticker-content {
        display: inline-block;
        animation: ticker 30s linear infinite;
        white-space: nowrap;
    }
    .ticker-content span {
        display: inline-block;
        padding: 0 2rem;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        font-size: 0.65rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: #aaaaaa;
    }
    .ticker-content span.dot {
        color: #555;
        padding: 0 0.5rem;
    }
    @keyframes ticker {
        0%   { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    /* Product card */
    .product-card {
        cursor: pointer;
    }
    .product-img-wrap {
        overflow: hidden;
        background: #f0efed;
        aspect-ratio: 3/4;
    }
    .product-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.45s ease;
    }
    .product-card:hover .product-img-wrap img {
        transform: scale(1.05);
    }

    /* Stars */
    .star-filled { color: #f5a623; }
    .star-empty  { color: #ddd; }

    /* Stat card */
    .stat-number {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        font-size: clamp(2rem, 4vw, 2.8rem);
        letter-spacing: -0.02em;
        color: #111;
    }

    /* Voucher card */
    .voucher-card {
        border: 1.5px dashed #d0d0d0;
        border-radius: 8px;
        position: relative;
        overflow: hidden;
    }
    .voucher-card::before {
        content: '';
        position: absolute;
        left: -1px;
        top: 50%;
        transform: translateY(-50%);
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #f5f5f5;
        border: 1.5px dashed #d0d0d0;
    }
    .voucher-card::after {
        content: '';
        position: absolute;
        right: -1px;
        top: 50%;
        transform: translateY(-50%);
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #f5f5f5;
        border: 1.5px dashed #d0d0d0;
    }
    .voucher-discount {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        font-size: 1.8rem;
        letter-spacing: -0.02em;
        line-height: 1;
    }
    .code-box {
        background: #f0f0f0;
        border: 1px dashed #bbb;
        border-radius: 4px;
        padding: 6px 10px;
        font-family: 'Inter', monospace;
        font-weight: 700;
        font-size: 0.9rem;
        letter-spacing: 0.1em;
        color: #333;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }
    .copy-btn {
        cursor: pointer;
        background: #111;
        color: #fff;
        border: none;
        border-radius: 3px;
        padding: 3px 8px;
        font-size: 0.65rem;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        transition: background 0.2s;
    }
    .copy-btn:hover { background: #333; }
    .copy-btn:active { transform: scale(0.95); }

    /* Spring collection dark section */
    .spring-section {
        position: relative;
        overflow: hidden;
        background: #0a0a0a;
        min-height: 480px;
        display: flex;
        align-items: center;
    }
    .spring-bg {
        position: absolute;
        inset: 0;
        opacity: 0.25;
        background-image: url('{{ asset("images/men-home3.jpg") }}');
        background-size: cover;
        background-position: center;
    }
    .spring-heading {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        font-size: clamp(3rem, 7vw, 5.5rem);
        line-height: 0.9;
        letter-spacing: -0.03em;
        text-transform: uppercase;
        color: #fff;
        text-align: left;
    }

    /* Why section icon */
    .why-icon {
        width: 44px;
        height: 44px;
        border: 1.5px solid #ddd;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
    }

</style>
@endpush

@section('content')

{{-- ===== 1. HERO ===== --}}
<section class="hero-section">
    <div class="hero-bg"></div>

    {{-- Overlay gradient bottom --}}
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black/30 pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 w-full">
        <div class="max-w-2xl">
            <h1 class="hero-heading mb-6 max-w-lg">
                Comfort To<br>Explore.
            </h1>
            <div class="flex flex-wrap items-center gap-3">
                {{-- Shop Now --}}
                <a href="{{ route('pelanggan.katalog') }}"
                   class="inline-flex items-center gap-2 bg-white text-black px-6 py-3 text-xs font-heading font-bold tracking-widest uppercase hover:bg-gray-100 transition-colors">
                    Shop Now
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="12" height="12">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
                {{-- Women --}}
               <a href="{{ route('pelanggan.katalog', ['gender' => 'women']) }}"
                   class="inline-flex items-center gap-1.5 text-white text-xs font-bold tracking-widest uppercase hover:text-white/70 transition-colors">
                    Women
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="11" height="11">
                        <path d="M7 17L17 7M17 7H7M17 7v10"/>
                    </svg>
                </a>
                {{-- Men --}}
               <a href="{{ route('pelanggan.katalog', ['gender' => 'men']) }}" 
                   class="inline-flex items-center gap-1.5 text-white text-xs font-bold tracking-widest uppercase hover:text-white/70 transition-colors">
                    Men
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="11" height="11">
                        <path d="M7 17L17 7M17 7H7M17 7v10"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

<div class="ticker-wrap">
    <div class="ticker-content">
        @php
            $tickers = ['Free Shipping Over Rp500K', 'Premium Quality', 'Move With Style', 'Engineered Comfort', 'New Collection', 'Spring 2026', 'Free Shipping Over Rp500K', 'Premium Quality', 'Move With Style', 'Engineered Comfort', 'New Collection', 'Spring 2026'];
        @endphp
        @foreach($tickers as $item)
            <span>{{ $item }}</span><span class="dot">·</span>
        @endforeach
        @foreach($tickers as $item)
            <span>{{ $item }}</span><span class="dot">·</span>
        @endforeach
    </div>
</div>

<section class="collection-grid">
    <div class="collection-card h-[520px] md:h-[620px]">
        <img src="{{ asset('images/women-home.jpg') }}"
             alt="Women Collection"
             class="collection-img w-full h-full object-cover object-center">
        <div class="collection-overlay"></div>
        <div class="absolute bottom-0 left-0 p-8 md:p-10">
            <h2 class="font-heading font-bold text-white uppercase tracking-wide"
                style="font-size:clamp(2rem,4vw,3rem); line-height:1;">
                Women
            </h2>
           <a href="{{ route('pelanggan.katalog', ['gender' => 'women']) }}"
               class="btn-arrow mt-3 text-white/80 hover:text-white inline-flex items-center gap-1.5">
                Shop Collection
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="11" height="11">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

    {{-- Men --}}
    <div class="collection-card h-[520px] md:h-[620px]">
        <img src="{{ asset('images/men-home.jpg') }}"
             alt="Men Collection"
             class="collection-img w-full h-full object-cover object-center">
        <div class="collection-overlay"></div>
        <div class="absolute bottom-0 left-0 p-8 md:p-10">
            <h2 class="font-heading font-bold text-white uppercase tracking-wide"
                style="font-size:clamp(2rem,4vw,3rem); line-height:1;">
                Men
            </h2>
            <a href="{{ route('pelanggan.katalog', ['gender' => 'men']) }}"
               class="btn-arrow mt-3 text-white/80 hover:text-white inline-flex items-center gap-1.5">
                Shop Collection
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="11" height="11">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>

<section class="bg-white py-16 md:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        {{-- Header --}}
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-xs font-heading font-semibold tracking-widest uppercase text-gray-400 mb-1">New In</p>
                <h2 class="font-heading font-bold text-3xl md:text-4xl tracking-tight text-gray-900">Featured Pieces</h2>
            </div>
            <a href="{{ route('pelanggan.katalog') }}" class="btn-arrow text-gray-500 hover:text-black hidden md:inline-flex items-center gap-1.5">
                View All
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="11" height="11">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        {{-- Products grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">

            @php
            $products = [
                [
                    'img'   => 'women-home2.jpg',
                    'name'  => 'Classic Cargo Pants',
                    'cat'   => 'Women',
                    'price' => 'Rp 85.000',
                    'rating'=> 4.8,
                ],
                [
                    'img'   => 'men-home2.jpg',
                    'name'  => 'Sport Active Joggers',
                    'cat'   => 'Men',
                    'price' => 'Rp 129.000',
                    'rating'=> 4.9,
                ],
                [
                    'img'   => 'women-home3.jpg',
                    'name'  => 'Formal Office Trousers',
                    'cat'   => 'Women',
                    'price' => 'Rp 85.000',
                    'rating'=> 4.7,
                ],
                [
                    'img'   => 'men-home3.jpg',
                    'name'  => 'Casual Everyday Pants',
                    'cat'   => 'Men',
                    'price' => 'Rp 129.000',
                    'rating'=> 4.8,
                ],
            ];
            @endphp

            @foreach($products as $product)
            <div class="product-card reveal">
                <div class="product-img-wrap rounded-sm mb-3">
                    <img src="{{ asset('images/' . $product['img']) }}"
                         alt="{{ $product['name'] }}"
                         loading="lazy">
                </div>
                <div class="px-0.5">
                    <p class="text-[0.68rem] font-heading font-semibold tracking-widest uppercase text-gray-400 mb-0.5">{{ $product['cat'] }}</p>
                    <h3 class="text-sm font-body font-medium text-gray-900 leading-snug mb-1">{{ $product['name'] }}</h3>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-body font-semibold text-gray-900">{{ $product['price'] }}</span>
                        <div class="flex items-center gap-0.5">
                            <svg class="star-filled" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="11" height="11">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            <span class="text-xs font-body font-medium text-gray-600 ml-0.5">{{ $product['rating'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Mobile view all --}}
        <div class="flex justify-center mt-8 md:hidden">
            <a href="{{ route('pelanggan.katalog') }}" class="btn-arrow text-gray-600 hover:text-black border border-gray-300 px-6 py-2.5 rounded-sm inline-flex items-center gap-1.5">
                View All
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="11" height="11">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- ===== 5. STATS BAR ===== --}}
<!-- <section class="bg-white border-y border-gray-100 py-10">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-0 divide-y md:divide-y-0 md:divide-x divide-gray-100">
            @php
            $stats = [
                ['num'=>'50K+',  'label'=>'Happy Customers'],
                ['num'=>'4.9',   'label'=>'Average Rating'],
                ['num'=>'100%',  'label'=>'Premium Quality'],
                ['num'=>'24/7',  'label'=>'Customer Support'],
            ];
            @endphp
            @foreach($stats as $stat)
            <div class="text-center py-4 md:py-0 reveal">
                <div class="stat-number">{{ $stat['num'] }}</div>
                <p class="text-xs font-heading font-semibold tracking-widest uppercase text-gray-400 mt-1">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section> -->

{{-- ===== 6. EXCLUSIVE VOUCHERS ===== --}}
<section class="bg-gray-50 py-16 md:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-xs font-heading font-semibold tracking-widest uppercase text-gray-400 mb-1">Save More</p>
                <h2 class="font-heading font-bold text-3xl md:text-4xl tracking-tight text-gray-900">Exclusive Vouchers</h2>
            </div>
            <p class="text-xs text-gray-400 font-body hidden md:block">Apply at checkout for instant savings</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            {{-- Voucher 1: 20% Off --}}
            <div class="voucher-card bg-white p-7 reveal">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <span class="voucher-discount">20% OFF</span>
                        <p class="text-xs font-body text-gray-500 mt-1 leading-relaxed">
                            For new customers on their first order above Rp500K
                        </p>
                    </div>
                    <div class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" width="16" height="16">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>
                        </svg>
                    </div>
                </div>
                <hr class="border-dashed border-gray-200 my-4">
                <p class="text-[0.65rem] font-heading font-semibold tracking-widest uppercase text-gray-400 mb-2">Promo Code</p>
                <div class="code-box">
                    <span>WELCOME20</span>
                    <button class="copy-btn" onclick="copyCode(this, 'WELCOME20')">COPY</button>
                </div>
                <p class="text-[0.6rem] text-gray-400 mt-2 font-body">Valid until Dec 31, 2026. T&C apply.</p>
            </div>

            {{-- Voucher 2: Free Ship --}}
            <div class="voucher-card bg-white p-7 reveal">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <span class="voucher-discount">FREE SHIP</span>
                        <p class="text-xs font-body text-gray-500 mt-1 leading-relaxed">
                            Free shipping on all orders over Rp500K, no minimum
                        </p>
                    </div>
                    <div class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" width="16" height="16">
                            <rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                        </svg>
                    </div>
                </div>
                <hr class="border-dashed border-gray-200 my-4">
                <p class="text-[0.65rem] font-heading font-semibold tracking-widest uppercase text-gray-400 mb-2">Promo Code</p>
                <div class="code-box">
                    <span>FREESHIP100</span>
                    <button class="copy-btn" onclick="copyCode(this, 'FREESHIP100')">COPY</button>
                </div>
                <p class="text-[0.6rem] text-gray-400 mt-2 font-body">Valid until Dec 31, 2026. T&C apply.</p>
            </div>

            {{-- Voucher 3: 30% Off --}}
            <div class="voucher-card bg-white p-7 reveal">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <span class="voucher-discount">30% OFF</span>
                        <p class="text-xs font-body text-gray-500 mt-1 leading-relaxed">
                            Valid on selected Spring Collection 2026 items
                        </p>
                    </div>
                    <div class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" width="16" height="16">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>
                        </svg>
                    </div>
                </div>
                <hr class="border-dashed border-gray-200 my-4">
                <p class="text-[0.65rem] font-heading font-semibold tracking-widest uppercase text-gray-400 mb-2">Promo Code</p>
                <div class="code-box">
                    <span>SPRING30</span>
                    <button class="copy-btn" onclick="copyCode(this, 'SPRING30')">COPY</button>
                </div>
                <p class="text-[0.6rem] text-gray-400 mt-2 font-body">Valid until Jun 30, 2026. T&C apply.</p>
            </div>

        </div>
    </div>
</section>

{{-- ===== 7. SPRING COLLECTION 2026 DARK BANNER ===== --}}
<section class="spring-section">
    <div class="spring-bg"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 py-20 w-full">
        <p class="text-xs font-semibold tracking-widest uppercase text-white/40 mb-4">Limited Edition</p>
        <h2 class="spring-heading mb-4 text-left">
            Spring<br>Collection<br>2026
        </h2>
        <p class="text-sm text-white/60 max-w-sm leading-relaxed mb-8">
            New styles, same premium quality. Up to 30% off select pieces for a limited time.
        </p>
        <a href="#"
           class="inline-flex items-center gap-2 border border-white text-white px-7 py-3 text-xs font-heading font-bold tracking-widest uppercase hover:bg-white hover:text-black transition-colors">
            Shop Collection
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="12" height="12">
                <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</section>

{{-- ===== 8. WHY TANKEN ===== --}}
<section class="bg-white py-16 md:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        <div class="mb-12 reveal">
            <p class="text-xs font-semibold tracking-widest uppercase text-gray-400 mb-2">Our Promise</p>
            <h2 class="font-extrabold text-3xl md:text-4xl text-gray-900">Why TANKEN</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-200">

            {{-- Feature 1 --}}
            <div class="reveal pb-8 md:pb-0 md:pr-10">
                <div class="why-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" width="20" height="20" class="text-gray-700">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-sm text-gray-900 mb-2">Premium Materials</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Carefully selected fabrics engineered for durability and comfort, crafted from the finest materials available.
                </p>
            </div>

            {{-- Feature 2 --}}
            <div class="reveal pt-8 pb-8 md:pt-0 md:pb-0 md:px-10">
                <div class="why-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" width="20" height="20" class="text-gray-700">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                </div>
                <h3 class="font-bold text-sm text-gray-900 mb-2">Engineered Comfort</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Advanced construction techniques for maximum mobility. Move freely without restrictions, all day long.
                </p>
            </div>

            {{-- Feature 3 --}}
            <div class="reveal pt-8 md:pt-0 md:pl-10">
                <div class="why-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" width="20" height="20" class="text-gray-700">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-sm text-gray-900 mb-2">Modern Fit Technology</h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Precision-tailored designs that adapt to your movement. Perfect fit that looks great and feels even better.
                </p>
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Copy voucher code
    function copyCode(btn, code) {
        navigator.clipboard.writeText(code).then(() => {
            const orig = btn.textContent;
            btn.textContent = 'COPIED!';
            btn.style.background = '#2d7a3a';
            setTimeout(() => {
                btn.textContent = orig;
                btn.style.background = '';
            }, 1800);
        }).catch(() => {
            // Fallback
            const el = document.createElement('textarea');
            el.value = code;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            const orig = btn.textContent;
            btn.textContent = 'COPIED!';
            btn.style.background = '#2d7a3a';
            setTimeout(() => {
                btn.textContent = orig;
                btn.style.background = '';
            }, 1800);
        });
    }

    // Scroll reveal
    const revealEls = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, i * 80);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    revealEls.forEach(el => observer.observe(el));
</script>
@endpush