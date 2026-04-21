@extends('layouts.main')

@section('title', 'Shop — TANKEN')

@section('content')

{{-- ====== HERO BANNER ====== --}}
<section class="relative w-full h-56 md:h-72 overflow-hidden bg-black">
    <img
        src="{{ asset('images/men-home.jpg') }}"
        alt="Shop Banner"
        class="absolute inset-0 w-full h-full object-cover object-top opacity-50"
    >
    <div class="relative z-10 h-full flex flex-col justify-end px-8 md:px-12 pb-8">
        <p class="text-xs text-gray-300 uppercase tracking-widest mb-1 font-medium">All Collections</p>
        <h1 class="text-5xl md:text-6xl font-extrabold text-white leading-none tracking-tight">Shop</h1>
        <p class="text-xs text-gray-400 mt-2" id="result-count">8 pieces found</p>
    </div>
</section>

{{-- ====== SEARCH BAR ====== --}}
<div class="border-b border-gray-200 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-3">
        <div class="flex items-center gap-3 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" width="16" height="16">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input
                id="search-input"
                type="text"
                placeholder="Cari produk..."
                class="w-full text-sm text-gray-700 placeholder-gray-400 focus:outline-none bg-transparent py-1"
                oninput="filterProducts()"
            >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" width="16" height="16">
                <line x1="21" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="9" y2="14"/><line x1="21" y1="18" x2="9" y2="18"/>
            </svg>
        </div>
    </div>
</div>

