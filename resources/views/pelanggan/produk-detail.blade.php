@extends('layouts.main')

@section('title', $product->name . ' — TANKEN')

@section('content')

{{-- ====== IMAGE POPUP ZOOM OVERLAY ====== --}}
<div id="zoom-overlay" class="fixed inset-0 z-[60] hidden bg-black/90 backdrop-blur-sm" onclick="closeZoom(event)">
    <button class="absolute top-4 right-5 text-white/80 hover:text-white z-10 p-2" onclick="closeZoom()">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="28" height="28">
            <path d="M18 6 6 18M6 6l12 12" />
        </svg>
    </button>
    
    {{-- Zoom controls --}}
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-3 z-10">
        <button onclick="zoomChange(-0.3)" class="bg-white/20 hover:bg-white/40 text-white rounded-full w-10 h-10 flex items-center justify-center text-xl font-light transition">−</button>
        <span id="zoom-level-label" class="text-white/70 text-xs font-medium w-12 text-center">100%</span>
        <button onclick="zoomChange(0.3)" class="bg-white/20 hover:bg-white/40 text-white rounded-full w-10 h-10 flex items-center justify-center text-xl font-light transition">+</button>
        <button onclick="resetZoomLevel()" class="bg-white/20 hover:bg-white/40 text-white rounded-full px-3 h-10 flex items-center text-xs font-medium transition">Reset</button>
    </div>

    {{-- Swipeable image container --}}
    <div class="w-full h-full flex items-center justify-center overflow-hidden cursor-move" id="zoom-container">
        <img id="zoom-img" src="" alt=""
            class="max-h-[85vh] max-w-[90vw] object-contain rounded select-none transition-transform duration-150 ease-out"
            style="transform-origin: center center; transform: scale(1) translate(0px, 0px);" draggable="false">
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 pt-4 sm:pt-6 pb-16">

    {{-- Breadcrumb / Back --}}
    <a href="{{ route('pelanggan.katalog') }}" class="inline-flex items-center gap-1.5 text-xs text-gray-500 hover:text-black transition-colors mb-5 group">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="14" height="14" class="group-hover:-translate-x-0.5 transition-transform">
            <path d="M15 18l-6-6 6-6" />
        </svg>
        BACK
    </a>

    {{-- ====== MAIN PRODUCT SECTION ====== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16">

        {{-- ====== LEFT: IMAGES ====== --}}
        <div>
            <div class="relative group rounded-xl bg-gray-100 overflow-hidden aspect-[4/5] mb-3">
                {{-- Main Image (Ditambah transisi opacity untuk animasi smooth) --}}
                <img id="main-product-img" 
                    src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('images/men-home.jpg') }}"
                    alt="{{ $product->name }}" 
                    class="w-full h-full object-cover object-top cursor-zoom-in transition-opacity duration-200 ease-in-out opacity-100"
                    onclick="openZoom(this.src)">

                {{-- Navigation Arrows --}}
                <button onclick="prevImage()" class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-black p-2 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="18" height="18"><path d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button onclick="nextImage()" class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-black p-2 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="18" height="18"><path d="M9 5l7 7-7 7"/></svg>
                </button>

                <div class="absolute bottom-3 right-3 bg-black/50 text-white text-[10px] px-2 py-1 rounded-full flex items-center gap-1 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="11" height="11">
                        <circle cx="11" cy="11" r="8" /><path d="m21 21-4.35-4.35" /><line x1="11" y1="8" x2="11" y2="14" /><line x1="8" y1="11" x2="14" y2="11" />
                    </svg>
                    Tap untuk perbesar
                </div>
            </div>

            {{-- Thumbnails --}}
            <div class="flex gap-3 overflow-x-auto pb-1 scrollbar-none" id="thumb-container">
                @if($product->main_image)
                <button onclick="switchImage('{{ asset('storage/' . $product->main_image) }}', this, 0)"
                    class="thumb-btn flex-shrink-0 w-20 h-24 sm:w-24 sm:h-28 rounded-lg overflow-hidden border-2 border-black active-thumb">
                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover object-top">
                </button>
                @endif
                @foreach($product->galleries as $index => $gallery)
                <button onclick="switchImage('{{ asset('storage/' . $gallery->image) }}', this, {{ $index + 1 }})"
                    class="thumb-btn flex-shrink-0 w-20 h-24 sm:w-24 sm:h-28 rounded-lg overflow-hidden border-2 border-transparent hover:border-gray-300 transition-colors">
                    <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover object-top">
                </button>
                @endforeach
            </div>
        </div>

        {{-- ====== RIGHT: PRODUCT INFO ====== --}}
        <div class="flex flex-col">
            <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Celana {{ ucfirst($product->type) }}</p>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight mb-3">{{ $product->name }}</h1>

            <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-3">
                <span class="text-xl sm:text-2xl font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                @if($product->original_price && $product->original_price > $product->price)
                <span class="text-sm text-gray-400 line-through">Rp {{ number_format($product->original_price, 0, ',', '.') }}</span>
                <span class="text-xs bg-red-100 text-red-600 font-bold px-2 py-0.5 rounded">{{ $product->discount_percent }}%</span>
                @endif
            </div>

            @php $avgRating = $product->reviews->avg('rating') ?? 0; $reviewCount = $product->reviews->count(); @endphp
            @if($reviewCount > 0)
            <div class="flex items-center gap-2 mb-4">
                <div class="flex gap-0.5">
                    @for($i = 1; $i <= 5; $i++) 
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{{ $i <= round($avgRating) ? '#f5a623' : '#e5e7eb' }}" width="14" height="14">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    @endfor
                </div>
                <span class="text-sm text-gray-600 font-medium">{{ number_format($avgRating, 1) }}</span>
                <span class="text-sm text-gray-400">({{ $reviewCount }} ulasan)</span>
            </div>
            @endif

            <hr class="border-gray-100 mb-5">

            {{-- SIZE ONLY --}}
            @php $sizesArray = is_array($product->sizes) ? $product->sizes : (json_decode($product->sizes, true) ?? []); @endphp
            <div class="mb-5">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-bold tracking-widest uppercase text-gray-500">
                        Ukuran — <span id="selected-size-label" class="text-gray-900 normal-case font-semibold tracking-normal">Pilih ukuran</span>
                    </p>
                    <button class="text-xs text-gray-400 underline hover:text-gray-700 transition-colors" onclick="openSizeChart()">Size Guide</button>
                </div>
                <div class="flex flex-wrap gap-2" id="size-options">
                    @forelse($sizesArray as $sz)
                    <button onclick="selectSize('{{ $sz }}', this)" class="size-btn w-11 h-11 rounded border-2 border-gray-200 text-sm font-semibold text-gray-700 hover:border-gray-800 transition-colors">{{ $sz }}</button>
                    @empty
                    @foreach(['XS','S','M','L','XL','XXL'] as $sz)
                    <button onclick="selectSize('{{ $sz }}', this)" class="size-btn w-11 h-11 rounded border-2 border-gray-200 text-sm font-semibold text-gray-700 hover:border-gray-800 transition-colors">{{ $sz }}</button>
                    @endforeach
                    @endforelse
                </div>
                <p id="size-error" class="text-xs text-red-500 mt-2 font-medium hidden">⚠ Silakan pilih ukuran terlebih dahulu.</p>
            </div>

            {{-- QUANTITY & DYNAMIC STOCK INFO --}}
            <div class="mb-6">
                <p class="text-xs font-bold tracking-widest uppercase text-gray-500 mb-2">Jumlah</p>
                <div class="flex items-center gap-4">
                    <div class="flex items-center border border-gray-200 rounded w-fit bg-white">
                        <button onclick="changeQty(-1)" class="w-10 h-10 text-xl font-light text-gray-600 hover:bg-gray-50 rounded-l flex items-center justify-center">−</button>
                        <span id="qty-display" class="w-12 text-center text-sm font-semibold text-gray-900">1</span>
                        <button onclick="changeQty(1)" class="w-10 h-10 text-xl font-light text-gray-600 hover:bg-gray-50 rounded-r flex items-center justify-center">+</button>
                    </div>
                    <span id="stock-info" class="hidden text-xs font-semibold"></span>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex gap-2 sm:gap-3 mb-6">
                <button id="btn-add-cart" onclick="addToCart()"
                    class="flex-1 bg-white text-black border-2 border-black text-[11px] sm:text-xs font-bold uppercase tracking-wider py-3.5 rounded-lg flex items-center justify-center gap-1 sm:gap-2 hover:bg-gray-50 active:scale-[0.98] transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" /><line x1="3" y1="6" x2="21" y2="6" /><path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                    Keranjang
                </button>

                <button id="btn-buy-now" onclick="buyNow()"
                    class="flex-1 bg-black text-white border-2 border-black text-[11px] sm:text-xs font-bold uppercase tracking-wider py-3.5 rounded-lg flex items-center justify-center gap-2 hover:bg-gray-900 active:scale-[0.98] transition-all">
                    Beli Sekarang
                </button>

                <button id="btn-wishlist" onclick="toggleWishlist()" class="w-12 h-12 sm:w-[48px] sm:h-[48px] border-2 rounded-lg flex items-center justify-center transition-colors flex-shrink-0
                   {{ auth()->check() && auth()->user()->wishlists()->where('product_id', $product->id)->exists() ? 'border-red-400 bg-white text-red-500' : 'border-gray-200 text-gray-400 hover:border-red-300 hover:text-red-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="{{ auth()->check() && auth()->user()->wishlists()->where('product_id', $product->id)->exists() ? '#ef4444' : 'none' }}" stroke="{{ auth()->check() && auth()->user()->wishlists()->where('product_id', $product->id)->exists() ? '#ef4444' : 'currentColor' }}" viewBox="0 0 24 24" stroke-width="1.8" width="18" height="18">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ====== TABS ====== --}}
    <div class="mt-14 sm:mt-16 border-t border-gray-200 pt-8">
        <div class="flex gap-6 sm:gap-10 border-b border-gray-200 mb-8 overflow-x-auto scrollbar-none">
            <button onclick="switchTab('desc')" id="tab-desc" class="tab-btn pb-3 text-xs sm:text-sm font-bold uppercase tracking-widest text-black border-b-2 border-black -mb-px">Deskripsi</button>
            <button onclick="switchTab('reviews')" id="tab-reviews" class="tab-btn pb-3 text-xs sm:text-sm font-bold uppercase tracking-widest text-gray-400 border-b-2 border-transparent hover:text-gray-700 transition-colors -mb-px">Ulasan ({{ $reviewCount }})</button>
        </div>
        <div id="content-desc" class="tab-content max-w-2xl">
            <p class="text-sm text-gray-700 leading-relaxed">{{ $product->description ?? 'Belum ada deskripsi.' }}</p>
        </div>
        <div id="content-reviews" class="tab-content hidden max-w-2xl space-y-6">
            @forelse($product->reviews as $review)
            <div class="border-b border-gray-100 pb-5 last:border-0">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-bold text-gray-900">{{ $review->user->name ?? 'Pengguna' }}</span>
                    <span class="text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex gap-0.5 mb-2">@for($i=1;$i<=5;$i++)<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{{$i<=$review->rating?'#f5a623':'#e5e7eb'}}" width="13" height="13"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor</div>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $review->comment }}</p>
            </div>
            @empty <p class="text-sm text-gray-400 italic">Belum ada ulasan.</p> @endforelse
        </div>
    </div>
</div>

{{-- TOAST --}}
<div id="cart-toast" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-black text-white text-sm font-medium px-5 py-3 rounded-full shadow-xl items-center gap-2 transition-all whitespace-nowrap">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="16" height="16" class="text-green-400"><path d="M20 6L9 17l-5-5" /></svg>
    <span>Produk ditambahkan!</span>
</div>

{{-- SIZE MODAL --}}
@if($product->size_chart_image)
<div id="modal-sizechart" class="hidden fixed inset-0 z-[70] bg-black/80 backdrop-blur-sm flex items-center justify-center px-4" onclick="if(event.target===this) closeSizeChart()">
    <div class="bg-white rounded-xl w-full max-w-lg overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-bold uppercase tracking-widest">Size Guide</h3>
            <button onclick="closeSizeChart()"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M18 6 6 18M6 6l12 12" /></svg></button>
        </div>
        <div class="p-4"><img src="{{ asset('storage/' . $product->size_chart_image) }}" class="w-full rounded-lg max-h-[70vh] object-contain"></div>
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
    .size-btn.active { border-color: #111 !important; background-color: #111; color: #fff; }
    .active-thumb { border-color: #111 !important; }
    .scrollbar-none::-webkit-scrollbar { display: none; }
</style>
@endpush

@push('scripts')
<script>
    // Data Galeri
    const images = [
        "{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('images/men-home.jpg') }}",
        @foreach($product->galleries as $gallery) "{{ asset('storage/' . $gallery->image) }}", @endforeach
    ];
    let currentImgIdx = 0;

    // Data Stok
    const stockData = {!! json_encode($product->stocks ? $product->stocks->pluck('quantity', 'size') : []) !!};
    let maxStock = 0;
    let selectedSize = null;
    let qty = 1;

    // ── Image Navigation (Smooth Fade & No Scroll Jump) ──
    function fadeToImage(src) {
        const img = document.getElementById('main-product-img');
        img.style.opacity = '0'; // Pudar perlahan
        setTimeout(() => {
            img.src = src;
            img.style.opacity = '1'; // Muncul perlahan
        }, 200); // Waktu jeda sesuai dengan durasi CSS (200ms)
    }

    function switchImage(src, btn, index) {
        currentImgIdx = index;
        fadeToImage(src);
        document.querySelectorAll('.thumb-btn').forEach(b => b.classList.remove('active-thumb'));
        btn.classList.add('active-thumb');
    }

    function prevImage() {
        currentImgIdx = (currentImgIdx === 0) ? images.length - 1 : currentImgIdx - 1;
        updateMainImage();
    }

    function nextImage() {
        currentImgIdx = (currentImgIdx === images.length - 1) ? 0 : currentImgIdx + 1;
        updateMainImage();
    }

    function updateMainImage() {
        const src = images[currentImgIdx];
        fadeToImage(src);

        const thumbs = document.querySelectorAll('.thumb-btn');
        thumbs.forEach(b => b.classList.remove('active-thumb'));
        
        const activeThumb = thumbs[currentImgIdx];
        activeThumb.classList.add('active-thumb');
        
        // Menggeser kontainer thumbnail HANYA secara horizontal, mencegah layar ikut loncat
        const container = document.getElementById('thumb-container');
        const scrollPos = activeThumb.offsetLeft - (container.clientWidth / 2) + (activeThumb.clientWidth / 2);
        container.scrollTo({ left: scrollPos, behavior: 'smooth' });
    }

    // ── Size & Qty ──
    function selectSize(size, btn) {
        selectedSize = size;
        document.getElementById('selected-size-label').textContent = size;
        document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('size-error').classList.add('hidden');

        maxStock = stockData[size] || 0;
        const info = document.getElementById('stock-info');
        info.classList.remove('hidden');
        if(maxStock > 0) {
            info.textContent = `✓ Stok tersedia (${maxStock} item)`;
            info.className = 'text-xs font-semibold text-green-600';
        } else {
            info.textContent = `✗ Stok habis`;
            info.className = 'text-xs font-semibold text-red-500';
        }
        qty = 1; document.getElementById('qty-display').textContent = 1;
    }

    function changeQty(delta) {
        if(!selectedSize) { document.getElementById('size-error').classList.remove('hidden'); return; }
        if(maxStock <= 0) return;
        qty = Math.max(1, Math.min(qty + delta, maxStock));
        document.getElementById('qty-display').textContent = qty;
    }

    // ── Actions ──
    function addToCart() {
        if(!selectedSize) { 
            document.getElementById('size-error').classList.remove('hidden');
            return; 
        }
        if(maxStock <= 0) { showToast('Maaf, stok ukuran ini habis.', true); return; }

        const btn = document.getElementById('btn-add-cart');
        const originalHTML = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16">
                <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
            </svg> Menambahkan...
        `;

        fetch('{{ route("pelanggan.keranjang.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            // Varian warna sudah dihapus murni dari sini
            body: JSON.stringify({ product_id: {{ $product->id }}, size: selectedSize, quantity: qty })
        })
        .then(r => r.json()).then(data => {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
            if(data.success) { showToast('Produk masuk keranjang!'); updateNavbarCartBadge(data.cart_count); }
            else { showToast('Gagal menambahkan produk.', true); }
        }).catch(() => {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
            showToast('Terjadi kesalahan.', true);
        });
    }

    function buyNow() {
        if(!selectedSize) { 
            document.getElementById('size-error').classList.remove('hidden');
            return; 
        }
        if(maxStock <= 0) { showToast('Maaf, stok ukuran ini habis.', true); return; }

        const btn = document.getElementById('btn-buy-now');
        const originalHTML = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16">
                <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
            </svg> Memproses...
        `;

        fetch('{{ route("pelanggan.keranjang.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ product_id: {{ $product->id }}, size: selectedSize, quantity: qty })
        })
        .then(r => r.json()).then(data => {
            if(data.success) {
                window.location.href = '{{ route("pelanggan.checkout.index") }}';
            } else {
                btn.disabled = false;
                btn.innerHTML = originalHTML;
                showToast('Gagal memproses.', true);
            }
        }).catch(() => {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
            showToast('Terjadi kesalahan.', true);
        });
    }

    // ── Zoom Logic (Improved for Trackpad/Gesture) ──
    let zoomScale = 1;
    let isDragging = false;
    let startX, startY, translateX = 0, translateY = 0;

    function openZoom(src) {
        const overlay = document.getElementById('zoom-overlay');
        const img = document.getElementById('zoom-img');
        img.src = src;
        zoomScale = 1; translateX = 0; translateY = 0;
        applyZoom();
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeZoom(e) {
        if(e && e.target === document.getElementById('zoom-img')) return;
        document.getElementById('zoom-overlay').style.display = 'none';
        document.body.style.overflow = '';
    }

    function zoomChange(delta) {
        zoomScale = Math.min(5, Math.max(0.5, zoomScale + delta));
        applyZoom();
    }

    function applyZoom() {
        const img = document.getElementById('zoom-img');
        img.style.transform = `scale(${zoomScale}) translate(${translateX}px, ${translateY}px)`;
        document.getElementById('zoom-level-label').textContent = Math.round(zoomScale * 100) + '%';
    }

    // Wheel/Trackpad Zoom & Pan
    document.getElementById('zoom-container').addEventListener('wheel', e => {
        e.preventDefault();
        const delta = e.deltaY > 0 ? -0.15 : 0.15; // Lebih smooth untuk trackpad
        zoomChange(delta);
    }, { passive: false });

    // Drag to Pan
    const zoomImg = document.getElementById('zoom-img');
    zoomImg.onmousedown = (e) => {
        if(zoomScale <= 1) return;
        isDragging = true;
        startX = e.clientX - translateX; startY = e.clientY - translateY;
    };
    window.onmousemove = (e) => {
        if(!isDragging) return;
        translateX = e.clientX - startX; translateY = e.clientY - startY;
        applyZoom();
    };
    window.onmouseup = () => isDragging = false;

    // Gesture (Pinch) for Mobile/Trackpad
    let initialDist = 0;
    zoomImg.ontouchstart = (e) => {
        if(e.touches.length === 2) initialDist = Math.hypot(e.touches[0].clientX - e.touches[1].clientX, e.touches[0].clientY - e.touches[1].clientY);
    };
    zoomImg.ontouchmove = (e) => {
        if(e.touches.length === 2) {
            e.preventDefault();
            const dist = Math.hypot(e.touches[0].clientX - e.touches[1].clientX, e.touches[0].clientY - e.touches[1].clientY);
            const scale = dist / initialDist;
            zoomScale = Math.min(5, Math.max(0.5, zoomScale * scale));
            initialDist = dist;
            applyZoom();
        }
    };

    function resetZoomLevel() { zoomScale = 1; translateX = 0; translateY = 0; applyZoom(); }

    // Misc
    function switchTab(tab) {
        ['desc', 'reviews'].forEach(t => {
            document.getElementById('content-'+t).classList.add('hidden');
            document.getElementById('tab-'+t).classList.replace('text-black', 'text-gray-400');
            document.getElementById('tab-'+t).classList.replace('border-black', 'border-transparent');
        });
        document.getElementById('content-'+tab).classList.remove('hidden');
        document.getElementById('tab-'+tab).classList.replace('text-gray-400', 'text-black');
        document.getElementById('tab-'+tab).classList.replace('border-transparent', 'border-black');
    }

    function showToast(msg, err=false) {
        const t = document.getElementById('cart-toast');
        t.classList.remove('hidden');
        t.querySelector('span').textContent = msg;
        t.className = `fixed bottom-6 left-1/2 -translate-x-1/2 z-50 text-white text-sm font-medium px-5 py-3 rounded-full shadow-xl flex items-center gap-2 transition-all ${err?'bg-red-600':'bg-black'}`;
        setTimeout(() => t.classList.add('hidden'), 2500);
    }

    function toggleWishlist() {
        @auth
        fetch('{{ route("pelanggan.wishlist.toggle") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ product_id: {{ $product->id }} })
        })
        .then(r => r.json()).then(data => {
            const btn = document.getElementById('btn-wishlist');
            const svg = btn.querySelector('svg');
            if (data.status === 'added') {
                btn.classList.remove('border-gray-200', 'text-gray-400', 'hover:border-red-300', 'hover:text-red-400');
                btn.classList.add('border-red-400', 'bg-white', 'text-red-500');
                svg.setAttribute('fill', '#ef4444'); svg.setAttribute('stroke', '#ef4444');
                showToast('Ditambahkan ke wishlist ❤️');
            } else {
                btn.classList.remove('border-red-400', 'bg-white', 'text-red-500');
                btn.classList.add('border-gray-200', 'text-gray-400', 'hover:border-red-300', 'hover:text-red-400');
                svg.setAttribute('fill', 'none'); svg.setAttribute('stroke', 'currentColor');
            }
        });
        @else
        window.location.href = '{{ route("login") }}';
        @endauth
    }

    function openSizeChart() { document.getElementById('modal-sizechart').classList.remove('hidden'); document.body.style.overflow='hidden'; }
    function closeSizeChart() { document.getElementById('modal-sizechart').classList.add('hidden'); document.body.style.overflow=''; }
    document.addEventListener('keydown', e => { if(e.key==='Escape'){ closeSizeChart(); closeZoom(); } });
</script>
@endpush