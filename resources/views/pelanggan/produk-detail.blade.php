@extends('layouts.main')

@section('title', 'Yama Crinkle Nylon Boardshorts — TANKEN')

@section('content')

{{-- ====== IMAGE ZOOM OVERLAY ====== --}}
<div id="zoom-overlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm" onclick="closeZoom()">
    <button class="absolute top-5 right-6 text-white text-3xl font-light leading-none">&times;</button>
    <img id="zoom-img" src="" alt="" class="max-h-[90vh] max-w-[90vw] object-contain rounded-lg shadow-2xl" onclick="event.stopPropagation()">
</div>

<div class="max-w-7xl mx-auto px-6 lg:px-10 pt-6 pb-16">

    {{-- Back --}}
    <a href="{{ route('katalog') }}" class="inline-flex items-center gap-1.5 text-xs text-gray-500 hover:text-black transition-colors mb-6 group">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14" class="group-hover:-translate-x-0.5 transition-transform">
            <path d="M15 18l-6-6 6-6"/>
        </svg>
        BACK
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">

        {{-- ====== LEFT: IMAGES ====== --}}
        <div>
            {{-- Main image with zoom on hover --}}
            <div class="relative overflow-hidden rounded-xl bg-gray-100 aspect-[4/5] mb-3 cursor-zoom-in group"
                 id="main-img-wrap"
                 onmousemove="handleZoom(event)"
                 onmouseleave="resetZoom()"
                 onclick="openZoom(document.getElementById('main-product-img').src)">
                <img
                    id="main-product-img"
                    src="{{ asset('images/men-home.jpg') }}"
                    alt="Yama Crinkle Nylon Boardshorts"
                    class="w-full h-full object-cover object-top transition-none"
                    id="main-product-img"
                >
                {{-- Zoom lens overlay --}}
                <div id="zoom-lens"
                     class="absolute pointer-events-none hidden w-28 h-28 border-2 border-white/70 rounded-full shadow-lg overflow-hidden"
                     style="transform: translate(-50%, -50%);">
                </div>
                {{-- Zoom result box --}}
                <div id="zoom-result"
                     class="absolute top-0 right-0 w-56 h-56 hidden border border-gray-200 rounded-xl overflow-hidden shadow-xl bg-white pointer-events-none z-10"
                     style="transform: translateX(calc(100% + 12px));">
                    <div id="zoom-bg" class="w-full h-full bg-no-repeat" style="background-size: 300%;"></div>
                </div>
            </div>

            {{-- Thumbnails --}}
            <div class="flex gap-3">
                <button onclick="switchImage('{{ asset('images/men-home.jpg') }}', this)"
                    class="thumb-btn w-24 h-28 rounded-lg overflow-hidden border-2 border-black flex-shrink-0">
                    <img src="{{ asset('images/men-home.jpg') }}" alt="Thumb 1" class="w-full h-full object-cover object-top">
                </button>
                <button onclick="switchImage('{{ asset('images/men-home2.jpg') }}', this)"
                    class="thumb-btn w-24 h-28 rounded-lg overflow-hidden border-2 border-gray-200 flex-shrink-0 hover:border-gray-400 transition-colors">
                    <img src="{{ asset('images/men-home2.jpg') }}" alt="Thumb 2" class="w-full h-full object-cover object-top">
                </button>
            </div>
        </div>

        {{-- ====== RIGHT: PRODUCT INFO ====== --}}
        <div class="flex flex-col">

            {{-- Category --}}
            <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Celana Pendek</p>

            {{-- Title --}}
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight mb-3">Yama Crinkle<br>Nylon Boardshorts</h1>

            {{-- Price --}}
            <div class="flex items-center gap-3 mb-3">
                <span class="text-2xl font-bold text-gray-900">Rp1.249.000</span>
                <span class="text-sm text-gray-400 line-through">Rp1.499.000</span>
                <span class="text-xs bg-red-100 text-red-600 font-bold px-2 py-0.5 rounded">17%</span>
            </div>

            {{-- Rating --}}
            <div class="flex items-center gap-2 mb-2">
                <div class="flex gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             fill="{{ $i <= 4 ? '#f5a623' : 'none' }}"
                             stroke="{{ $i <= 4 ? '#f5a623' : '#d1d5db' }}"
                             stroke-width="1" width="14" height="14">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <span class="text-sm text-gray-600 font-medium">4.7</span>
                <span class="text-sm text-gray-400">(3 reviews)</span>
            </div>

            {{-- SKU & Stock --}}
            <div class="flex flex-col gap-0.5 mb-4 text-xs text-gray-500">
                <span><span class="font-semibold text-gray-700">SKU:</span> TKN-YCB-001</span>
                <span class="text-green-600 font-semibold">✓ In Stock (60 available)</span>
            </div>

            {{-- Description short --}}
            <p class="text-sm text-gray-600 leading-relaxed mb-5">Performance boardshorts perfect for workouts, outdoor activities, and casual wear.</p>

            <hr class="border-gray-100 mb-5">

            {{-- COLOR --}}
            <div class="mb-5">
                <p class="text-xs font-bold tracking-widest uppercase text-gray-500 mb-2">
                    Warna — <span id="selected-color-label" class="text-gray-900 normal-case font-semibold tracking-normal">Pilih warna</span>
                </p>
                <div class="flex gap-2" id="color-options">
                    <button onclick="selectColor('Olive', this)"
                        class="color-btn px-4 py-1.5 rounded border-2 border-gray-200 text-sm font-medium text-gray-700 hover:border-gray-800 transition-colors">
                        Olive
                    </button>
                    <button onclick="selectColor('Stone Grey', this)"
                        class="color-btn px-4 py-1.5 rounded border-2 border-gray-200 text-sm font-medium text-gray-700 hover:border-gray-800 transition-colors">
                        Stone Grey
                    </button>
                    <button onclick="selectColor('Indigo', this)"
                        class="color-btn px-4 py-1.5 rounded border-2 border-gray-200 text-sm font-medium text-gray-700 hover:border-gray-800 transition-colors">
                        Indigo
                    </button>
                </div>
                <p id="color-error" class="text-xs text-red-500 mt-1 hidden">Pilih warna terlebih dahulu.</p>
            </div>

            {{-- SIZE --}}
            <div class="mb-5">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-bold tracking-widest uppercase text-gray-500">
                        Ukuran — <span id="selected-size-label" class="text-gray-900 normal-case font-semibold tracking-normal">Pilih ukuran</span>
                    </p>
                    <button class="text-xs text-gray-400 underline hover:text-gray-600 flex items-center gap-1" onclick="alert('Size guide modal — coming soon!')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" width="12" height="12">
                            <path d="M21 6H3M21 12H3M21 18H3"/></svg>
                        Size Guide
                    </button>
                </div>
                <div class="flex gap-2" id="size-options">
                    @foreach(['XS','S','M','L','XL','XXL'] as $sz)
                    <button onclick="selectSize('{{ $sz }}', this)"
                        class="size-btn w-10 h-10 rounded border-2 border-gray-200 text-sm font-semibold text-gray-700 hover:border-gray-800 transition-colors">
                        {{ $sz }}
                    </button>
                    @endforeach
                </div>
                <p id="size-error" class="text-xs text-red-500 mt-1 hidden">Pilih ukuran terlebih dahulu.</p>
            </div>

            {{-- QUANTITY --}}
            <div class="mb-6">
                <p class="text-xs font-bold tracking-widest uppercase text-gray-500 mb-2">Jumlah</p>
                <div class="flex items-center border border-gray-200 rounded w-fit">
                    <button onclick="changeQty(-1)" class="w-9 h-9 text-lg font-medium text-gray-600 hover:bg-gray-50 rounded-l transition-colors">−</button>
                    <span id="qty-display" class="w-10 text-center text-sm font-semibold text-gray-900">1</span>
                    <button onclick="changeQty(1)" class="w-9 h-9 text-lg font-medium text-gray-600 hover:bg-gray-50 rounded-r transition-colors">+</button>
                </div>
            </div>

            {{-- ADD TO CART + WISHLIST --}}
            <div class="flex gap-3 mb-6">
                <button onclick="addToCart()"
                    class="flex-1 bg-black text-white text-sm font-bold uppercase tracking-wider py-3.5 rounded flex items-center justify-center gap-2 hover:bg-gray-900 active:scale-[0.99] transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    Add to Cart
                </button>
                <button class="w-12 h-12 border-2 border-gray-200 rounded flex items-center justify-center text-gray-400 hover:border-gray-800 hover:text-red-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" width="18" height="18">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </button>
            </div>

            {{-- Toast notification --}}
            <div id="cart-toast"
                 class="hidden fixed bottom-6 right-6 z-50 bg-black text-white text-sm font-medium px-5 py-3 rounded-full shadow-xl flex items-center gap-2 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="16" height="16" class="text-green-400">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
                Produk ditambahkan ke keranjang!
            </div>

            {{-- Benefits --}}
            <div class="grid grid-cols-2 gap-x-6 gap-y-3 pt-4 border-t border-gray-100">
                <div>
                    <p class="text-xs font-bold text-gray-800">🚚 Free Shipping</p>
                    <p class="text-xs text-gray-400 mt-0.5">Untuk pembelian di atas Rp500.000</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-800">↩ 30-Day Returns</p>
                    <p class="text-xs text-gray-400 mt-0.5">Free returns, no questions</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-800">🔒 Secure Checkout</p>
                    <p class="text-xs text-gray-400 mt-0.5">SSL encrypted payment</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-800">⭐ Premium Quality</p>
                    <p class="text-xs text-gray-400 mt-0.5">Satisfaction guaranteed</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ====== TABS: Description / Material / Reviews ====== --}}
    <div class="mt-16 border-t border-gray-200 pt-8">
        <div class="flex gap-8 border-b border-gray-200 mb-8">
            <button onclick="switchTab('desc')" id="tab-desc"
                class="tab-btn pb-3 text-sm font-bold uppercase tracking-widest text-black border-b-2 border-black">
                Description
            </button>
            <button onclick="switchTab('material')" id="tab-material"
                class="tab-btn pb-3 text-sm font-bold uppercase tracking-widest text-gray-400 border-b-2 border-transparent hover:text-gray-700 transition-colors">
                Material
            </button>
            <button onclick="switchTab('reviews')" id="tab-reviews"
                class="tab-btn pb-3 text-sm font-bold uppercase tracking-widest text-gray-400 border-b-2 border-transparent hover:text-gray-700 transition-colors">
                Reviews (3)
            </button>
        </div>

        <div id="content-desc" class="tab-content max-w-2xl">
            <p class="text-sm text-gray-700 leading-relaxed">
                Yama Crinkle Nylon Boardshort hadir sebagai pilihan tepat untuk gaya hidup aktif, nyaman digunakan untuk aktivitas outdoor, traveling, hingga momen santai.
            </p>
            <p class="text-sm text-gray-700 leading-relaxed mt-3">
                Dilengkapi lima kantong yang praktis: dua kantong depan berukuran besar, dua kantong kecil berbahan mesh agar cepat kering, serta satu kantong belakang untuk tambahan ruang simpan. Tali pinggang elastis yang adjustable memberikan kenyamanan maksimal dan mudah disesuaikan, memastikan fit yang pas sekaligus tetap fleksibel saat bergerak.
            </p>
        </div>

        <div id="content-material" class="tab-content hidden max-w-2xl">
            <ul class="text-sm text-gray-700 leading-relaxed space-y-2 list-disc list-inside">
                <li>100% Recycled Nylon Crinkle Fabric</li>
                <li>Quick-dry mesh lining</li>
                <li>4-way stretch for full range of motion</li>
                <li>UPF 30+ sun protection</li>
                <li>Machine washable — cold water recommended</li>
            </ul>
        </div>

        <div id="content-reviews" class="tab-content hidden max-w-2xl space-y-6">
            @foreach([
                ['name' => 'Andi R.', 'rating' => 5, 'date' => '12 Apr 2026', 'text' => 'Kualitas bagus banget, bahannya adem dan cepet kering. Cocok banget buat aktivitas outdoor!'],
                ['name' => 'Bima S.', 'rating' => 4, 'date' => '5 Mar 2026',  'text' => 'Desainnya simpel dan elegan. Ukurannya pas sesuai chart. Recommended!'],
                ['name' => 'Dita K.', 'rating' => 5, 'date' => '20 Feb 2026', 'text' => 'Sudah beli 2x, gak kecewa. Jahitannya rapi dan kantongnya banyak.'],
            ] as $review)
            <div class="border-b border-gray-100 pb-5">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-bold text-gray-900">{{ $review['name'] }}</span>
                    <span class="text-xs text-gray-400">{{ $review['date'] }}</span>
                </div>
                <div class="flex gap-0.5 mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             fill="{{ $i <= $review['rating'] ? '#f5a623' : '#e5e7eb' }}"
                             width="12" height="12">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <p class="text-sm text-gray-600">{{ $review['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .color-btn.active  { border-color: #111; background-color: #111; color: #fff; }
    .size-btn.active   { border-color: #111; background-color: #111; color: #fff; }
    .thumb-btn.active  { border-color: #111; }
    #zoom-overlay.flex { display: flex; }
</style>
@endpush

@push('scripts')
<script>
    let selectedColor = null;
    let selectedSize  = null;
    let qty = 1;

    // ---- Color & Size selection ----
    function selectColor(color, btn) {
        selectedColor = color;
        document.getElementById('selected-color-label').textContent = color;
        document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('color-error').classList.add('hidden');
    }

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
        if (!selectedColor) {
            document.getElementById('color-error').classList.remove('hidden');
            valid = false;
        }
        if (!selectedSize) {
            document.getElementById('size-error').classList.remove('hidden');
            valid = false;
        }
        if (!valid) return;

        const cart = JSON.parse(sessionStorage.getItem('tanken_cart') || '[]');
        const key  = 'yama-crinkle-' + selectedColor + '-' + selectedSize;
        const idx  = cart.findIndex(i => i.key === key);

        if (idx >= 0) {
            cart[idx].qty += qty;
        } else {
            cart.push({
                key,
                name:  'Yama Crinkle Nylon Boardshorts',
                color: selectedColor,
                size:  selectedSize,
                price: 1249000,
                qty,
                img:   '{{ asset('images/men-home.jpg') }}'
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
        // update zoom bg too
        document.getElementById('zoom-bg').style.backgroundImage = `url('${src}')`;
    }

    // ---- Zoom on hover ----
    const mainWrap = document.getElementById('main-img-wrap');

    function handleZoom(e) {
        const img    = document.getElementById('main-product-img');
        const lens   = document.getElementById('zoom-lens');
        const result = document.getElementById('zoom-result');
        const bg     = document.getElementById('zoom-bg');
        const rect   = mainWrap.getBoundingClientRect();

        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        lens.style.left = x + 'px';
        lens.style.top  = y + 'px';
        lens.classList.remove('hidden');
        result.classList.remove('hidden');

        // Set bg image and position
        const bgX = (x / rect.width) * 100;
        const bgY = (y / rect.height) * 100;
        bg.style.backgroundImage    = `url('${img.src}')`;
        bg.style.backgroundSize     = '300%';
        bg.style.backgroundPosition = `${bgX}% ${bgY}%`;
    }

    function resetZoom() {
        document.getElementById('zoom-lens').classList.add('hidden');
        document.getElementById('zoom-result').classList.add('hidden');
    }

    // ---- Fullscreen zoom on click ----
    function openZoom(src) {
        const overlay = document.getElementById('zoom-overlay');
        document.getElementById('zoom-img').src = src;
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
    }

    function closeZoom() {
        const overlay = document.getElementById('zoom-overlay');
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeZoom();
    });

    // ---- Tabs ----
    function switchTab(tab) {
        ['desc','material','reviews'].forEach(t => {
            document.getElementById('content-' + t).classList.add('hidden');
            document.getElementById('tab-' + t).classList.remove('text-black','border-black');
            document.getElementById('tab-' + t).classList.add('text-gray-400','border-transparent');
        });
        document.getElementById('content-' + tab).classList.remove('hidden');
        document.getElementById('tab-' + tab).classList.add('text-black','border-black');
        document.getElementById('tab-' + tab).classList.remove('text-gray-400','border-transparent');
    }
</script>
@endpush