@extends('layouts.main')

{{-- DYNAMIC: title dari database --}}
{{-- OLD: @section('title', 'Yama Crinkle Nylon Boardshorts — TANKEN') --}}
@section('title', $product->name . ' — TANKEN')

@section('content')

{{-- ====== IMAGE POPUP ZOOM OVERLAY ====== --}}
<div id="zoom-overlay" class="fixed inset-0 z-[60] hidden bg-black/90 backdrop-blur-sm" onclick="closeZoom(event)">
    <button class="absolute top-4 right-5 text-white/80 hover:text-white z-10 p-2" onclick="closeZoom()">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
            width="28" height="28">
            <path d="M18 6 6 18M6 6l12 12" />
        </svg>
    </button>
    {{-- Zoom controls --}}
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-3 z-10">
        <button onclick="zoomChange(-0.3)"
            class="bg-white/20 hover:bg-white/40 text-white rounded-full w-10 h-10 flex items-center justify-center text-xl font-light transition">−</button>
        <span id="zoom-level-label" class="text-white/70 text-xs font-medium w-12 text-center">100%</span>
        <button onclick="zoomChange(0.3)"
            class="bg-white/20 hover:bg-white/40 text-white rounded-full w-10 h-10 flex items-center justify-center text-xl font-light transition">+</button>
        <button onclick="resetZoomLevel()"
            class="bg-white/20 hover:bg-white/40 text-white rounded-full px-3 h-10 flex items-center text-xs font-medium transition">Reset</button>
    </div>
    {{-- Swipeable image container --}}
    <div class="w-full h-full flex items-center justify-center overflow-hidden" id="zoom-container">
        <img id="zoom-img" src="" alt=""
            class="max-h-[85vh] max-w-[90vw] object-contain rounded select-none transition-transform duration-150"
            style="transform-origin: center center; transform: scale(1);" draggable="false">
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 pt-4 sm:pt-6 pb-16">

    {{-- Breadcrumb / Back --}}
    <a href="{{ route('pelanggan.katalog') }}"
        class="inline-flex items-center gap-1.5 text-xs text-gray-500 hover:text-black transition-colors mb-5 group">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
            width="14" height="14" class="group-hover:-translate-x-0.5 transition-transform">
            <path d="M15 18l-6-6 6-6" />
        </svg>
        BACK
    </a>

    {{-- ====== MAIN PRODUCT SECTION ====== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16">

        {{-- ====== LEFT: IMAGES ====== --}}
        <div>
            {{-- Main image — click to open popup zoom --}}
            <div class="relative rounded-xl bg-gray-100 overflow-hidden aspect-[4/5] mb-3 cursor-zoom-in"
                onclick="openZoom(document.getElementById('main-product-img').src)">
                <img id="main-product-img" {{-- OLD: src="{{ asset('images/men-home.jpg') }}" --}} {{-- DYNAMIC: gunakan
                    main_image dari DB, fallback ke placeholder --}}
                    src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('images/men-home.jpg') }}"
                    alt="{{ $product->name }}" class="w-full h-full object-cover object-top">
                {{-- Hint label --}}
                <div
                    class="absolute bottom-3 right-3 bg-black/50 text-white text-[10px] px-2 py-1 rounded-full flex items-center gap-1 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2" width="11" height="11">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                        <line x1="11" y1="8" x2="11" y2="14" />
                        <line x1="8" y1="11" x2="14" y2="11" />
                    </svg>
                    Tap untuk perbesar
                </div>
            </div>

            {{-- Thumbnails --}}
            {{-- OLD:
            <div class="flex gap-3">
                <button onclick="switchImage('{{ asset('images/men-home.jpg') }}', this)"
                    class="thumb-btn flex-shrink-0 w-20 h-24 sm:w-24 sm:h-28 rounded-lg overflow-hidden border-2 border-black">
                    <img src="{{ asset('images/men-home.jpg') }}" ...>
                </button>
                <button onclick="switchImage('{{ asset('images/men-home2.jpg') }}', this)" ...>
                    <img src="{{ asset('images/men-home2.jpg') }}" ...>
                </button>
            </div>
            --}}
            {{-- DYNAMIC: thumbnail dari main_image + galleries --}}
            <div class="flex gap-3 overflow-x-auto pb-1">
                {{-- Thumbnail main image --}}
                @if($product->main_image)
                <button onclick="switchImage('{{ asset('storage/' . $product->main_image) }}', this)"
                    class="thumb-btn flex-shrink-0 w-20 h-24 sm:w-24 sm:h-28 rounded-lg overflow-hidden border-2 border-black">
                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}"
                        class="w-full h-full object-cover object-top">
                </button>
                @endif

                {{-- Thumbnail dari galleries --}}
                @foreach($product->galleries as $gallery)
                <button onclick="switchImage('{{ asset('storage/' . $gallery->image) }}', this)"
                    class="thumb-btn flex-shrink-0 w-20 h-24 sm:w-24 sm:h-28 rounded-lg overflow-hidden border-2 border-gray-200 hover:border-gray-400 transition-colors">
                    <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $product->name }}"
                        class="w-full h-full object-cover object-top">
                </button>
                @endforeach
            </div>
        </div>

        {{-- ====== RIGHT: PRODUCT INFO ====== --}}
        <div class="flex flex-col">

            {{-- Category badge --}}
            {{-- OLD: <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Celana Pendek</p> --}}
            {{-- DYNAMIC: tipe produk dari DB --}}
            <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">
                Celana {{ ucfirst($product->type) }}
            </p>

            {{-- Title --}}
            {{-- OLD: <h1 ...>Yama Crinkle Nylon Boardshorts</h1> --}}
            {{-- DYNAMIC: nama produk dari DB --}}
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight mb-3">
                {{ $product->name }}
            </h1>

            {{-- Price --}}
            {{-- OLD:
            <div ...>
                <span ...>Rp1.249.000</span>
                <span ...>Rp1.499.000</span>
                <span ...>17%</span>
            </div>
            --}}
            {{-- DYNAMIC: harga dari DB, original_price & diskon jika ada --}}
            <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-3">
                <span class="text-xl sm:text-2xl font-bold text-gray-900">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </span>
                @if($product->original_price && $product->original_price > $product->price)
                <span class="text-sm text-gray-400 line-through">
                    Rp {{ number_format($product->original_price, 0, ',', '.') }}
                </span>
                <span class="text-xs bg-red-100 text-red-600 font-bold px-2 py-0.5 rounded">
                    {{ $product->discount_percent }}%
                </span>
                @endif
            </div>

            {{-- Rating --}}
            {{-- OLD: rating & review count hardcoded (4.7, 3 ulasan) --}}
            {{-- DYNAMIC: dari relasi reviews --}}
            @php
            $avgRating = $product->reviews->avg('rating') ?? 0;
            $reviewCount = $product->reviews->count();
            @endphp
            @if($reviewCount > 0)
            <div class="flex items-center gap-2 mb-3">
                <div class="flex gap-0.5">
                    @for($i = 1; $i <= 5; $i++) <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="{{ $i <= round($avgRating) ? '#f5a623' : '#e5e7eb' }}" width="14" height="14">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        @endfor
                </div>
                <span class="text-sm text-gray-600 font-medium">{{ number_format($avgRating, 1) }}</span>
                <span class="text-sm text-gray-400">({{ $reviewCount }} ulasan)</span>
            </div>
            @endif

            {{-- SKU & Stock --}}
            {{-- OLD:
            <span>SKU: TKN-YCB-001</span>
            <span>✓ Stok tersedia (60 item)</span>
            --}}
            {{-- DYNAMIC: SKU & total stok dari DB --}}
            @php $totalStock = $product->stocks->sum('quantity'); @endphp
            <div class="flex flex-col gap-0.5 mb-4 text-xs text-gray-500">
                <span><span class="font-semibold text-gray-700">SKU:</span> {{ $product->sku }}</span>
                @if($totalStock > 0)
                <span class="text-green-600 font-semibold">✓ Stok tersedia ({{ $totalStock }} item)</span>
                @else
                <span class="text-red-500 font-semibold">✗ Stok habis</span>
                @endif
            </div>

            <hr class="border-gray-100 mb-5">

            {{-- COLOR --}}
            {{-- OLD: warna hardcoded (Olive, Stone Grey, Indigo) --}}
            {{-- DYNAMIC: dari $product->colors (JSON array) --}}
            @php $colorsArray = is_array($product->colors) ? $product->colors : (json_decode($product->colors, true) ??
            []); @endphp
            @if(!empty($colorsArray))
            <div class="mb-5">
                <p class="text-xs font-bold tracking-widest uppercase text-gray-500 mb-2">
                    Warna — <span id="selected-color-label"
                        class="text-gray-900 normal-case font-semibold tracking-normal">Pilih warna</span>
                </p>
                <div class="flex flex-wrap gap-2" id="color-options">
                    @foreach($colorsArray as $color)
                    <button onclick="selectColor('{{ $color }}', this)"
                        class="color-btn px-4 py-2 rounded border-2 border-gray-200 text-sm font-medium text-gray-700 hover:border-gray-800 transition-colors">
                        {{ $color }}
                    </button>
                    @endforeach
                </div>
                <p id="color-error" class="text-xs text-red-500 mt-1.5 hidden">⚠ Pilih warna terlebih dahulu.</p>
            </div>
            @endif

            {{-- SIZE --}}
            {{-- OLD: ukuran hardcoded (XS, S, M, L, XL, XXL) --}}
            {{-- DYNAMIC: dari $product->sizes (JSON array) --}}
            @php $sizesArray = is_array($product->sizes) ? $product->sizes : (json_decode($product->sizes, true) ?? []);
            @endphp
            <div class="mb-5">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-bold tracking-widest uppercase text-gray-500">
                        Ukuran — <span id="selected-size-label"
                            class="text-gray-900 normal-case font-semibold tracking-normal">Pilih ukuran</span>
                    </p>
                    {{-- OLD: onclick="alert('Size guide modal — coming soon!')" --}}
                    <button class="text-xs text-gray-400 underline hover:text-gray-700 transition-colors"
                        onclick="openSizeChart()">
                        Size Guide
                    </button>
                </div>
                <div class="flex flex-wrap gap-2" id="size-options">
                    @forelse($sizesArray as $sz)
                    <button onclick="selectSize('{{ $sz }}', this)"
                        class="size-btn w-11 h-11 rounded border-2 border-gray-200 text-sm font-semibold text-gray-700 hover:border-gray-800 transition-colors">
                        {{ $sz }}
                    </button>
                    @empty
                    {{-- Fallback kalau sizes kosong --}}
                    @foreach(['XS','S','M','L','XL','XXL'] as $sz)
                    <button onclick="selectSize('{{ $sz }}', this)"
                        class="size-btn w-11 h-11 rounded border-2 border-gray-200 text-sm font-semibold text-gray-700 hover:border-gray-800 transition-colors">
                        {{ $sz }}
                    </button>
                    @endforeach
                    @endforelse
                </div>
                <p id="size-error" class="text-xs text-red-500 mt-1.5 hidden">⚠ Pilih ukuran terlebih dahulu.</p>
            </div>

            {{-- QUANTITY --}}
            <div class="mb-6">
                <p class="text-xs font-bold tracking-widest uppercase text-gray-500 mb-2">Jumlah</p>
                <div class="flex items-center border border-gray-200 rounded w-fit">
                    <button onclick="changeQty(-1)"
                        class="w-10 h-10 text-xl font-light text-gray-600 hover:bg-gray-50 rounded-l transition-colors flex items-center justify-center">−</button>
                    <span id="qty-display" class="w-12 text-center text-sm font-semibold text-gray-900">1</span>
                    <button onclick="changeQty(1)"
                        class="w-10 h-10 text-xl font-light text-gray-600 hover:bg-gray-50 rounded-r transition-colors flex items-center justify-center">+</button>
                </div>
            </div>

            {{-- ADD TO CART + WISHLIST --}}
            <div class="flex gap-3 mb-6">
                <button onclick="addToCart()"
                    class="flex-1 bg-black text-white text-sm font-bold uppercase tracking-wider py-4 rounded-lg flex items-center justify-center gap-2 hover:bg-gray-900 active:scale-[0.98] transition-all"
                    {{ $totalStock <=0 ? 'disabled' : '' }}>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2" width="16" height="16">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                    {{ $totalStock <= 0 ? 'Stok Habis' : 'Tambah ke Keranjang' }} </button>
                        <button
                            class="w-12 h-12 border-2 border-gray-200 rounded-lg flex items-center justify-center text-gray-400 hover:border-red-300 hover:text-red-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8" width="18" height="18">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                            </svg>
                        </button>
            </div>

            {{-- Benefits grid --}}
            <div class="grid grid-cols-2 gap-x-4 gap-y-3 pt-4 border-t border-gray-100">
                <div class="flex items-start gap-2">
                    <span class="text-base leading-none mt-0.5">🚚</span>
                    <div>
                        <p class="text-xs font-bold text-gray-800">Gratis Ongkir</p>
                        <p class="text-xs text-gray-400 mt-0.5">Pembelian di atas Rp500.000</p>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <span class="text-base leading-none mt-0.5">↩</span>
                    <div>
                        <p class="text-xs font-bold text-gray-800">30-Day Returns</p>
                        <p class="text-xs text-gray-400 mt-0.5">Pengembalian mudah & gratis</p>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <span class="text-base leading-none mt-0.5">🔒</span>
                    <div>
                        <p class="text-xs font-bold text-gray-800">Pembayaran Aman</p>
                        <p class="text-xs text-gray-400 mt-0.5">Enkripsi SSL</p>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <span class="text-base leading-none mt-0.5">⭐</span>
                    <div>
                        <p class="text-xs font-bold text-gray-800">Kualitas Premium</p>
                        <p class="text-xs text-gray-400 mt-0.5">Garansi kepuasan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====== TABS: Description & Reviews ====== --}}
    <div class="mt-14 sm:mt-16 border-t border-gray-200 pt-8">

        {{-- Tab buttons --}}
        <div class="flex gap-6 sm:gap-10 border-b border-gray-200 mb-8 overflow-x-auto scrollbar-none">
            <button onclick="switchTab('desc')" id="tab-desc"
                class="tab-btn whitespace-nowrap pb-3 text-xs sm:text-sm font-bold uppercase tracking-widest text-black border-b-2 border-black -mb-px">
                Deskripsi
            </button>
            {{-- OLD: Ulasan (3) hardcoded --}}
            {{-- DYNAMIC: jumlah ulasan dari DB --}}
            <button onclick="switchTab('reviews')" id="tab-reviews"
                class="tab-btn whitespace-nowrap pb-3 text-xs sm:text-sm font-bold uppercase tracking-widest text-gray-400 border-b-2 border-transparent hover:text-gray-700 transition-colors -mb-px">
                Ulasan ({{ $reviewCount }})
            </button>
        </div>

        {{-- Description content --}}
        {{-- OLD: deskripsi hardcoded --}}
        {{-- DYNAMIC: dari $product->description --}}
        <div id="content-desc" class="tab-content max-w-2xl">
            @if($product->description)
            <p class="text-sm text-gray-700 leading-relaxed">{{ $product->description }}</p>
            @else
            <p class="text-sm text-gray-400 italic">Belum ada deskripsi untuk produk ini.</p>
            @endif
        </div>

        {{-- Reviews content --}}
        {{-- OLD: reviews hardcoded array dummy --}}
        {{-- DYNAMIC: dari relasi $product->reviews --}}
        <div id="content-reviews" class="tab-content hidden max-w-2xl space-y-6">
            @forelse($product->reviews as $review)
            <div class="border-b border-gray-100 pb-5 last:border-0">
                <div class="flex items-center justify-between mb-1">
                    {{-- OLD: 'Andi R.' hardcoded --}}
                    <span class="text-sm font-bold text-gray-900">{{ $review->user->name ?? 'Pengguna' }}</span>
                    {{-- OLD: '12 Apr 2026' hardcoded --}}
                    <span class="text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex gap-0.5 mb-2">
                    @for($i = 1; $i <= 5; $i++) <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="{{ $i <= $review->rating ? '#f5a623' : '#e5e7eb' }}" width="13" height="13">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        @endfor
                </div>
                {{-- OLD: teks review hardcoded --}}
                <p class="text-sm text-gray-600 leading-relaxed">{{ $review->comment }}</p>
            </div>
            @empty
            <p class="text-sm text-gray-400 italic">Belum ada ulasan untuk produk ini.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- ====== TOAST NOTIFICATION ====== --}}
