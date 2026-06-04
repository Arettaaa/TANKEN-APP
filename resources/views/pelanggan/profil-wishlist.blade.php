@extends('layouts.akun-pelanggan')

@section('title', 'Wishlist Saya — TANKEN')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@push('akun-styles')
<style>
    /* Wishlist product card */
    .wishlist-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        background: #fff;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .wishlist-card:hover {
        transform: translateY(-4px);
    }

    .wishlist-img-wrap {
        aspect-ratio: 3/4;
        overflow: hidden;
        background: #f3f4f6;
        position: relative;
    }

    .wishlist-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .wishlist-card:hover .wishlist-img-wrap img {
        transform: scale(1.05);
    }

    /* Color Swatches (Display only on card) */
    .color-swatch-display {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        border: 1px solid #e5e7eb;
    }
    
    @media (min-width: 640px) {
        .color-swatch-display {
            width: 14px;
            height: 14px;
        }
    }

    /* Varian Button Style (Modal) */
    .variant-btn {
        border: 1px solid #e5e7eb;
        color: #374151;
        background: #fff;
        transition: all 0.2s;
    }

    .variant-btn.selected {
        border-color: #111;
        background: #111;
        color: #fff;
    }

    /* Modal Animation */
    .modal-overlay {
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal-content {
        transform: translateY(100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @media (min-width: 640px) {
        .modal-content {
            transform: scale(0.95);
            opacity: 0;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
    }

    .modal-overlay.active .modal-content {
        transform: translateY(0);
        opacity: 1;
    }

    @media (min-width: 640px) {
        .modal-overlay.active .modal-content {
            transform: scale(1);
        }
    }

    /* Hide scrollbar */
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endpush

@section('akun-content')

<div>
    {{-- Header --}}
    <div class="flex items-end justify-between mb-6 pb-4 border-b border-gray-200">
        <div>
            <p class="text-[10px] sm:text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Tersimpan</p>
            <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900">
                Wishlist Saya
            </h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                @if($wishlists->count() > 0)
                {{ $wishlists->count() }} item tersimpan
                @endif
            </p>
        </div>

        @if($wishlists->count() > 0)
        <a href="{{ route('pelanggan.katalog') ?? '#' }}"
            class="text-[10px] sm:text-xs font-bold tracking-widest uppercase text-gray-900 hover:text-gray-500 transition-colors flex items-center gap-1.5 pb-1">
            <span class="hidden sm:inline">Lanjut Belanja</span>
            <span class="sm:hidden">Belanja</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2.5" width="12" height="12">
                <path d="M5 12h14M12 5l7 7-7 7" />
            </svg>
        </a>
        @endif
    </div>

    @if($wishlists->count() > 0)
    {{-- Grid produk: Mobile 2 Kolom, Tablet 2 Kolom, Desktop 3 Kolom --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-8">
        @foreach($wishlists as $item)
        @php
        $wid = $item->product_id;
        $wslug = $item->product->slug ?? '';
        $wname = $item->product->name;
        $wprice = $item->product->price;
        $woldPrice = $item->product->original_price ?? null;
        $wdiscount = $item->product->discount_percent ?? null;
        $wimage = $item->product->main_image
        ? asset('storage/' . $item->product->main_image)
        : asset('images/men-home.jpg');
        $wcategory = $item->product->category->name ?? '';
        $wcolors = is_array($item->product->colors)
        ? $item->product->colors
        : (json_decode($item->product->colors, true) ?? []);
        $detailUrl = route('pelanggan.produk.detail', $wslug);
        @endphp

        <div class="wishlist-card group h-full" id="wishlist-item-{{ $wid }}">

            {{-- Gambar Produk --}}
            <a href="{{ $detailUrl }}" class="block mb-3 sm:mb-4 flex-shrink-0">
                <div class="wishlist-img-wrap rounded-md">
                    @if($wimage)
                    <img src="{{ $wimage }}" alt="{{ $wname }}" loading="lazy">
                    @else
                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                        <i class="fa-solid fa-image text-gray-300 text-3xl"></i>
                    </div>
                    @endif
                </div>
            </a>

            {{-- Info Produk --}}
            <div class="flex-1 flex flex-col px-1">
                
                {{-- Kategori (Fixed Height) --}}
                <div class="h-[14px] mb-1">
                    @if($wcategory)
                    <p class="text-[9px] text-gray-400 font-bold tracking-widest uppercase truncate">{{ $wcategory }}</p>
                    @endif
                </div>

                {{-- Judul Produk (Fixed Height: dipaksa maksimal 2 baris, lebihnya dipotong rapi) --}}
                <a href="{{ $detailUrl }}"
                    class="text-xs sm:text-sm font-semibold text-gray-900 hover:text-gray-600 transition-colors leading-snug block mb-1.5 sm:mb-2 line-clamp-2 h-[34px] sm:h-[40px] overflow-hidden">
                    {{ $wname }}
                </a>

                {{-- Area Harga & Diskon (Fixed Height) --}}
                <div class="flex flex-col justify-start h-[36px] sm:h-[40px] mb-2 sm:mb-3">
                    <span class="text-xs sm:text-sm font-extrabold text-gray-900 mb-0.5">
                        Rp {{ number_format($wprice, 0, ',', '.') }}
                    </span>

                    @if($woldPrice && $wdiscount)
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <span class="text-[10px] sm:text-xs text-gray-400 font-medium line-through truncate">
                            Rp {{ number_format($woldPrice, 0, ',', '.') }}
                        </span>
                        <span class="bg-black text-white text-[8px] sm:text-[9px] font-bold px-1.5 py-0.5 rounded tracking-wide leading-none">
                            {{ $wdiscount }}
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Varian Warna (Fixed Height) --}}
                <div class="flex items-center gap-1 sm:gap-1.5 h-[14px] sm:h-[16px] mb-3 sm:mb-5">
                    @if(count($wcolors) > 0)
                        @foreach($item->product->colors_hex as $color)
                        <span class="color-swatch-display"
                            style="background-color: {{ $color['hex'] }};"
                            title="{{ $color['name'] }}">
                        </span>
                        @endforeach
                    @endif
                </div>

                <div class="flex-grow"></div>

                {{-- Bagian Tombol Tambah & Hapus (Akan selalu sejajar karena konten di atasnya dikunci ukurannya) --}}
                <div class="flex items-center gap-1.5 sm:gap-2 mt-auto pt-3 sm:pt-4 border-t border-gray-100">
                    <button
                        onclick="openVariantModal({{ $wid }}, '{{ addslashes($wname) }}', {{ $wprice }}, {{ json_encode($wcolors) }}, '{{ $item->product->size_chart_image ? asset('storage/' . $item->product->size_chart_image) : '' }}')"
                        class="flex-1 h-[36px] sm:h-[44px] bg-black text-white text-[9px] sm:text-[10px] font-bold tracking-widest uppercase rounded-md hover:bg-gray-800 transition-colors">
                        TAMBAH
                    </button>
                    <button onclick="removeWishlist({{ $wid }}, '{{ addslashes($wname) }}')" title="Hapus"
                        class="w-[36px] sm:w-[44px] h-[36px] sm:h-[44px] flex-shrink-0 flex items-center justify-center border border-gray-200 rounded-md hover:bg-gray-50 hover:border-gray-300 transition-colors">
                        <i class="fa-regular fa-trash-can text-sm sm:text-lg" style="color: rgb(38, 37, 37);"></i>
                    </button>
                </div>
            </div>

        </div>
        @endforeach
    </div>
    @else
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="1.2" width="28" height="28" class="text-gray-300">
                <path
                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
            </svg>
        </div>
        <p class="font-bold text-gray-900 text-base mb-1">Belum ada item tersimpan</p>
        <p class="text-sm text-gray-400 mb-8 max-w-xs">Tekan ikon hati pada produk untuk menyimpannya di sini.</p>
        <a href="{{ route('pelanggan.katalog') ?? '#' }}"
            class="inline-flex items-center gap-2 bg-gray-900 text-white text-[10px] font-bold tracking-widest uppercase px-7 py-3.5 rounded-md hover:bg-gray-700 transition-colors">
            Jelajahi Produk
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2.5" width="12" height="12">
                <path d="M5 12h14M12 5l7 7-7 7" />
            </svg>
        </a>
    </div>
    @endif

</div>

{{-- ========================================== --}}
{{-- MODAL 1: PILIH VARIAN SEBELUM KE KERANJANG --}}
{{-- ========================================== --}}
<div id="variantModal"
    class="modal-overlay fixed inset-0 z-[100] flex items-end sm:items-center justify-center bg-black/60 backdrop-blur-sm">
    <div
        class="modal-content w-full sm:w-[500px] bg-white rounded-t-2xl sm:rounded-2xl p-6 sm:p-8 relative max-h-[90vh] overflow-y-auto">

        {{-- Close Button --}}
        <button onclick="closeVariantModal()"
            class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors text-gray-600">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        {{-- Info Produk Singkat --}}
        <div class="mb-6 pr-10">
            <h3 id="modalProductName" class="text-lg font-bold text-gray-900 mb-1">Nama Produk</h3>
            <p id="modalProductPrice" class="text-base font-extrabold text-gray-900">Rp 0</p>
        </div>

        <form id="addToCartForm" onsubmit="event.preventDefault(); submitAddToCart();">
            <input type="hidden" id="modalProductId" value="">

            {{-- Pilihan Warna --}}
            <div class="mb-6">
                <p class="text-[10px] font-bold tracking-widest text-gray-500 uppercase mb-3">
                    Warna &mdash; <span id="selectedColorText" class="text-gray-900">Pilih warna</span>
                </p>
                <div class="flex flex-wrap gap-2">
                    {{-- Tombol warna akan di-render via JS --}}
                </div>
                {{-- INLINE ERROR WARNA --}}
                <p id="errorColor" class="hidden text-xs text-red-500 mt-2 flex items-center gap-1.5 font-medium">
                    <i class="fa-solid fa-triangle-exclamation"></i> Pilih warna terlebih dahulu.
                </p>
            </div>

            {{-- Pilihan Ukuran & Size Guide --}}
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[10px] font-bold tracking-widest text-gray-500 uppercase">
                        Ukuran &mdash; <span id="selectedSizeText" class="text-gray-900">Pilih ukuran</span>
                    </p>
                    <button type="button" onclick="openSizeGuide()"
                        class="text-xs font-semibold text-gray-500 hover:text-black underline underline-offset-2">
                        Size Guide
                    </button>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="selectSize(this, 'XS')"
                        class="size-btn variant-btn w-11 h-11 rounded flex items-center justify-center text-xs font-semibold">XS</button>
                    <button type="button" onclick="selectSize(this, 'S')"
                        class="size-btn variant-btn w-11 h-11 rounded flex items-center justify-center text-xs font-semibold">S</button>
                    <button type="button" onclick="selectSize(this, 'M')"
                        class="size-btn variant-btn w-11 h-11 rounded flex items-center justify-center text-xs font-semibold">M</button>
                    <button type="button" onclick="selectSize(this, 'L')"
                        class="size-btn variant-btn w-11 h-11 rounded flex items-center justify-center text-xs font-semibold">L</button>
                    <button type="button" onclick="selectSize(this, 'XL')"
                        class="size-btn variant-btn w-11 h-11 rounded flex items-center justify-center text-xs font-semibold">XL</button>
                    <button type="button" onclick="selectSize(this, 'XXL')"
                        class="size-btn variant-btn w-11 h-11 rounded flex items-center justify-center text-xs font-semibold">XXL</button>
                </div>
                {{-- INLINE ERROR UKURAN --}}
                <p id="errorSize" class="hidden text-xs text-red-500 mt-2 flex items-center gap-1.5 font-medium">
                    <i class="fa-solid fa-triangle-exclamation"></i> Pilih ukuran terlebih dahulu.
                </p>
            </div>

            {{-- Pilihan Jumlah (- dan +) --}}
            <div class="mb-6">
                <p class="text-[10px] font-bold tracking-widest text-gray-500 uppercase mb-3">Jumlah</p>
                <div class="flex items-center border border-gray-200 rounded w-fit h-11">
                    <button type="button" onclick="updateQty(-1)"
                        class="w-10 h-full flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors"><i
                            class="fa-solid fa-minus text-xs"></i></button>
                    <input type="number" id="productQty" value="1" min="1" max="50"
                        class="w-12 h-full text-center text-sm font-bold text-gray-900 bg-transparent border-none outline-none focus:ring-0 appearance-none"
                        readonly>
                    <button type="button" onclick="updateQty(1)"
                        class="w-10 h-full flex items-center justify-center text-gray-500 hover:bg-gray-50 transition-colors"><i
                            class="fa-solid fa-plus text-xs"></i></button>
                </div>
            </div>

            {{-- INLINE ERROR KERANJANG PENUH --}}
            <p id="errorCartLimit"
                class="hidden text-xs text-red-500 mb-3 text-center flex items-center justify-center gap-1.5 font-medium">
                <i class="fa-solid fa-triangle-exclamation"></i> Ups! Keranjang kamu penuh (Maksimal 50 barang).
            </p>

            {{-- Submit Keranjang --}}
            <button type="submit" id="btnSubmitCart"
                class="w-full h-[48px] bg-black text-white text-[11px] font-bold tracking-widest uppercase rounded-md hover:bg-gray-800 transition-colors flex items-center justify-center gap-2 mt-2">
                <i class="fa-solid fa-cart-shopping text-sm"></i>
                TAMBAH KE KERANJANG
            </button>
        </form>
    </div>
</div>

{{-- ========================================== --}}
{{-- MODAL 2: SIZE GUIDE (BERUPA GAMBAR ADMIN) --}}
{{-- ========================================== --}}
<div id="sizeGuideModal"
    class="modal-overlay fixed inset-0 z-[110] flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="modal-content w-full max-w-lg mx-4 bg-white rounded-xl p-6 sm:p-8 relative">
        <button onclick="closeSizeGuide()"
            class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-black transition-colors">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <h3 class="text-lg font-bold text-gray-900 mb-4">Panduan Ukuran</h3>

        <div
            class="w-full flex justify-center items-center bg-gray-50 rounded-lg overflow-hidden border border-gray-100">
            <img src="https://via.placeholder.com/600x400.png?text=Gambar+Size+Guide+(Di-upload+Admin)"
                alt="Panduan Ukuran TANKEN" class="max-w-full h-auto object-contain">
        </div>
    </div>
</div>

{{-- MODAL: KONFIRMASI HAPUS WISHLIST --}}
<div id="modal-delete-wishlist" class="fixed inset-0 z-[200] hidden items-center justify-center opacity-0 transition-opacity duration-300 bg-black/60 backdrop-blur-sm">
    <div id="modal-delete-wishlist-box" class="bg-white rounded-md w-full max-w-sm mx-4 p-6 text-center shadow-2xl transform scale-95 transition-transform duration-300">
        <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-triangle-exclamation text-red-500 text-2xl"></i>
        </div>
        <h3 class="text-base font-bold text-gray-900 mb-1">Hapus dari Wishlist?</h3>
        <p class="text-sm font-semibold text-gray-800 mb-1" id="modal-delete-wishlist-name">—</p>
        <p class="text-xs text-red-500 mb-5">Produk ini akan dihapus dari wishlist kamu.</p>
        <div class="flex gap-3">
            <button type="button" onclick="closeDeleteWishlistModal()"
                class="flex-1 py-2.5 rounded-md border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button type="button" id="modal-delete-wishlist-confirm"
                class="flex-1 py-2.5 rounded-md bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition-colors shadow-sm">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

@endsection

@push('akun-scripts')
<script>
   let deleteTargetId = null;

function removeWishlist(productId, productName) {
    deleteTargetId = productId;
    document.getElementById('modal-delete-wishlist-name').textContent = '"' + productName + '"';
    
    const modal = document.getElementById('modal-delete-wishlist');
    const box = document.getElementById('modal-delete-wishlist-box');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        box.classList.remove('scale-95');
    }, 10);
}

function closeDeleteWishlistModal() {
    const modal = document.getElementById('modal-delete-wishlist');
    const box = document.getElementById('modal-delete-wishlist-box');
    modal.classList.add('opacity-0');
    box.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        deleteTargetId = null;
    }, 300);
}

