@extends('layouts.main')

@section('title', 'Shop — TANKEN')

@section('content')

{{-- ====== HERO BANNER ====== --}}
<section class="relative w-full h-56 md:h-72 overflow-hidden bg-black">
    <img src="{{ asset('images/men-home.jpg') }}" alt="Shop Banner"
        class="absolute inset-0 w-full h-full object-cover object-top opacity-50">
    <div class="relative z-10 h-full flex flex-col justify-end px-8 md:px-12 pb-8">
        <p class="text-xs text-gray-300 uppercase tracking-widest mb-1 font-medium">All Collections</p>
        <h1 class="text-5xl md:text-6xl font-extrabold text-white leading-none tracking-tight">Shop</h1>
        <p class="text-xs text-gray-400 mt-2" id="result-count">{{ $products->count() }} pieces found</p>
    </div>
</section>

{{-- ====== SEARCH BAR ====== --}}
<div class="border-b border-gray-200 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-3">
        <div class="flex items-center gap-3 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="1.6" width="16" height="16">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.35-4.35" />
            </svg>
            <input id="search-input" type="text" placeholder="Cari produk..."
                class="w-full text-sm text-gray-700 placeholder-gray-400 focus:outline-none bg-transparent py-1"
                oninput="filterProducts()">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="1.6" width="16" height="16">
                <line x1="21" y1="10" x2="3" y2="10" />
                <line x1="21" y1="6" x2="3" y2="6" />
                <line x1="21" y1="14" x2="9" y2="14" />
                <line x1="21" y1="18" x2="9" y2="18" />
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
                        <input type="checkbox" value="all" class="filter-gender accent-black w-3.5 h-3.5 cursor-pointer"
                            {{ !request('gender') ? 'checked' : '' }} onchange="filterProducts()">
                        <span class="text-sm text-gray-800 font-medium group-hover:text-black">Semua</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" value="women"
                            class="filter-gender accent-black w-3.5 h-3.5 cursor-pointer" {{ request('gender')=='women'
                            ? 'checked' : '' }} onchange="filterProducts()">
                        <span class="text-sm text-gray-600 group-hover:text-black">Wanita</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" value="men" class="filter-gender accent-black w-3.5 h-3.5 cursor-pointer"
                            {{ request('gender')=='men' ? 'checked' : '' }} onchange="filterProducts()">
                        <span class="text-sm text-gray-600 group-hover:text-black">Pria</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" value="unisex"
                            class="filter-gender accent-black w-3.5 h-3.5 cursor-pointer" {{ request('gender')=='unisex'
                            ? 'checked' : '' }} onchange="filterProducts()">
                        <span class="text-sm text-gray-600 group-hover:text-black">Unisex</span>
                    </label>
                </div>
            </div>

            {{-- Tipe --}}
            <div class="mb-7">
                <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-3">Tipe</p>
                <div class="flex flex-col gap-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" value="all" class="filter-style accent-black w-3.5 h-3.5 cursor-pointer"
                            checked onchange="filterProducts()">
                        <span class="text-sm text-gray-800 font-medium group-hover:text-black">Semua</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" value="panjang"
                            class="filter-style accent-black w-3.5 h-3.5 cursor-pointer" onchange="filterProducts()">
                        <span class="text-sm text-gray-600 group-hover:text-black">Celana Panjang</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" value="pendek"
                            class="filter-style accent-black w-3.5 h-3.5 cursor-pointer" onchange="filterProducts()">
                        <span class="text-sm text-gray-600 group-hover:text-black">Celana Pendek</span>
                    </label>
                </div>
            </div>

            {{-- Harga --}}
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-3">Harga</p>
                <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                    <span>Rp0</span>
                    <span id="price-max-label">Rp1.000.000</span>
                </div>
                <input type="range" id="price-range" min="0" max="2000000" value="2000000" step="50000"
                    class="w-full accent-black cursor-pointer"
                    oninput="updatePriceLabel(this.value); filterProducts();">
                <div class="flex items-center gap-2 mt-3">
                    <input type="number" value="0" min="0" max="2000000" placeholder="0"
                        class="w-full border border-gray-200 rounded px-2 py-1 text-xs text-gray-700 focus:outline-none focus:border-gray-400">
                    <span class="text-gray-400 text-xs">—</span>
                    <input type="number" id="price-input-max" value="2000000" min="0" max="2000000"
                        placeholder="2000000"
                        class="w-full border border-gray-200 rounded px-2 py-1 text-xs text-gray-700 focus:outline-none focus:border-gray-400"
                        oninput="document.getElementById('price-range').value=this.value; updatePriceLabel(this.value); filterProducts();">
                </div>
            </div>
        </aside>

        {{-- ====== PRODUCT GRID ====== --}}
        <div class="flex-1">
            <div id="product-grid" class="grid grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-10">
                @foreach ($products as $product)
                <div class="product-card group cursor-pointer" data-name="{{ strtolower($product->name) }}"
                    data-gender="{{ strtolower($product->category->name ?? 'unisex') }}" {{-- ✅ tambah strtolower --}}
                    data-style="{{ $product->type }}" {{-- ✅ ambil dari DB --}} data-price="{{ $product->price }}">

                    <a href="{{ route('pelanggan.produk.detail', $product->slug) }}">
                        <div class="overflow-hidden rounded-lg bg-gray-100 aspect-[3/4] mb-3">

                            @if(!empty($product->main_image))
                            <img src="{{ asset('storage/' . $product->main_image) }}"
                                class="product-img w-full h-full object-cover object-top">
                            @else
                            <img src="{{ asset('images/men-home.jpg') }}"
                                class="product-img w-full h-full object-cover object-top">
                            @endif

                        </div>

                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-0.5">
                            {{ $product->category->name ?? '-' }}
                        </p>

                        <h3 class="text-sm font-semibold text-gray-900 leading-snug">
                            {{ $product->name }}
                        </h3>

                        <div class="flex items-center justify-between mt-1">
                            <span class="text-sm font-bold text-gray-900">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                        </div>
                    </a>
                </div>
                @endforeach


            </div>
        </div>

        {{-- Empty state --}}
        <div id="empty-state" class="hidden text-center py-20 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="1.2" width="40" height="40" class="mx-auto mb-4 opacity-40">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.35-4.35" />
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
        const search = document.getElementById('search-input').value.toLowerCase();
        const maxPrice = parseFloat(document.getElementById('price-range').value);

        const genderBoxes = [...document.querySelectorAll('.filter-gender:checked')].map(cb => cb.value);
        const styleBoxes = [...document.querySelectorAll('.filter-style:checked')].map(cb => cb.value);

        const allGender = genderBoxes.includes('all');
        const allStyle = styleBoxes.includes('all');

        const cards = document.querySelectorAll('.product-card');
        let visible = 0;

        cards.forEach(card => {
            const name = card.dataset.name.toLowerCase();
            const gender = card.dataset.gender;
            const style = card.dataset.style;
            const price = parseFloat(card.dataset.price);

            const matchSearch = name.includes(search);
            const matchGender = allGender || genderBoxes.includes(gender);
            const matchStyle = allStyle || styleBoxes.includes(style);
            const matchPrice = price <= maxPrice;

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

    document.addEventListener('DOMContentLoaded', () => {
    @if(request('gender'))
        filterProducts();
    @endif
});
</script>
@endpush