<div id="cart-toast"
    class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-black text-white text-sm font-medium px-5 py-3 rounded-full shadow-xl items-center gap-2 transition-all whitespace-nowrap">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
        width="16" height="16" class="text-green-400">
        <path d="M20 6L9 17l-5-5" />
    </svg>
    Produk ditambahkan ke keranjang!
</div>

{{-- MODAL: Size Chart --}}
@if($product->size_chart_image)
<div id="modal-sizechart"
    class="hidden fixed inset-0 z-[70] bg-black/80 backdrop-blur-sm flex items-center justify-center px-4"
    onclick="if(event.target===this) closeSizeChart()">
    <div class="bg-white rounded-xl w-full max-w-lg shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest">Size Guide</h3>
            <button onclick="closeSizeChart()" class="text-gray-400 hover:text-gray-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2" width="20" height="20">
                    <path d="M18 6 6 18M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-4">
            <img src="{{ asset('storage/' . $product->size_chart_image) }}" alt="Size Chart {{ $product->name }}"
                class="w-full rounded-lg object-contain max-h-[70vh]">
        </div>
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
    .color-btn.active {
        border-color: #111 !important;
        background-color: #111;
        color: #fff;
    }

    .size-btn.active {
        border-color: #111 !important;
        background-color: #111;
        color: #fff;
    }

    .thumb-btn.active {
        border-color: #111 !important;
    }

    /* Hide scrollbar on tab row */
    .scrollbar-none {
        scrollbar-width: none;
    }

    .scrollbar-none::-webkit-scrollbar {
        display: none;
    }

    /* Zoom overlay shown */
    #zoom-overlay.show {
        display: flex !important;
    }