document.getElementById('modal-delete-wishlist-confirm').addEventListener('click', function() {
    if (!deleteTargetId) return;

    fetch(`/akun/wishlist/${deleteTargetId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: '_method=DELETE'
    })
    .then(r => r.json())
    .then(() => {
        closeDeleteWishlistModal();
        const card = document.getElementById('wishlist-item-' + deleteTargetId);
        card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        card.style.opacity = '0';
        card.style.transform = 'scale(0.9)';
        setTimeout(() => {
            card.remove();
            if (!document.querySelector('.wishlist-card')) location.reload();
        }, 300);
    });
});

document.getElementById('modal-delete-wishlist').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteWishlistModal();
});

    const variantModal = document.getElementById('variantModal');
    let selectedColor = null;
    let selectedSize = null;
function openVariantModal(id, name, price, colors, sizeChartUrl) {
    selectedColor = null;
    selectedSize = null;
    document.getElementById('selectedColorText').innerText = 'Pilih warna';
    document.getElementById('selectedSizeText').innerText = 'Pilih ukuran';
    document.getElementById('productQty').value = 1;

    document.querySelectorAll('.color-btn, .size-btn').forEach(btn => {
        btn.classList.remove('selected');
    });

    document.getElementById('errorColor').classList.add('hidden');
    document.getElementById('errorSize').classList.add('hidden');
    document.getElementById('errorCartLimit').classList.add('hidden');

    // Render warna dinamis
    const colorContainer = document.querySelector('#addToCartForm .flex.flex-wrap.gap-2:first-of-type');
    colorContainer.innerHTML = '';
    if (colors && colors.length > 0) {
        colors.forEach(color => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'color-btn variant-btn px-4 py-2 rounded text-xs font-semibold';
            btn.innerText = color;
            btn.onclick = function() { selectColor(this, color); };
            colorContainer.appendChild(btn);
        });
        colorContainer.closest('.mb-6').classList.remove('hidden');
    } else {
        colorContainer.closest('.mb-6').classList.add('hidden');
        selectedColor = 'default';
    }

    // Update size guide
    const sizeGuideImg = document.querySelector('#sizeGuideModal img');
    const sizeGuideBtn = document.querySelector('[onclick="openSizeGuide()"]');
    if (sizeChartUrl) {
        sizeGuideImg.src = sizeChartUrl;
        sizeGuideBtn.classList.remove('hidden');
    } else {
        sizeGuideBtn.classList.add('hidden');
    }

    document.getElementById('modalProductId').value = id;
    document.getElementById('modalProductName').innerText = name;
    document.getElementById('modalProductPrice').innerText = 'Rp ' + price.toLocaleString('id-ID');

    variantModal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

    function closeVariantModal() {
        variantModal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Klik warna
    function selectColor(btn, color) {
        document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        selectedColor = color;
        document.getElementById('selectedColorText').innerText = color;
        
        // Hilangkan error merah kalau sudah pilih
        document.getElementById('errorColor').classList.add('hidden');
    }

    // Klik ukuran
    function selectSize(btn, size) {
        document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        selectedSize = size;
        document.getElementById('selectedSizeText').innerText = size;
        
        // Hilangkan error merah kalau sudah pilih
        document.getElementById('errorSize').classList.add('hidden');
    }

    function updateQty(change) {
        const input = document.getElementById('productQty');
        let newVal = parseInt(input.value) + change;
        if(newVal >= 1 && newVal <= 50) {
            input.value = newVal;
        }
        // Hilangkan error keranjang penuh saat user ubah angka (opsional)
        document.getElementById('errorCartLimit').classList.add('hidden');
    }

    function submitAddToCart() {
    let hasError = false;

    if (!selectedColor) {
        document.getElementById('errorColor').classList.remove('hidden');
        hasError = true;
    }
    if (!selectedSize) {
        document.getElementById('errorSize').classList.remove('hidden');
        hasError = true;
    }
    if (hasError) return;

    const productId = document.getElementById('modalProductId').value;
    const qty = parseInt(document.getElementById('productQty').value);

    const btnSubmit = document.getElementById('btnSubmitCart');
    const originalHTML = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menambahkan...';

    // Pakai URLSearchParams biar Laravel bisa baca tanpa perlu ubah controller
    const params = new URLSearchParams();
    params.append('product_id', productId);
    params.append('color', selectedColor);
    params.append('size', selectedSize);
    params.append('quantity', qty);
    params.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("pelanggan.keranjang.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: params.toString()
    })
    .then(r => {
        if (!r.ok) throw new Error('Server error: ' + r.status);
        return r.json();
    })
    .then(data => {
        if (data.success) {
            btnSubmit.innerHTML = '<i class="fa-solid fa-check"></i> BERHASIL DITAMBAHKAN';
            btnSubmit.classList.replace('bg-black', 'bg-emerald-600');
            btnSubmit.classList.replace('hover:bg-gray-800', 'hover:bg-emerald-700');

            // Update badge keranjang jika ada di navbar
            const badge = document.getElementById('cart-count-badge');
            if (badge && data.cart_count !== undefined) {
                badge.textContent = data.cart_count;
            }

            setTimeout(() => {
                btnSubmit.innerHTML = originalHTML;
                btnSubmit.classList.replace('bg-emerald-600', 'bg-black');
                btnSubmit.classList.replace('hover:bg-emerald-700', 'hover:bg-gray-800');
                btnSubmit.disabled = false;
                closeVariantModal();
            }, 1200);
        }
    })
    .catch(err => {
        console.error(err);
        btnSubmit.innerHTML = originalHTML;
        btnSubmit.disabled = false;
        alert('Gagal menambahkan ke keranjang, coba lagi.');
    });
}
    // === LOGIKA MODAL SIZE GUIDE ===
    const sizeGuideModal = document.getElementById('sizeGuideModal');
    function openSizeGuide() {
        sizeGuideModal.classList.add('active');
    }
    function closeSizeGuide() {
        sizeGuideModal.classList.remove('active');
    }

    window.onclick = function(event) {
        if (event.target == variantModal) closeVariantModal();
        if (event.target == sizeGuideModal) closeSizeGuide();
    }
</script>
@endpush