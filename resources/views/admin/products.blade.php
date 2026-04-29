@extends('layouts.admin')

@section('title', 'Product Management — TANKEN')
@section('page-title', 'Product Management')
@section('breadcrumb', 'Home / Products')

@push('styles')
<style>
    /* Toggle switch Merah/Hijau */
    .toggle-switch {
        position: relative;
        width: 44px;
        height: 24px;
        cursor: pointer;
        display: inline-block;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        inset: 0;
        background: #ef4444;
        border-radius: 24px;
        transition: .3s;
    }

    /* Merah Default (Hidden) */
    .toggle-slider:before {
        content: '';
        position: absolute;
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background: white;
        border-radius: 50%;
        transition: .3s;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    input:checked+.toggle-slider {
        background: #16a34a;
    }

    /* Hijau Active (Visible) */
    input:checked+.toggle-slider:before {
        transform: translateX(20px);
    }

    /* Badges */
    .cat-pendek {
        background: #eff6ff;
        color: #2563eb;
    }

    .cat-panjang {
        background: #f0fdf4;
        color: #16a34a;
    }

    .cat-badge {
        padding: 4px 12px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 4px;
    }

    .rating-badge {
        background: #fefce8;
        color: #854d0e;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Stock color */
    .stock-low {
        color: #ef4444;
        font-weight: 700;
    }

    .stock-medium {
        color: #f97316;
        font-weight: 700;
    }

    .stock-high {
        color: #16a34a;
        font-weight: 700;
    }

    /* Scrollable modal body */
    .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }

    .modal-body::-webkit-scrollbar {
        width: 6px;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background-color: #e5e7eb;
        border-radius: 4px;
    }

    /* Custom Dropdown Filter */
    .dropdown-item:hover {
        background-color: #f9fafb;
    }

    .custom-dropdown-btn {
        appearance: none;
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 8px 14px;
        font-size: 0.875rem;
        color: #4b5563;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .custom-dropdown-btn:focus {
        border-color: #111;
        outline: none;
    }
</style>
@endpush

@section('content')

<div class="flex items-center justify-between mb-5">
    <p class="text-sm text-gray-500">{{ $products->total() }} products</p>
    <button onclick="openModal('modal-add')" class="flex items-center gap-2 bg-[#111111] text-white text-sm font-semibold px-4 py-2.5 rounded-md hover:bg-black transition-colors shadow-sm">
        <i class="fa-solid fa-plus"></i> Add Product
    </button>
</div>

{{-- ===== FILTER & SEARCH ===== --}}
<form id="filterForm" method="GET" action="{{ route('admin.products.index') }}" class="bg-white rounded-md border border-gray-100 p-4 mb-5 flex flex-wrap items-center gap-3 shadow-sm">

    {{-- Custom Dropdown: Kategori --}}
    <div class="relative custom-dropdown">
        <button type="button" onclick="toggleFilterMenu('categoryMenu')" class="custom-dropdown-btn min-w-[140px]">
            <span id="label-category">{{ request('category') == '1' ? 'Men' : (request('category') == '2' ? 'Women' : 'Semua Kategori') }}</span>
            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
        </button>
        <div id="categoryMenu" class="drop-menu absolute left-0 w-full mt-1 bg-white border border-gray-100 rounded-md shadow-lg hidden z-30 overflow-hidden">
            <ul class="text-sm text-gray-700">
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('category', '', 'Semua Kategori')">Semua Kategori</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('category', '1', 'Men')">Men</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('category', '2', 'Women')">Women</li>
            </ul>
        </div>
        <input type="hidden" name="category" id="input-category" value="{{ request('category') }}">
    </div>

    {{-- Custom Dropdown: Tipe --}}
    <div class="relative custom-dropdown">
        <button type="button" onclick="toggleFilterMenu('typeMenu')" class="custom-dropdown-btn min-w-[130px]">
            <span id="label-type">{{ request('type') == 'pendek' ? 'Pendek' : (request('type') == 'panjang' ? 'Panjang' : 'Semua Tipe') }}</span>
            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
        </button>
        <div id="typeMenu" class="drop-menu absolute left-0 w-full mt-1 bg-white border border-gray-100 rounded-md shadow-lg hidden z-30 overflow-hidden">
            <ul class="text-sm text-gray-700">
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('type', '', 'Semua Tipe')">Semua Tipe</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('type', 'pendek', 'Pendek')">Pendek</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('type', 'panjang', 'Panjang')">Panjang</li>
            </ul>
        </div>
        <input type="hidden" name="type" id="input-type" value="{{ request('type') }}">
    </div>

    {{-- Custom Dropdown: Sort --}}
    <div class="relative custom-dropdown">
        <button type="button" onclick="toggleFilterMenu('sortMenu')" class="custom-dropdown-btn min-w-[150px]">
            <span id="label-sort">
                @if(request('sort') == 'price-asc') Harga Terendah
                @elseif(request('sort') == 'price-desc') Harga Tertinggi
                @elseif(request('sort') == 'stock-asc') Stok Sedikit
                @elseif(request('sort') == 'stock-desc') Stok Terbanyak
                @else Urutkan @endif
            </span>
            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
        </button>
        <div id="sortMenu" class="drop-menu absolute left-0 w-48 mt-1 bg-white border border-gray-100 rounded-md shadow-lg hidden z-30 overflow-hidden">
            <ul class="text-sm text-gray-700">
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('sort', '', 'Urutkan')">Default</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('sort', 'price-asc', 'Harga Terendah')">Harga: Rendah ke Tinggi</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('sort', 'price-desc', 'Harga Tertinggi')">Harga: Tinggi ke Rendah</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('sort', 'stock-asc', 'Stok Sedikit')">Stok: Paling Sedikit</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('sort', 'stock-desc', 'Stok Terbanyak')">Stok: Paling Banyak</li>
            </ul>
        </div>
        <input type="hidden" name="sort" id="input-sort" value="{{ request('sort') }}">
    </div>

    <div class="flex-1 relative min-w-[180px]">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau SKU..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-md text-sm focus:outline-none focus:border-gray-900 transition-colors">
        {{-- Tombol submit tersembunyi untuk input search (tekan enter) --}}
        <button type="submit" class="hidden"></button>
    </div>

    <button type="button" class="flex items-center gap-2 border border-gray-200 rounded-md px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
        <i class="fa-solid fa-download"></i> Export Excel
    </button>
</form>

{{-- ===== TABLE PRODUK ===== --}}
<div class="bg-white rounded-md border border-gray-100 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-[#111111] text-white text-[10px] font-bold tracking-widest uppercase">
                    <th class="px-5 py-4">Product</th>
                    <th class="px-4 py-4">SKU</th>
                    <th class="px-4 py-4">Category</th>
                    <th class="px-4 py-4">Price</th>
                    <th class="px-4 py-4">Stock</th>
                    <th class="px-4 py-4">Rating</th>
                    <th class="px-4 py-4">Status</th>
                    <th class="px-4 py-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                @php
                $totalStock = $product->stocks->sum('quantity') + $product->stock;
                $stockClass = $totalStock <= 20 ? 'stock-low' : ($totalStock <=40 ? 'stock-medium' : 'stock-high' );
                    $avgRating=$product->reviews->avg('rating') ?? 0;
                    $reviewCount = $product->reviews->count();
                    @endphp
                    <tr class="hover:bg-gray-50/60 transition-colors group">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-4">
                                @if($product->main_image)
                                <img src="{{ Storage::url($product->main_image) }}" class="w-12 h-12 rounded object-cover border border-gray-100" alt="{{ $product->name }}">
                                @else
                                <div class="w-12 h-12 rounded bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-image text-gray-300 text-lg"></i>
                                </div>
                                @endif
                                <span class="font-semibold text-gray-900 text-sm">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 font-mono text-xs text-gray-500 font-medium">{{ $product->sku }}</td>
                        <td class="px-4 py-4">
                            <span class="cat-badge {{ $product->type === 'pendek' ? 'cat-pendek' : 'cat-panjang' }}">{{ ucfirst($product->type) }}</span>
                        </td>
                        <td class="px-4 py-4 text-sm font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-4 text-sm {{ $stockClass }}">{{ $totalStock }}</td>
                        <td class="px-4 py-4">
                            @if($reviewCount > 0)
                            <span class="rating-badge">
                                <i class="fa-solid fa-star text-[10px]" style="color: #f59e0b;"></i>
                                {{ number_format($avgRating, 1) }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400">–</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <label class="toggle-switch" title="{{ $product->is_active ? 'Terlihat Pelanggan' : 'Disembunyikan' }}">
                                <input type="checkbox" {{ $product->is_active ? 'checked' : '' }} onchange="toggleStatus({{ $product->id }}, this.checked)">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <button type="button" onclick="openModal('modal-view-{{ $product->id }}')" class="text-gray-400 hover:text-black transition-colors" title="Lihat detail">
                                    <i class="fa-regular fa-eye text-[15px]"></i>
                                </button>
                                <button type="button" onclick="openModal('modal-edit-{{ $product->id }}')" class="text-gray-400 hover:text-black transition-colors" title="Edit">
                                    <i class="fa-regular fa-pen-to-square text-[15px]"></i>
                                </button>
                                <button type="button" onclick="openModal('modal-delete-{{ $product->id }}')" class="text-gray-400 hover:text-red-600 transition-colors" title="Hapus">
                                    <i class="fa-regular fa-trash-can text-[15px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="py-16 text-center text-gray-400">
                                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-50 mb-3">
                                    <i class="fa-solid fa-box-open text-xl text-gray-300"></i>
                                </div>
                                <p class="text-sm font-medium">Tidak ada produk ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $products->withQueryString()->links() }}