{{-- ====== MAIN CATALOG AREA ====== --}}
<div class="max-w-7xl mx-auto px-6 lg:px-10 py-10">
    <div class="flex gap-10">

        {{-- ====== SIDEBAR FILTER ====== --}}
        <aside class="hidden md:block w-44 flex-shrink-0">

            {{-- Gender --}}
            <div class="mb-7">
                <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-3">Gender</p>
                <div class="flex flex-col gap-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" value="all" class="filter-gender accent-black w-3.5 h-3.5 cursor-pointer" checked onchange="filterProducts()">
                        <span class="text-sm text-gray-800 font-medium group-hover:text-black">Semua</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" value="women" class="filter-gender accent-black w-3.5 h-3.5 cursor-pointer" onchange="filterProducts()">
                        <span class="text-sm text-gray-600 group-hover:text-black">Wanita</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" value="men" class="filter-gender accent-black w-3.5 h-3.5 cursor-pointer" onchange="filterProducts()">
                        <span class="text-sm text-gray-600 group-hover:text-black">Pria</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" value="unisex" class="filter-gender accent-black w-3.5 h-3.5 cursor-pointer" onchange="filterProducts()">
                        <span class="text-sm text-gray-600 group-hover:text-black">Unisex</span>
                    </label>
                </div>
            </div>

            {{-- Tipe --}}
            <div class="mb-7">
                <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-3">Tipe</p>
                <div class="flex flex-col gap-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" value="all" class="filter-style accent-black w-3.5 h-3.5 cursor-pointer" checked onchange="filterProducts()">
                        <span class="text-sm text-gray-800 font-medium group-hover:text-black">Semua</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" value="panjang" class="filter-style accent-black w-3.5 h-3.5 cursor-pointer" onchange="filterProducts()">
                        <span class="text-sm text-gray-600 group-hover:text-black">Celana Panjang</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" value="pendek" class="filter-style accent-black w-3.5 h-3.5 cursor-pointer" onchange="filterProducts()">
                        <span class="text-sm text-gray-600 group-hover:text-black">Celana Pendek</span>
                    </label>
                </div>
            </div>

            {{-- Harga --}}
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-3">Harga</p>
                <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                    <span>Rp0</span>
                    <span id="price-max-label">Rp2.000.000</span>
                </div>
                <input
                    type="range"
                    id="price-range"
                    min="0" max="2000000" value="2000000" step="50000"
                    class="w-full accent-black cursor-pointer"
                    oninput="updatePriceLabel(this.value); filterProducts();"
                >
                <div class="flex items-center gap-2 mt-3">
                    <input type="number" value="0" min="0" max="2000000" placeholder="0"
                        class="w-full border border-gray-200 rounded px-2 py-1 text-xs text-gray-700 focus:outline-none focus:border-gray-400">
                    <span class="text-gray-400 text-xs">—</span>
                    <input type="number" id="price-input-max" value="2000000" min="0" max="2000000" placeholder="2000000"
                        class="w-full border border-gray-200 rounded px-2 py-1 text-xs text-gray-700 focus:outline-none focus:border-gray-400"
                        oninput="document.getElementById('price-range').value=this.value; updatePriceLabel(this.value); filterProducts();">
                </div>
            </div>
        </aside>

        {{-- ====== PRODUCT GRID ====== --}}
        <div class="flex-1">
            <div id="product-grid" class="grid grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-10">

                {{-- Product Card 1 --}}
                <div class="product-card group cursor-pointer"
                     data-name="Classic Cargo Pants"
                     data-gender="men"
                     data-style="panjang"
                     data-price="1399000">
                    <a href="{{ route('pelanggan.produk.detail', 'classic-cargo-pants') }}">
                        <div class="overflow-hidden rounded-lg bg-gray-100 aspect-[3/4] mb-3">
                            <img src="{{ asset('images/men-home.jpg') }}"
                                 alt="Classic Cargo Pants"
                                 class="product-img w-full h-full object-cover object-top">
                        </div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-0.5">Celana Panjang</p>
                        <h3 class="text-sm font-semibold text-gray-900 leading-snug">Classic Cargo Pants</h3>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-sm font-bold text-gray-900">Rp1.399.000</span>
                            <span class="flex items-center gap-1 text-xs text-gray-500">
                                <svg class="star" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="12" height="12"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                4.8
                            </span>
                        </div>
                    </a>
                </div>

                {{-- Product Card 2 --}}
                <div class="product-card group cursor-pointer"
                     data-name="Yama Crinkle Nylon Boardshorts"
                     data-gender="unisex"
                     data-style="pendek"
                     data-price="1249000">
                    <a href="{{ route('pelanggan.produk.detail', 'yama-crinkle-nylon-boardshorts') }}">
                        <div class="overflow-hidden rounded-lg bg-gray-100 aspect-[3/4] mb-3 relative">
                            <img src="{{ asset('images/men-home2.jpg') }}"
                                 alt="Yama Crinkle Nylon Boardshorts"
                                 class="product-img w-full h-full object-cover object-top">
                            <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded uppercase tracking-wide">Sale</span>
                        </div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-0.5">Celana Pendek</p>
                        <h3 class="text-sm font-semibold text-gray-900 leading-snug">Yama Crinkle Nylon Boardshorts</h3>
                        <div class="flex items-center justify-between mt-1">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-900">Rp1.249.000</span>
                                <span class="text-xs text-gray-400 line-through">Rp1.499.000</span>
                                <span class="text-xs bg-red-100 text-red-600 font-semibold px-1.5 py-0.5 rounded">17%</span>
                            </div>
                            <span class="flex items-center gap-1 text-xs text-gray-500">
                                <svg class="star" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="12" height="12"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                4.9
                            </span>
                        </div>
                    </a>
                </div>

                {{-- Product Card 3 --}}
                <div class="product-card group cursor-pointer"
                     data-name="Formal Office Trousers"
                     data-gender="men"
                     data-style="panjang"
                     data-price="1599000">
                    <a href="{{ route('pelanggan.produk.detail', 'formal-office-trousers') }}">
                        <div class="overflow-hidden rounded-lg bg-gray-100 aspect-[3/4] mb-3">
                            <img src="{{ asset('images/men-home3.jpg') }}"
                                 alt="Formal Office Trousers"
                                 class="product-img w-full h-full object-cover object-top">
                        </div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-0.5">Celana Panjang</p>
                        <h3 class="text-sm font-semibold text-gray-900 leading-snug">Formal Office Trousers</h3>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-sm font-bold text-gray-900">Rp1.599.000</span>
                            <span class="flex items-center gap-1 text-xs text-gray-500">
                                <svg class="star" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="12" height="12"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                4.7
                            </span>
                        </div>
                    </a>
                </div>

                {{-- Product Card 4 --}}
                <div class="product-card group cursor-pointer"
                     data-name="Casual Everyday Pants"
                     data-gender="women"
                     data-style="pendek"
                     data-price="1099000">
                    <a href="{{ route('pelanggan.produk.detail', 'casual-everyday-pants') }}">
                        <div class="overflow-hidden rounded-lg bg-gray-100 aspect-[3/4] mb-3">
                            <img src="{{ asset('images/women-home.jpg') }}"
                                 alt="Casual Everyday Pants"
                                 class="product-img w-full h-full object-cover object-top">
                        </div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-0.5">Celana Pendek</p>
                        <h3 class="text-sm font-semibold text-gray-900 leading-snug">Casual Everyday Pants</h3>
                        <div class="flex items-center justify-between mt-1">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-900">Rp1.099.000</span>
                                <span class="text-xs text-gray-400 line-through">Rp1.349.000</span>
                            </div>
                            <span class="flex items-center gap-1 text-xs text-gray-500">
                                <svg class="star" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="12" height="12"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                4.6
                            </span>
                        </div>
                    </a>
                </div>

                {{-- Product Card 5 --}}
                <div class="product-card group cursor-pointer"
                     data-name="Athletic Performance Pants"
                     data-gender="women"
                     data-style="pendek"
                     data-price="1499000">
                    <a href="{{ route('pelanggan.produk.detail', 'athletic-performance-pants') }}">
                        <div class="overflow-hidden rounded-lg bg-gray-100 aspect-[3/4] mb-3">
                            <img src="{{ asset('images/women-home2.jpg') }}"
                                 alt="Athletic Performance Pants"
                                 class="product-img w-full h-full object-cover object-top">
                        </div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-0.5">Celana Pendek</p>
                        <h3 class="text-sm font-semibold text-gray-900 leading-snug">Athletic Performance Pants</h3>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-sm font-bold text-gray-900">Rp1.499.000</span>
                            <span class="flex items-center gap-1 text-xs text-gray-500">
                                <svg class="star" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="12" height="12"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                4.9
                            </span>
                        </div>
                    </a>
                </div>

                {{-- Product Card 6 --}}
                <div class="product-card group cursor-pointer"
                     data-name="Relaxed Fit Joggers"
                     data-gender="unisex"
                     data-style="panjang"
                     data-price="1199000">
                    <a href="{{ route('pelanggan.produk.detail', 'relaxed-fit-joggers') }}">
                        <div class="overflow-hidden rounded-lg bg-gray-100 aspect-[3/4] mb-3">
                            <img src="{{ asset('images/women-home3.jpg') }}"
                                 alt="Relaxed Fit Joggers"
                                 class="product-img w-full h-full object-cover object-top">
                        </div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-0.5">Celana Panjang</p>
                        <h3 class="text-sm font-semibold text-gray-900 leading-snug">Relaxed Fit Joggers</h3>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-sm font-bold text-gray-900">Rp1.199.000</span>
                            <span class="flex items-center gap-1 text-xs text-gray-500">
                                <svg class="star" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="12" height="12"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                4.5
                            </span>
                        </div>
                    </a>
                </div>

                {{-- Product Card 8 --}}
                <div class="product-card group cursor-pointer"
                     data-name="Urban Slim Chinos"
                     data-gender="men"
                     data-style="panjang"
                     data-price="1349000">
                    <a href="{{ route('pelanggan.produk.detail', 'urban-slim-chinos') }}">
                        <div class="overflow-hidden rounded-lg bg-gray-100 aspect-[3/4] mb-3">
                            <img src="{{ asset('images/men-home2.jpg') }}"
                                 alt="Urban Slim Chinos"
                                 class="product-img w-full h-full object-cover object-top">
                        </div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-0.5">Celana Panjang</p>
                        <h3 class="text-sm font-semibold text-gray-900 leading-snug">Urban Slim Chinos</h3>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-sm font-bold text-gray-900">Rp1.349.000</span>
                            <span class="flex items-center gap-1 text-xs text-gray-500">
                                <svg class="star" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="12" height="12"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                4.6
                            </span>
                        </div>
                    </a>
                </div>

            </div>

            {{-- Empty state --}}
            <div id="empty-state" class="hidden text-center py-20 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2" width="40" height="40" class="mx-auto mb-4 opacity-40">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <p class="text-sm font-medium">Produk tidak ditemukan</p>
                <p class="text-xs mt-1">Coba ubah filter pencarian kamu</p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function updatePriceLabel(val) {
        const formatted = 'Rp' + parseInt(val).toLocaleString('id-ID');
        document.getElementById('price-max-label').textContent = formatted;
        document.getElementById('price-input-max').value = val;
    }

    function filterProducts() {
        const search   = document.getElementById('search-input').value.toLowerCase();
        const maxPrice = parseFloat(document.getElementById('price-range').value);

        const genderBoxes = [...document.querySelectorAll('.filter-gender:checked')].map(cb => cb.value);
        const styleBoxes  = [...document.querySelectorAll('.filter-style:checked')].map(cb => cb.value);

        const allGender = genderBoxes.includes('all');
        const allStyle  = styleBoxes.includes('all');

        const cards = document.querySelectorAll('.product-card');
        let visible = 0;

        cards.forEach(card => {
            const name   = card.dataset.name.toLowerCase();
            const gender = card.dataset.gender;
            const style  = card.dataset.style;
            const price  = parseFloat(card.dataset.price);

            const matchSearch = name.includes(search);
            const matchGender = allGender || genderBoxes.includes(gender);
            const matchStyle  = allStyle  || styleBoxes.includes(style);
            const matchPrice  = price <= maxPrice;

            if (matchSearch && matchGender && matchStyle && matchPrice) {
                card.classList.remove('hidden');
                visible++;
            } else {
                card.classList.add('hidden');
            }
        });

        document.getElementById('result-count').textContent = visible + ' pieces found';
        document.getElementById('empty-state').classList.toggle('hidden', visible > 0);
    }
</script>
@endpush