</style>
@endpush

@push('scripts')
<script>
    let selectedColor = null;
    let selectedSize  = null;
    let qty = 1;
    let currentZoomScale = 1;

    // ---- Color ----
    function selectColor(color, btn) {
        selectedColor = color;
        document.getElementById('selected-color-label').textContent = color;
        document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('color-error').classList.add('hidden');
    }

    // ---- Size ----
    function selectSize(size, btn) {
        selectedSize = size;
        document.getElementById('selected-size-label').textContent = size;
        document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('size-error').classList.add('hidden');
    }

    // ---- Quantity ----
    function changeQty(delta) {
        qty = Math.max(1, qty + delta);
        document.getElementById('qty-display').textContent = qty;
    }

    // ---- Add to Cart ----
    function addToCart() {
        let valid = true;

        {{-- DYNAMIC: cek apakah produk punya pilihan warna --}}
        @if(!empty($colorsArray))
        if (!selectedColor) {
            document.getElementById('color-error').classList.remove('hidden');
            valid = false;
        }
        @endif

        if (!selectedSize) {
            document.getElementById('size-error').classList.remove('hidden');
            valid = false;
        }
        if (!valid) {
            document.getElementById('color-error').closest('div')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        const cart = JSON.parse(sessionStorage.getItem('tanken_cart') || '[]');

        {{-- OLD: key & data hardcoded --}}
        {{-- DYNAMIC: pakai data dari DB --}}
        const key  = '{{ $product->slug }}-' + (selectedColor || 'default') + '-' + selectedSize;
        const idx  = cart.findIndex(i => i.key === key);

        if (idx >= 0) {
            cart[idx].qty += qty;
        } else {
            cart.push({
                key,
                name:  '{{ $product->name }}',
                color: selectedColor,
                size:  selectedSize,
                price: {{ $product->price }},
                qty,
                img:   '{{ $product->main_image ? asset("storage/" . $product->main_image) : asset("images/men-home.jpg") }}'
            });
        }

        sessionStorage.setItem('tanken_cart', JSON.stringify(cart));
        window.dispatchEvent(new Event('cartUpdated'));
        showToast();
    }

    function showToast() {
        const toast = document.getElementById('cart-toast');
        toast.classList.remove('hidden');
        toast.classList.add('flex');
        setTimeout(() => {
            toast.classList.add('hidden');
            toast.classList.remove('flex');
        }, 2500);
    }

    // ---- Thumbnail switch ----
    function switchImage(src, btn) {
        document.getElementById('main-product-img').src = src;
        document.querySelectorAll('.thumb-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    // ---- POPUP ZOOM ----
    function openZoom(src) {
        const overlay = document.getElementById('zoom-overlay');
        const img     = document.getElementById('zoom-img');
        img.src = src;
        currentZoomScale = 1;
        img.style.transform = 'scale(1)';
        document.getElementById('zoom-level-label').textContent = '100%';
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeZoom(e) {
        if (e && e.target === document.getElementById('zoom-img')) return;
        document.getElementById('zoom-overlay').style.display = 'none';
        document.body.style.overflow = '';
    }

    function zoomChange(delta) {
        currentZoomScale = Math.min(4, Math.max(0.5, currentZoomScale + delta));
        applyZoom();
    }

    function resetZoomLevel() {
        currentZoomScale = 1;
        applyZoom();
    }

    function applyZoom() {
        const img = document.getElementById('zoom-img');
        img.style.transform = `scale(${currentZoomScale})`;
        document.getElementById('zoom-level-label').textContent = Math.round(currentZoomScale * 100) + '%';
    }

    document.addEventListener('wheel', function(e) {
        if (document.getElementById('zoom-overlay').style.display === 'flex') {
            e.preventDefault();
            zoomChange(e.deltaY < 0 ? 0.2 : -0.2);
        }
    }, { passive: false });

    let initDist = 0;
    let initScale = 1;
    document.getElementById('zoom-img')?.addEventListener('touchstart', e => {
        if (e.touches.length === 2) {
            initDist  = Math.hypot(e.touches[0].clientX - e.touches[1].clientX, e.touches[0].clientY - e.touches[1].clientY);
            initScale = currentZoomScale;
        }
    });
    document.getElementById('zoom-img')?.addEventListener('touchmove', e => {
        if (e.touches.length === 2) {
            e.preventDefault();
            const dist = Math.hypot(e.touches[0].clientX - e.touches[1].clientX, e.touches[0].clientY - e.touches[1].clientY);
            currentZoomScale = Math.min(4, Math.max(0.5, initScale * (dist / initDist)));
            applyZoom();
        }
    }, { passive: false });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeZoom();
    });

    // ---- Tabs ----
    function switchTab(tab) {
        ['desc', 'reviews'].forEach(t => {
            document.getElementById('content-' + t).classList.add('hidden');
            const btn = document.getElementById('tab-' + t);
            btn.classList.remove('text-black', 'border-black');
            btn.classList.add('text-gray-400', 'border-transparent');
        });
        document.getElementById('content-' + tab).classList.remove('hidden');
        const activeBtn = document.getElementById('tab-' + tab);
        activeBtn.classList.add('text-black', 'border-black');
        activeBtn.classList.remove('text-gray-400', 'border-transparent');
    }

    function openSizeChart() {
    @if($product->size_chart_image)
    document.getElementById('modal-sizechart').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    @else
    alert('Size chart belum tersedia untuk produk ini.');
    @endif
}

function closeSizeChart() {
    document.getElementById('modal-sizechart').classList.add('hidden');
    document.body.style.overflow = '';
}

// ESC juga bisa nutup
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeSizeChart();
        closeZoom();
    }
});
</script>
@endpush