</div>

{{-- ===================== MODALS PER PRODUCT ===================== --}}
@foreach($products as $product)
@php
$totalStock = $product->stocks->sum('quantity') + $product->stock;
$stockClass = $totalStock <= 20 ? 'stock-low' : ($totalStock <=40 ? 'stock-medium' : 'stock-high' );
    $colorsArray=is_array($product->colors) ? $product->colors : json_decode($product->colors, true) ?? [];
    $sizesArray = is_array($product->sizes) ? $product->sizes : json_decode($product->sizes, true) ?? [];
    @endphp

    {{-- 1. MODAL: VIEW DETAIL --}}
    <div id="modal-view-{{ $product->id }}" class="fixed inset-0 z-[60] hidden items-center justify-center opacity-0 transition-opacity duration-300 bg-black/60 backdrop-blur-sm" onclick="closeOnBackdrop(event,'modal-view-{{ $product->id }}')">
        <div class="bg-white rounded-md w-full max-w-lg mx-4 shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="px-6 pt-6 pb-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Detail Produk</h2>
                <button type="button" onclick="closeModal('modal-view-{{ $product->id }}')" class="text-gray-400 hover:text-gray-700">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <div class="modal-body px-6 py-5">
                <div class="flex gap-3 mb-5 overflow-x-auto pb-2">
                    @if($product->main_image)
                    <img src="{{ Storage::url($product->main_image) }}" class="w-24 h-24 rounded object-cover border border-gray-200 flex-shrink-0" alt="{{ $product->name }}">
                    @endif
                    
                    {{-- TAMBAHAN: Loop untuk nampilin foto tambahan di modal View --}}
                    {{-- Note: Ganti "$product->galleries" sesuai nama relasi/kolom kamu di DB ya --}}
                    @if(isset($product->galleries) && $product->galleries->count() > 0)
                        @foreach($product->galleries as $gallery)
                            <img src="{{ Storage::url($gallery->image) }}" class="w-24 h-24 rounded object-cover border border-gray-200 flex-shrink-0" alt="Additional Image">
                        @endforeach
                    @endif
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-start py-2 border-b border-gray-100">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Produk</span>
                        <span class="text-sm font-semibold text-gray-900 text-right max-w-[60%]">{{ $product->name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">SKU</span>
                        <span class="text-sm font-mono text-gray-700">{{ $product->sku }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kategori</span>
                        <span class="cat-badge {{ $product->type === 'pendek' ? 'cat-pendek' : 'cat-panjang' }}">{{ ucfirst($product->type) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Harga</span>
                        <span class="text-sm font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Stok Total</span>
                        <span class="text-sm font-semibold {{ $stockClass }}">{{ $totalStock }} pcs</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Ukuran</span>
                        <span class="text-sm font-semibold text-gray-700">{{ implode(', ', $sizesArray) ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Warna</span>
                        <span class="text-sm font-semibold text-gray-700">{{ implode(', ', $colorsArray) ?: '-' }}</span>
                    </div>
                    @if($product->description)
                    <div class="py-2.5">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1.5">Deskripsi</span>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $product->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                <button type="button" onclick="closeModal('modal-view-{{ $product->id }}')" class="w-full py-2.5 rounded-md border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">Tutup</button>
            </div>
        </div>
    </div>

    {{-- 2. MODAL: EDIT PRODUCT --}}
    <div id="modal-edit-{{ $product->id }}" class="fixed inset-0 z-[60] hidden items-center justify-center opacity-0 transition-opacity duration-300 bg-black/60 backdrop-blur-sm" onclick="closeOnBackdrop(event,'modal-edit-{{ $product->id }}')">
        <div class="bg-white rounded-md shadow-2xl w-full max-w-lg mx-4 transform scale-95 transition-transform duration-300">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">Edit Produk</h2>
            </div>
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body px-6 py-5 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm font-mono focus:border-black outline-none transition-colors" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori</label>
                            <select name="category_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors bg-white">
                                <option value="1" {{ $product->category_id == 1 ? 'selected' : '' }}>Men</option>
                                <option value="2" {{ $product->category_id == 2 ? 'selected' : '' }}>Women</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga (Rp)</label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe</label>
                            <select name="type" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors bg-white">
                                <option value="pendek" {{ $product->type === 'pendek'  ? 'selected' : '' }}>Pendek</option>
                                <option value="panjang" {{ $product->type === 'panjang' ? 'selected' : '' }}>Panjang</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                        <textarea name="description" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors resize-y" rows="3">{{ old('description', $product->description) }}</textarea>
                    </div>

                    {{-- Edit Ukuran (Chips) --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ukuran (Size)</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                            @php $sel = in_array($size, $sizesArray); @endphp
                            <button type="button" data-value="{{ $size }}" onclick="toggleChip(this, 'edit-sizes-{{ $product->id }}')" class="px-4 py-1.5 border border-gray-200 rounded-md text-sm transition-colors {{ $sel ? 'bg-[#111111] text-white border-[#111111]' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">{{ $size }}</button>
                            @endforeach
                        </div>
                        <input type="hidden" name="sizes" id="hidden-edit-sizes-{{ $product->id }}" value="{{ implode(',', $sizesArray) }}">
                    </div>

                    {{-- Edit Warna (Chips) --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Warna</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Black', 'Stone Grey', 'Indigo', 'Petrol', 'Pink Fusia', 'Sage', 'Olive'] as $color)
                            @php $sel = in_array($color, $colorsArray); @endphp
                            <button type="button" data-value="{{ $color }}" onclick="toggleChip(this, 'edit-colors-{{ $product->id }}')" class="px-4 py-1.5 border border-gray-200 rounded-md text-sm transition-colors {{ $sel ? 'bg-[#111111] text-white border-[#111111]' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">{{ $color }}</button>
                            @endforeach
                        </div>
                        <input type="hidden" name="colors" id="hidden-edit-colors-{{ $product->id }}" value="{{ implode(',', $colorsArray) }}">
                    </div>

                    {{-- Uploads --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Produk Utama</label>
                            @if($product->main_image)
                            <img src="{{ Storage::url($product->main_image) }}" class="w-12 h-12 mb-2 rounded object-cover border border-gray-200" alt="">
                            @endif
                            <div class="w-full border-2 border-dashed border-gray-200 rounded-md p-4 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors" onclick="document.getElementById('edit-image-input-{{ $product->id }}').click()">
                                {{-- PERUBAHAN JS: nambahin gap-2 di parent div ini --}}
                                <div id="edit-upload-preview-{{ $product->id }}" class="flex flex-wrap gap-2 justify-center w-full"></div>
                                <div id="edit-upload-placeholder-{{ $product->id }}" class="text-center">
                                    <i class="fa-solid fa-image text-gray-300 text-xl block mb-1"></i>
                                    <span class="text-[10px] font-medium text-gray-400">Ganti Foto</span>
                                </div>
                                <input type="file" id="edit-image-input-{{ $product->id }}" name="main_image" accept="image/*" class="hidden" onchange="previewImages(this,'edit-upload-preview-{{ $product->id }}','edit-upload-placeholder-{{ $product->id }}')">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Size Chart <span class="text-xs font-normal text-gray-400">(Opsional)</span></label>
                            <div class="w-full border-2 border-dashed border-gray-200 rounded-md p-4 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors" onclick="document.getElementById('edit-sizechart-input-{{ $product->id }}').click()">
                                {{-- PERUBAHAN JS: nambahin gap-2 --}}
                                <div id="edit-sz-preview-{{ $product->id }}" class="flex flex-wrap gap-2 justify-center w-full"></div>
                                <div id="edit-sz-placeholder-{{ $product->id }}" class="text-center">
                                    <i class="fa-solid fa-ruler-combined text-gray-300 text-xl block mb-1"></i>
                                    <span class="text-[10px] font-medium text-gray-400">Upload Size Guide</span>
                                </div>
                                <input type="file" id="edit-sizechart-input-{{ $product->id }}" name="size_chart_image" accept="image/*" class="hidden" onchange="previewImages(this,'edit-sz-preview-{{ $product->id }}','edit-sz-placeholder-{{ $product->id }}')">
                            </div>
                        </div>
                    </div>

                    {{-- TAMBAHAN: Upload Multiple Gambar di Modal Edit --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Tambahan <span class="text-xs font-normal text-gray-400">(Bisa pilih lebih dari satu)</span></label>
                        <div class="w-full border-2 border-dashed border-gray-200 rounded-md p-4 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors" onclick="document.getElementById('edit-gallery-input-{{ $product->id }}').click()">
                            <div id="edit-gallery-preview-{{ $product->id }}" class="flex flex-wrap gap-2 justify-center w-full"></div>
                            <div id="edit-gallery-placeholder-{{ $product->id }}" class="text-center">
                                <i class="fa-solid fa-images text-gray-300 text-xl block mb-1"></i>
                                <span class="text-[10px] font-medium text-gray-400">Upload Foto Tambahan</span>
                            </div>
                            <input type="file" id="edit-gallery-input-{{ $product->id }}" name="additional_images[]" accept="image/*" multiple class="hidden" onchange="previewImages(this,'edit-gallery-preview-{{ $product->id }}','edit-gallery-placeholder-{{ $product->id }}')">
                        </div>
                    </div>

                </div>
                <div class="px-6 py-5 border-t border-gray-100 flex gap-3">
                    <button type="button" onclick="closeModal('modal-edit-{{ $product->id }}')" class="flex-1 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-md text-sm font-bold hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-[#111111] text-white rounded-md text-sm font-bold hover:bg-black transition-colors shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- 3. MODAL: DELETE CONFIRM --}}
    <div id="modal-delete-{{ $product->id }}" class="fixed inset-0 z-[60] hidden items-center justify-center opacity-0 transition-opacity duration-300 bg-black/60 backdrop-blur-sm" onclick="closeOnBackdrop(event,'modal-delete-{{ $product->id }}')">
        <div class="bg-white rounded-md w-full max-w-sm mx-4 p-6 text-center shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1">Hapus Produk?</h3>
            <p class="text-sm font-semibold text-gray-800 mb-1">"{{ $product->name }}"</p>
            <p class="text-xs text-red-500 mb-5">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('modal-delete-{{ $product->id }}')" class="flex-1 py-2.5 rounded-md border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-2.5 rounded-md bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition-colors shadow-sm">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    {{-- ===================== MODAL: ADD PRODUCT ===================== --}}
    <div id="modal-add" class="fixed inset-0 z-[60] hidden items-center justify-center opacity-0 transition-opacity duration-300 bg-black/60 backdrop-blur-sm" onclick="closeOnBackdrop(event,'modal-add')">
        <div class="bg-white rounded-md shadow-2xl w-full max-w-lg mx-4 transform scale-95 transition-transform duration-300">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">Tambah Produk Baru</h2>
                <button type="button" onclick="closeModal('modal-add')" class="text-gray-400 hover:text-gray-700"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-6 py-5 space-y-5">

                    {{-- Area Error Validasi (jika ada) --}}
                    @if ($errors->any())
                    <div class="bg-red-50 text-red-600 text-xs p-3 rounded-md border border-red-100 mb-2">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors" placeholder="Masukkan nama produk" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">SKU <span class="text-red-500">*</span></label>
                            <input type="text" name="sku" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm font-mono focus:border-black outline-none transition-colors" placeholder="mis. TKN-PDK-001" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                            <select name="category_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors bg-white" required>
                                <option value="" disabled selected>Pilih kategori</option>
                                <option value="1">Men</option>
                                <option value="2">Women</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="price" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors" placeholder="0" min="0" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe <span class="text-red-500">*</span></label>
                            <select name="type" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors bg-white" required>
                                <option value="pendek">Pendek</option>
                                <option value="panjang">Panjang</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stok Awal <span class="text-red-500">*</span></label>
                            <input type="number" name="initial_stock" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors" placeholder="20" min="0" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                        <textarea name="description" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors resize-y" rows="3" placeholder="Masukkan deskripsi produk"></textarea>
                    </div>

                    {{-- Ukuran (Chips) --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ukuran (Size)</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                            <button type="button" data-value="{{ $size }}" onclick="toggleChip(this, 'add-sizes')" class="px-4 py-1.5 bg-gray-50 border border-gray-200 rounded-md text-sm text-gray-600 hover:bg-gray-100 transition-colors">{{ $size }}</button>
                            @endforeach
                        </div>
                        <input type="hidden" name="sizes" id="hidden-add-sizes" value="">
                    </div>

                    {{-- Warna (Chips) --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Warna</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Black', 'Stone Grey', 'Indigo', 'Petrol', 'Pink Fusia', 'Sage', 'Olive'] as $color)
                            <button type="button" data-value="{{ $color }}" onclick="toggleChip(this, 'add-colors')" class="px-4 py-1.5 bg-gray-50 border border-gray-200 rounded-md text-sm text-gray-600 hover:bg-gray-100 transition-colors">{{ $color }}</button>
                            @endforeach
                        </div>
                        <input type="hidden" name="colors" id="hidden-add-colors" value="">
                    </div>

                    {{-- Uploads (Foto Utama & Size Chart) --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Utama <span class="text-red-500">*</span></label>
                            <div class="w-full border-2 border-dashed border-gray-200 rounded-md p-5 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors" onclick="document.getElementById('add-image-input').click()">
                                {{-- PERUBAHAN JS: nambahin gap-2 --}}
                                <div id="add-upload-preview" class="flex flex-wrap gap-2 justify-center w-full"></div>
                                <div id="add-upload-placeholder" class="text-center">
                                    <i class="fa-solid fa-image text-gray-300 text-2xl block mb-2"></i>
                                    <span class="text-xs font-medium text-gray-400">Upload Foto</span>
                                </div>
                                <input type="file" id="add-image-input" name="main_image" accept="image/*" class="hidden" onchange="previewImages(this,'add-upload-preview','add-upload-placeholder')">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Size Chart <span class="text-xs font-normal text-gray-400">(Opsional)</span></label>
                            <div class="w-full border-2 border-dashed border-gray-200 rounded-md p-5 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors" onclick="document.getElementById('add-sizechart-input').click()">
                                {{-- PERUBAHAN JS: nambahin gap-2 --}}
                                <div id="add-sz-preview" class="flex flex-wrap gap-2 justify-center w-full"></div>
                                <div id="add-sz-placeholder" class="text-center">
                                    <i class="fa-solid fa-ruler-combined text-gray-300 text-2xl block mb-2"></i>
                                    <span class="text-xs font-medium text-gray-400">Upload Guide</span>
                                </div>
                                <input type="file" id="add-sizechart-input" name="size_chart_image" accept="image/*" class="hidden" onchange="previewImages(this,'add-sz-preview','add-sz-placeholder')">
                            </div>
                        </div>
                    </div>

                    {{-- TAMBAHAN: Upload Multiple Gambar di Modal Add --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Tambahan <span class="text-xs font-normal text-gray-400">(Bisa pilih lebih dari satu)</span></label>
                        <div class="w-full border-2 border-dashed border-gray-200 rounded-md p-5 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors" onclick="document.getElementById('add-gallery-input').click()">
                            <div id="add-gallery-preview" class="flex flex-wrap gap-2 justify-center w-full"></div>
                            <div id="add-gallery-placeholder" class="text-center">
                                <i class="fa-solid fa-images text-gray-300 text-2xl block mb-2"></i>
                                <span class="text-xs font-medium text-gray-400">Upload Foto Tambahan</span>
                            </div>
                            <input type="file" id="add-gallery-input" name="additional_images[]" accept="image/*" multiple class="hidden" onchange="previewImages(this,'add-gallery-preview','add-gallery-placeholder')">
                        </div>
                    </div>

                </div>
                <div class="px-6 py-5 border-t border-gray-100 flex gap-3">
                    <button type="button" onclick="closeModal('modal-add')" class="flex-1 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-md text-sm font-bold hover:bg-gray-50 transition-colors shadow-sm">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-[#111111] text-white rounded-md text-sm font-bold hover:bg-black transition-colors shadow-sm">Tambah Produk</button>
                </div>
            </form>
        </div>
    </div>

    {{-- FLOATING TOAST NOTIFICATION --}}
    <div id="toast-container" class="fixed top-5 left-1/2 -translate-x-1/2 z-[100] hidden transform transition-all duration-300 ease-out translate-y-[-20px] opacity-0">
        <div id="toast-box" class="flex items-center gap-3 bg-white border border-gray-100 text-gray-800 text-sm font-semibold px-5 py-3.5 rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
            <div id="toast-icon" class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center"></div>
            <span id="toast-msg"></span>
        </div>
    </div>

    @endsection

    @push('scripts')
    <script>
        // ==== 1. FLOATING TOAST SYSTEM ====
        function showToast(msg, type = 'success') {
            const container = document.getElementById('toast-container');
            const box = document.getElementById('toast-box');
            const icon = document.getElementById('toast-icon');
            const msgEl = document.getElementById('toast-msg');

            msgEl.textContent = msg;

            if (type === 'success') {
                icon.className = 'flex-shrink-0 w-6 h-6 rounded-full bg-green-100 flex items-center justify-center';
                icon.innerHTML = '<i class="fa-solid fa-check text-green-600 text-[10px]"></i>';
            } else {
                icon.className = 'flex-shrink-0 w-6 h-6 rounded-full bg-red-100 flex items-center justify-center';
                icon.innerHTML = '<i class="fa-solid fa-xmark text-red-600 text-[10px]"></i>';
            }

            container.classList.remove('hidden');
            // Animasi masuk
            setTimeout(() => {
                container.classList.remove('translate-y-[-20px]', 'opacity-0');
                container.classList.add('translate-y-0', 'opacity-100');
            }, 10);

            // Animasi keluar setelah 2.5 detik (lebih cepat)
            setTimeout(() => {
                container.classList.remove('translate-y-0', 'opacity-100');
                container.classList.add('translate-y-[-20px]', 'opacity-0');
                setTimeout(() => {
                    container.classList.add('hidden');
                }, 300);
            }, 2500);
        }

        // Trigger Toast dari session Laravel
        @if(session('success')) showToast("{{ session('success') }}", 'success');
        @endif
        @if(session('error')) showToast("{{ session('error') }}", 'error');
        @endif

        // ==== 2. CUSTOM DROPDOWN FILTER ====
        function toggleFilterMenu(id) {
            document.querySelectorAll('.drop-menu').forEach(menu => {
                if (menu.id !== id) menu.classList.add('hidden');
            });
            document.getElementById(id).classList.toggle('hidden');
        }

        function selectFilterItem(type, value, labelHtml) {
            document.getElementById('input-' + type).value = value;
            document.getElementById('label-' + type).innerHTML = labelHtml;
            document.getElementById(type + 'Menu').classList.add('hidden');

            // Otomatis submit form setelah dipilih
            document.getElementById('filterForm').submit();
        }

        // Tutup dropdown kalau klik di luar
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.custom-dropdown')) {
                document.querySelectorAll('.drop-menu').forEach(menu => menu.classList.add('hidden'));
            }
        });

        // ==== 3. TOGGLE STATUS ====
        function toggleStatus(id, val) {
            fetch(`/admin/products/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        is_active: val
                    })
                })
                .then(res => {
                    showToast(val ? 'Produk aktif & terlihat' : 'Produk disembunyikan', 'success');
                })
                .catch(() => showToast('Gagal mengubah status', 'error'));
        }

        // ==== 4. MODAL ENGINE ====
        function openModal(id) {
            const modal = document.getElementById(id);
            const box = modal.children[0];
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                if (box) box.classList.remove('scale-95');
            }, 10);
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            const box = modal.children[0];
            modal.classList.add('opacity-0');
            if (box) box.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        function closeOnBackdrop(e, id) {
            if (e.target.classList.contains('backdrop-blur-sm')) closeModal(id);
        }

        // Auto-open modal saat error validasi
        @if($errors->any()) openModal('modal-add');
        @endif

        // ==== 5. IMAGE PREVIEW ====
        function previewImages(input, previewId, placeholderId) {
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);
            preview.innerHTML = '';
            const files = Array.from(input.files);
            if (!files.length) {
                placeholder.style.display = '';
                return;
            }
            placeholder.style.display = 'none';
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    // PERUBAHAN JS: Kalau sebelumnya cuma w-16 h-16, ini dipertahankan, 
                    // karena pemisahnya (gap) udah aku kasih di div parent (id previewnya).
                    img.className = 'w-16 h-16 rounded object-cover border border-gray-200';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }

        // ==== 6. CHIPS (SIZE & WARNA) ====
        function toggleChip(button, inputId) {
            button.classList.toggle('bg-[#111111]');
            button.classList.toggle('text-white');
            button.classList.toggle('border-[#111111]');
            button.classList.toggle('bg-gray-50');
            button.classList.toggle('text-gray-600');

            const container = button.parentElement;
            const activeButtons = Array.from(container.querySelectorAll('.bg-\\[\\#111111\\]'));
            const selectedValues = activeButtons.map(btn => btn.getAttribute('data-value'));
            document.getElementById('hidden-' + inputId).value = selectedValues.join(',');
        }
    </script>
    @endpush