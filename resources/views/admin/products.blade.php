@extends('layouts.admin')

@section('title', 'Product Management — TANKEN')
@section('page-title', 'Product Management')
@section('breadcrumb', 'Home / Products')

@push('styles')
<style>
    .toggle-switch { position: relative; width: 44px; height: 24px; cursor: pointer; display: inline-block; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider { position: absolute; inset: 0; background: #ef4444; border-radius: 24px; transition: .3s; }
    .toggle-slider:before { content: ''; position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: .3s; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
    input:checked+.toggle-slider { background: #16a34a; }
    input:checked+.toggle-slider:before { transform: translateX(20px); }
    .cat-pendek { background: #eff6ff; color: #2563eb; }
    .cat-panjang { background: #f0fdf4; color: #16a34a; }
    .cat-badge { padding: 4px 12px; font-size: 11px; font-weight: 600; border-radius: 4px; }
    .rating-badge { background: #fefce8; color: #854d0e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
    .stock-low { color: #ef4444; font-weight: 700; }
    .stock-medium { color: #f97316; font-weight: 700; }
    .stock-high { color: #16a34a; font-weight: 700; }
    .modal-body { max-height: 70vh; overflow-y: auto; }
    .modal-body::-webkit-scrollbar { width: 6px; }
    .modal-body::-webkit-scrollbar-thumb { background-color: #e5e7eb; border-radius: 4px; }
    .dropdown-item:hover { background-color: #f9fafb; }
    .custom-dropdown-btn { appearance: none; background-color: white; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 14px; font-size: 0.875rem; color: #4b5563; display: flex; align-items: center; justify-content: space-between; gap: 10px; cursor: pointer; transition: border-color 0.2s; }
    .custom-dropdown-btn:focus { border-color: #111; outline: none; }

    /* ── Slot foto ── */
    .photo-slot { position: relative; aspect-ratio: 1/1; border: 2px dashed #e5e7eb; border-radius: 8px; overflow: hidden; cursor: pointer; transition: border-color .2s, background .2s; background: #fafafa; }
    .photo-slot:hover { border-color: #9ca3af; background: #f3f4f6; }
    .photo-slot.has-image { border-style: solid; border-color: #e5e7eb; }
    .photo-slot .slot-preview { width: 100%; height: 100%; object-fit: cover; display: none; }
    .photo-slot.has-image .slot-preview { display: block; }
    .photo-slot .slot-placeholder { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px; pointer-events: none; }
    .photo-slot.has-image .slot-placeholder { display: none; }
    .photo-slot .slot-remove { position: absolute; top: 4px; right: 4px; width: 20px; height: 20px; background: rgba(0,0,0,0.55); border-radius: 50%; display: none; align-items: center; justify-content: center; cursor: pointer; z-index: 2; }
    .photo-slot.has-image .slot-remove { display: flex; }
    .photo-slot .slot-label { position: absolute; bottom: 0; left: 0; right: 0; text-align: center; font-size: 9px; font-weight: 700; padding: 3px 0; pointer-events: none; }

    /* thumbnail radio */
    .thumb-radio-wrap { display: none; }
    .photo-slot.has-image + .thumb-radio-wrap { display: flex; }
    .thumb-radio-label { display: flex; align-items: center; gap-4px; font-size: 10px; color: #6b7280; cursor: pointer; margin-top: 4px; gap: 4px; }
    .thumb-radio-label input { accent-color: #111; }
    .thumb-radio-label.selected { color: #111; font-weight: 700; }

    /* stok per ukuran */
    .size-stock-row { display: none; align-items: center; gap: 8px; }
    .size-stock-row.visible { display: flex; }
</style>
@endpush

@section('content')

<div class="flex items-center justify-between mb-5">
    <p class="text-sm text-gray-500">{{ $products->total() }} products</p>
    <button onclick="openModal('modal-add')" class="flex items-center gap-2 bg-[#111111] text-white text-sm font-semibold px-4 py-2.5 rounded-md hover:bg-black transition-colors shadow-sm">
        <i class="fa-solid fa-plus"></i> Add Product
    </button>
</div>

{{-- FILTER --}}
<form id="filterForm" method="GET" action="{{ route('admin.products.index') }}" class="bg-white rounded-md border border-gray-100 p-4 mb-5 flex flex-wrap items-center gap-3 shadow-sm">
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
    </div>

    <a href="{{ route('admin.products.export', request()->query()) }}" class="flex items-center gap-2 border border-gray-200 rounded-md px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
        <i class="fa-solid fa-download"></i> Export Excel
    </a>
</form>

{{-- TABLE --}}
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
                    $totalStock = $product->stocks->sum('quantity');
                    $stockClass = $totalStock <= 20 ? 'stock-low' : ($totalStock <= 40 ? 'stock-medium' : 'stock-high');
                    $avgRating  = $product->reviews->avg('rating') ?? 0;
                    $reviewCount = $product->reviews->count();
                    $colorsArray = is_array($product->colors) ? $product->colors : (json_decode($product->colors, true) ?? []);
                    $sizesArray  = is_array($product->sizes)  ? $product->sizes  : (json_decode($product->sizes, true)  ?? []);
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
                            <div>
                                <span class="font-semibold text-gray-900 text-sm block">{{ $product->name }}</span>
                                @if(count($colorsArray))
                                    <span class="text-[11px] text-gray-400">{{ implode(', ', $colorsArray) }}</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 font-mono text-xs text-gray-500 font-medium">{{ $product->sku }}</td>
                    <td class="px-4 py-4">
                        <span class="cat-badge {{ $product->type === 'pendek' ? 'cat-pendek' : 'cat-panjang' }}">{{ ucfirst($product->type) }}</span>
                    </td>
                    <td class="px-4 py-4 text-sm font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-4 py-4">
                        <span class="text-sm {{ $stockClass }}">{{ $totalStock }}</span>
                        @if(count($sizesArray))
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($sizesArray as $sz)
                                @php $szStock = $product->stocks->where('size', $sz)->first(); @endphp
                                <span class="text-[10px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded font-mono">{{ $sz }}:{{ $szStock?->quantity ?? 0 }}</span>
                            @endforeach
                        </div>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        @if($reviewCount > 0)
                            <span class="rating-badge"><i class="fa-solid fa-star text-[10px]" style="color:#f59e0b;"></i> {{ number_format($avgRating, 1) }}</span>
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

<div class="mt-4">{{ $products->withQueryString()->links() }}</div>

{{-- ===================== MODALS PER PRODUCT ===================== --}}
@foreach($products as $product)
@php
    $totalStock  = $product->stocks->sum('quantity');
    $stockClass  = $totalStock <= 20 ? 'stock-low' : ($totalStock <= 40 ? 'stock-medium' : 'stock-high');
    $colorsArray = is_array($product->colors) ? $product->colors : (json_decode($product->colors, true) ?? []);
    $sizesArray  = is_array($product->sizes)  ? $product->sizes  : (json_decode($product->sizes, true)  ?? []);
@endphp

{{-- MODAL: VIEW --}}
<div id="modal-view-{{ $product->id }}" class="fixed inset-0 z-[60] hidden items-center justify-center opacity-0 transition-opacity duration-300 bg-black/60 backdrop-blur-sm" onclick="closeOnBackdrop(event,'modal-view-{{ $product->id }}')">
    <div class="bg-white rounded-md w-full max-w-lg mx-4 shadow-2xl transform scale-95 transition-transform duration-300">
        <div class="px-6 pt-6 pb-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">Detail Produk</h2>
            <button type="button" onclick="closeModal('modal-view-{{ $product->id }}')" class="text-gray-400 hover:text-gray-700"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <div class="modal-body px-6 py-5">
            {{-- Foto --}}
            <div class="flex gap-3 mb-5 overflow-x-auto pb-2">
                @if($product->main_image)
                    <div class="flex-shrink-0 text-center">
                        <img src="{{ Storage::url($product->main_image) }}" class="w-24 h-24 rounded object-cover border-2 border-[#111] mb-1" alt="{{ $product->name }}">
                        <span class="text-[9px] font-bold text-[#111] uppercase tracking-wider">Thumbnail</span>
                    </div>
                @endif
                @foreach($product->galleries as $gallery)
                    <img src="{{ Storage::url($gallery->image) }}" class="w-24 h-24 rounded object-cover border border-gray-200 flex-shrink-0" alt="">
                @endforeach
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
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tipe</span>
                    <span class="cat-badge {{ $product->type === 'pendek' ? 'cat-pendek' : 'cat-panjang' }}">{{ ucfirst($product->type) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Harga</span>
                    <span class="text-sm font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>
                @if(count($colorsArray))
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Warna (auto)</span>
                    <span class="text-sm font-semibold text-gray-700">{{ implode(', ', $colorsArray) }}</span>
                </div>
                @endif
                {{-- Stok per ukuran --}}
                <div class="py-2 border-b border-gray-100">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-2">Stok per Ukuran</span>
                    @if(count($sizesArray))
                        <div class="flex flex-wrap gap-2">
                            @foreach($sizesArray as $sz)
                                @php $szStock = $product->stocks->where('size', $sz)->first(); @endphp
                                <div class="flex flex-col items-center bg-gray-50 rounded px-3 py-1.5 min-w-[44px]">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">{{ $sz }}</span>
                                    <span class="text-sm font-bold {{ ($szStock?->quantity ?? 0) <= 5 ? 'text-red-500' : 'text-gray-800' }}">{{ $szStock?->quantity ?? 0 }}</span>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Total: <span class="font-bold text-gray-600 {{ $stockClass }}">{{ $totalStock }} pcs</span></p>
                    @else
                        <p class="text-sm text-gray-400">–</p>
                    @endif
                </div>
                @if($product->description)
                <div class="py-2.5">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1.5">Deskripsi</span>
                    <p class="text-sm text-gray-600 leading-relaxed">{!! nl2br(e($product->description)) !!}</p>
                </div>
                @endif
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            <button type="button" onclick="closeModal('modal-view-{{ $product->id }}')" class="w-full py-2.5 rounded-md border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">Tutup</button>
        </div>
    </div>
</div>

{{-- MODAL: EDIT --}}
<div id="modal-edit-{{ $product->id }}" class="fixed inset-0 z-[60] hidden items-center justify-center opacity-0 transition-opacity duration-300 bg-black/60 backdrop-blur-sm" onclick="closeOnBackdrop(event,'modal-edit-{{ $product->id }}')">
    <div class="bg-white rounded-md shadow-2xl w-full max-w-lg mx-4 transform scale-95 transition-transform duration-300">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Edit Produk</h2>
        </div>
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-body px-6 py-5 space-y-5">

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors" required>
                    <p class="text-[11px] text-gray-400 mt-1">Format: "Nama Celana - Warna" → warna terdeteksi otomatis</p>
                </div>

                {{-- SKU & Kategori --}}
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

                {{-- Harga & Tipe --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga (Rp)</label>
                        <input type="text" name="price" inputmode="numeric" value="{{ old('price', $product->price) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors"  oninput="formatRupiah(this)">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe</label>
                        <select name="type" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors bg-white">
                            <option value="pendek" {{ $product->type === 'pendek' ? 'selected' : '' }}>Pendek</option>
                            <option value="panjang" {{ $product->type === 'panjang' ? 'selected' : '' }}>Panjang</option>
                        </select>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors resize-y" rows="3">{{ old('description', $product->description) }}</textarea>
                </div>

                {{-- Ukuran + Stok per ukuran --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ukuran & Stok per Size</label>
                    <div class="space-y-2" id="edit-size-list-{{ $product->id }}">
                        @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                            @php
                                $isSelected = in_array($size, $sizesArray);
                                $szStock = $product->stocks->where('size', $size)->first();
                                $szQty   = $szStock?->quantity ?? 0;
                            @endphp
                            <div class="flex items-center gap-3">
                                <button type="button"
                                    data-value="{{ $size }}"
                                    onclick="toggleSizeEdit(this, 'edit-sizes-{{ $product->id }}', 'edit-stock-{{ $product->id }}-{{ $size }}')"
                                    class="w-14 py-1.5 border rounded-md text-sm font-semibold text-center transition-colors flex-shrink-0
                                        {{ $isSelected ? 'bg-[#111111] text-white border-[#111111]' : 'bg-gray-50 text-gray-500 border-gray-200 hover:bg-gray-100' }}">
                                    {{ $size }}
                                </button>
                                <div id="edit-stock-{{ $product->id }}-{{ $size }}" class="size-stock-row {{ $isSelected ? 'visible' : '' }} flex-1">
                                    <span class="text-xs text-gray-400 w-10 flex-shrink-0">Stok:</span>
                                    <input type="number"
                                        name="stock_per_size[{{ $size }}]"
                                        value="{{ $isSelected ? $szQty : 0 }}"
                                        min="0"
                                        class="w-24 px-3 py-1.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors">
                                    <span class="text-xs text-gray-400">pcs</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="sizes" id="hidden-edit-sizes-{{ $product->id }}" value="{{ implode(',', $sizesArray) }}">
                </div>

                {{-- 4 Slot Foto --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Foto Produk</label>
                    <p class="text-[11px] text-gray-400 mb-3">Upload foto per slot, lalu pilih mana yang jadi thumbnail utama.</p>

                    {{-- Slot 0 = foto utama saat ini --}}
                    <div class="grid grid-cols-4 gap-3">
                        @for($slot = 0; $slot < 4; $slot++)
                            @php
                                // Slot 0 = main_image, slot 1-3 = gallery (jika ada)
                                $existingImg = null;
                                if ($slot === 0 && $product->main_image) {
                                    $existingImg = Storage::url($product->main_image);
                                } elseif ($slot > 0) {
                                    $galleryItem = $product->galleries->get($slot - 1);
                                    if ($galleryItem) $existingImg = Storage::url($galleryItem->image);
                                }
                            @endphp
                            <div>
                                <div class="photo-slot {{ $existingImg ? 'has-image' : '' }}"
                                     id="edit-slot-{{ $product->id }}-{{ $slot }}"
                                     onclick="triggerSlotInput('edit-file-{{ $product->id }}-{{ $slot }}')">
                                    <img class="slot-preview"
                                         src="{{ $existingImg ?? '' }}"
                                         alt="Slot {{ $slot + 1 }}">
                                    <div class="slot-placeholder">
                                        <i class="fa-solid fa-plus text-gray-300 text-lg"></i>
                                        <span class="text-[9px] text-gray-400 font-medium">Foto {{ $slot + 1 }}</span>
                                    </div>
                                    <div class="slot-remove" onclick="removeSlot(event,'edit-slot-{{ $product->id }}-{{ $slot }}','edit-file-{{ $product->id }}-{{ $slot }}','edit-thumb-{{ $product->id }}-{{ $slot }}')">
                                        <i class="fa-solid fa-xmark text-white text-[8px]"></i>
                                    </div>
                                    <input type="file"
                                           id="edit-file-{{ $product->id }}-{{ $slot }}"
                                           name="slot_image[{{ $slot }}]"
                                           accept="image/*"
                                           class="hidden"
                                           onchange="onSlotChange(this,'edit-slot-{{ $product->id }}-{{ $slot }}','edit-thumb-{{ $product->id }}-{{ $slot }}')">
                                </div>
                                {{-- Radio thumbnail --}}
                                <div class="thumb-radio-wrap {{ $existingImg ? 'flex' : '' }} justify-center mt-1"
                                     id="edit-thumb-wrap-{{ $product->id }}-{{ $slot }}">
                                    <label class="thumb-radio-label {{ $slot === 0 ? 'selected' : '' }}" id="edit-thumb-{{ $product->id }}-{{ $slot }}">
                                        <input type="radio"
                                               name="thumbnail_slot"
                                               value="{{ $slot }}"
                                               {{ $slot === 0 ? 'checked' : '' }}
                                               onchange="markThumb('edit-thumb-{{ $product->id }}', {{ $slot }}, 4)">
                                        Thumbnail
                                    </label>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- Size Chart --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Size Chart <span class="text-xs font-normal text-gray-400">(Opsional)</span></label>
                    @if($product->size_chart_image ?? false)
                        <img src="{{ Storage::url($product->size_chart_image) }}" class="w-16 h-16 rounded object-cover border border-gray-200 mb-2" alt="Size Chart">
                    @endif
                    <div class="border-2 border-dashed border-gray-200 rounded-md p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors" onclick="document.getElementById('edit-sizechart-{{ $product->id }}').click()">
                        <span class="text-xs text-gray-400"><i class="fa-solid fa-ruler-combined mr-1"></i>Ganti Size Guide</span>
                        <input type="file" id="edit-sizechart-{{ $product->id }}" name="size_chart_image" accept="image/*" class="hidden">
                    </div>
                </div>

            </div>{{-- end modal-body --}}
            <div class="px-6 py-5 border-t border-gray-100 flex gap-3">
                <button type="button" onclick="closeModal('modal-edit-{{ $product->id }}')" class="flex-1 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-md text-sm font-bold hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-[#111111] text-white rounded-md text-sm font-bold hover:bg-black transition-colors shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: DELETE --}}
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

{{-- ===================== MODAL: ADD ===================== --}}
<div id="modal-add" class="fixed inset-0 z-[60] hidden items-center justify-center opacity-0 transition-opacity duration-300 bg-black/60 backdrop-blur-sm" onclick="closeOnBackdrop(event,'modal-add')">
    <div class="bg-white rounded-md shadow-2xl w-full max-w-lg mx-4 transform scale-95 transition-transform duration-300">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Tambah Produk Baru</h2>
            <button type="button" onclick="closeModal('modal-add')" class="text-gray-400 hover:text-gray-700"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body px-6 py-5 space-y-5">

                @if ($errors->any())
                <div class="bg-red-50 text-red-600 text-xs p-3 rounded-md border border-red-100">
                    <ul class="list-disc pl-4 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
                @endif

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors" placeholder="Celana Cargo Pendek - Olive" required>
                    <p class="text-[11px] text-gray-400 mt-1">Format: "Nama Celana - Warna" → warna terdeteksi otomatis</p>
                </div>

                {{-- SKU & Kategori --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">SKU <span class="text-red-500">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm font-mono focus:border-black outline-none transition-colors" placeholder="mis. TKN-PDK-001" required>
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

                {{-- Harga & Tipe --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="text" name="price" inputmode="numeric" value="{{ old('price') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors"   oninput="formatRupiah(this)" placeholder="0" min="0" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe <span class="text-red-500">*</span></label>
                        <select name="type" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors bg-white" required>
                            <option value="pendek">Pendek</option>
                            <option value="panjang">Panjang</option>
                        </select>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors resize-y" rows="3" placeholder="Masukkan deskripsi produk">{{ old('description') }}</textarea>
                </div>

                {{-- Ukuran + Stok per ukuran --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ukuran & Stok per Size <span class="text-red-500">*</span></label>
                    <p class="text-[11px] text-gray-400 mb-3">Klik ukuran untuk mengaktifkan, lalu isi stok masing-masing.</p>
                    <div class="space-y-2" id="add-size-list">
                        @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                        <div class="flex items-center gap-3">
                            <button type="button"
                                data-value="{{ $size }}"
                                onclick="toggleSizeAdd(this, 'add-sizes', 'add-stock-{{ $size }}')"
                                class="w-14 py-1.5 border border-gray-200 rounded-md text-sm font-semibold text-center bg-gray-50 text-gray-500 hover:bg-gray-100 transition-colors flex-shrink-0">
                                {{ $size }}
                            </button>
                            <div id="add-stock-{{ $size }}" class="size-stock-row flex-1">
                                <span class="text-xs text-gray-400 w-10 flex-shrink-0">Stok:</span>
                                <input type="number"
                                    name="stock_per_size[{{ $size }}]"
                                    value="0"
                                    min="0"
                                    class="w-24 px-3 py-1.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors">
                                <span class="text-xs text-gray-400">pcs</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="sizes" id="hidden-add-sizes" value="{{ old('sizes', '') }}">
                </div>

                {{-- 4 Slot Foto --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Foto Produk <span class="text-red-500">*</span></label>
                    <p class="text-[11px] text-gray-400 mb-3">Upload foto per slot, lalu pilih mana yang jadi thumbnail utama.</p>
                    <div class="grid grid-cols-4 gap-3">
                        @for($slot = 0; $slot < 4; $slot++)
                        <div>
                            <div class="photo-slot"
                                 id="add-slot-{{ $slot }}"
                                 onclick="triggerSlotInput('add-file-{{ $slot }}')">
                                <img class="slot-preview" src="" alt="Slot {{ $slot + 1 }}">
                                <div class="slot-placeholder">
                                    <i class="fa-solid fa-plus text-gray-300 text-lg"></i>
                                    <span class="text-[9px] text-gray-400 font-medium">Foto {{ $slot + 1 }}</span>
                                </div>
                                <div class="slot-remove" onclick="removeSlot(event,'add-slot-{{ $slot }}','add-file-{{ $slot }}','add-thumb-{{ $slot }}')">
                                    <i class="fa-solid fa-xmark text-white text-[8px]"></i>
                                </div>
                                <input type="file"
                                       id="add-file-{{ $slot }}"
                                       name="slot_image[{{ $slot }}]"
                                       accept="image/*"
                                       class="hidden"
                                       onchange="onSlotChange(this,'add-slot-{{ $slot }}','add-thumb-{{ $slot }}')">
                            </div>
                            {{-- Radio thumbnail --}}
                            <div class="thumb-radio-wrap justify-center mt-1"
                                 id="add-thumb-wrap-{{ $slot }}">
                                <label class="thumb-radio-label {{ $slot === 0 ? 'selected' : '' }}" id="add-thumb-{{ $slot }}">
                                    <input type="radio"
                                           name="thumbnail_slot"
                                           value="{{ $slot }}"
                                           {{ $slot === 0 ? 'checked' : '' }}
                                           onchange="markThumb('add-thumb', {{ $slot }}, 4)">
                                    Thumbnail
                                </label>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

                {{-- Size Chart --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Size Chart <span class="text-xs font-normal text-gray-400">(Opsional)</span></label>
                    <div class="border-2 border-dashed border-gray-200 rounded-md p-4 flex items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors" onclick="document.getElementById('add-sizechart').click()">
                        <div id="add-sz-preview" class="hidden mr-2"></div>
                        <span class="text-xs text-gray-400" id="add-sz-label"><i class="fa-solid fa-ruler-combined mr-1"></i>Upload Size Guide</span>
                        <input type="file" id="add-sizechart" name="size_chart_image" accept="image/*" class="hidden" onchange="onSizeChartChange(this)">
                    </div>
                </div>

            </div>{{-- end modal-body --}}
            <div class="px-6 py-5 border-t border-gray-100 flex gap-3">
                <button type="button" onclick="closeModal('modal-add')" class="flex-1 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-md text-sm font-bold hover:bg-gray-50 transition-colors shadow-sm">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-[#111111] text-white rounded-md text-sm font-bold hover:bg-black transition-colors shadow-sm">Tambah Produk</button>
            </div>
        </form>
    </div>
</div>

{{-- TOAST --}}
<div id="toast-container" class="fixed top-5 left-1/2 -translate-x-1/2 z-[100] hidden transform transition-all duration-300 ease-out translate-y-[-20px] opacity-0">
    <div id="toast-box" class="flex items-center gap-3 bg-white border border-gray-100 text-gray-800 text-sm font-semibold px-5 py-3.5 rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
        <div id="toast-icon" class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center"></div>
        <span id="toast-msg"></span>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ─── TOAST ────────────────────────────────────────────
function showToast(msg, type = 'success') {
    const container = document.getElementById('toast-container');
    const icon = document.getElementById('toast-icon');
    document.getElementById('toast-msg').textContent = msg;
    if (type === 'success') {
        icon.className = 'flex-shrink-0 w-6 h-6 rounded-full bg-green-100 flex items-center justify-center';
        icon.innerHTML = '<i class="fa-solid fa-check text-green-600 text-[10px]"></i>';
    } else {
        icon.className = 'flex-shrink-0 w-6 h-6 rounded-full bg-red-100 flex items-center justify-center';
        icon.innerHTML = '<i class="fa-solid fa-xmark text-red-600 text-[10px]"></i>';
    }
    container.classList.remove('hidden');
    setTimeout(() => { container.classList.remove('translate-y-[-20px]', 'opacity-0'); container.classList.add('translate-y-0', 'opacity-100'); }, 10);
    setTimeout(() => {
        container.classList.remove('translate-y-0', 'opacity-100');
        container.classList.add('translate-y-[-20px]', 'opacity-0');
        setTimeout(() => container.classList.add('hidden'), 300);
    }, 2500);
}
@if(session('success')) showToast("{{ session('success') }}", 'success'); @endif
@if(session('error')) showToast("{{ session('error') }}", 'error'); @endif

// ─── FILTER DROPDOWN ──────────────────────────────────
function toggleFilterMenu(id) {
    document.querySelectorAll('.drop-menu').forEach(m => { if (m.id !== id) m.classList.add('hidden'); });
    document.getElementById(id).classList.toggle('hidden');
}
function selectFilterItem(type, value, label) {
    document.getElementById('input-' + type).value = value;
    document.getElementById('label-' + type).innerHTML = label;
    document.getElementById(type + 'Menu').classList.add('hidden');
    document.getElementById('filterForm').submit();
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.custom-dropdown')) {
        document.querySelectorAll('.drop-menu').forEach(m => m.classList.add('hidden'));
    }
});

// ─── MODAL ────────────────────────────────────────────
function openModal(id) {
    const modal = document.getElementById(id);
    const box = modal.children[0];
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => { modal.classList.remove('opacity-0'); if (box) box.classList.remove('scale-95'); }, 10);
}
function closeModal(id) {
    const modal = document.getElementById(id);
    const box = modal.children[0];
    modal.classList.add('opacity-0');
    if (box) box.classList.add('scale-95');
    setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
}
function closeOnBackdrop(e, id) {
    if (e.target.classList.contains('backdrop-blur-sm')) closeModal(id);
}
@if($errors->any()) openModal('modal-add'); @endif

// ─── TOGGLE STATUS ────────────────────────────────────
function toggleStatus(id, val) {
    fetch(`/admin/products/${id}/toggle-status`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ is_active: val })
    }).then(() => showToast(val ? 'Produk aktif & terlihat' : 'Produk disembunyikan', 'success'))
      .catch(() => showToast('Gagal mengubah status', 'error'));
}

// ─── SIZE + STOK: ADD FORM ────────────────────────────
function toggleSizeAdd(btn, hiddenId, stockRowId) {
    const isActive = btn.classList.contains('bg-\\[\\#111111\\]') || btn.classList.contains('bg-[#111111]');
    // toggle style
    btn.classList.toggle('bg-[#111111]', !isActive);
    btn.classList.toggle('text-white', !isActive);
    btn.classList.toggle('border-[#111111]', !isActive);
    btn.classList.toggle('bg-gray-50', isActive);
    btn.classList.toggle('text-gray-500', isActive);
    btn.classList.toggle('border-gray-200', isActive);

    // show/hide stok row
    const row = document.getElementById(stockRowId);
    if (row) row.classList.toggle('visible', !isActive);

    // update hidden sizes input
    const container = btn.closest('[id^="add-size-list"], #add-size-list').parentElement;
    syncSizes('add-size-list', 'hidden-add-sizes');
}

function syncSizes(listId, hiddenId) {
    const list = document.getElementById(listId);
    if (!list) return;
    const active = Array.from(list.querySelectorAll('button.bg-\\[\\#111111\\]'))
        .map(b => b.dataset.value);
    const hidden = document.getElementById(hiddenId);
    if (hidden) hidden.value = active.join(',');
}

// ─── SIZE + STOK: EDIT FORM ───────────────────────────
function toggleSizeEdit(btn, hiddenId, stockRowId) {
    const isActive = btn.classList.contains('bg-[#111111]') ||
                     getComputedStyle(btn).backgroundColor === 'rgb(17, 17, 17)';

    btn.classList.toggle('bg-[#111111]', !isActive);
    btn.classList.toggle('text-white', !isActive);
    btn.classList.toggle('border-[#111111]', !isActive);
    btn.classList.toggle('bg-gray-50', isActive);
    btn.classList.toggle('text-gray-500', isActive);
    btn.classList.toggle('border-gray-200', isActive);

    const row = document.getElementById(stockRowId);
    if (row) row.classList.toggle('visible', !isActive);

    // sync hidden input — find the hidden by id
    const hidden = document.getElementById('hidden-' + hiddenId);
    if (!hidden) return;
    // collect all active sizes in the same form
    const form = btn.closest('form');
    if (!form) return;
    const active = Array.from(form.querySelectorAll('[id*="-size-list-"] button.bg-\\[\\#111111\\]'))
        .map(b => b.dataset.value);
    hidden.value = active.join(',');
}

// ─── SLOT FOTO ────────────────────────────────────────
function triggerSlotInput(inputId) {
    document.getElementById(inputId).click();
}

function onSlotChange(input, slotId, thumbLabelId) {
    const file = input.files[0];
    if (!file) return;
    const slot = document.getElementById(slotId);
    const preview = slot.querySelector('.slot-preview');
    const thumbWrap = slot.parentElement.querySelector('[id*="thumb-wrap"]');

    const reader = new FileReader();
    reader.onload = e => {
        preview.src = e.target.result;
        slot.classList.add('has-image');
        if (thumbWrap) thumbWrap.classList.add('flex');
    };
    reader.readAsDataURL(file);
}

function removeSlot(e, slotId, inputId, thumbLabelId) {
    e.stopPropagation();
    const slot = document.getElementById(slotId);
    const input = document.getElementById(inputId);
    const preview = slot.querySelector('.slot-preview');
    const thumbWrap = slot.parentElement.querySelector('[id*="thumb-wrap"]');

    preview.src = '';
    slot.classList.remove('has-image');
    input.value = '';
    if (thumbWrap) thumbWrap.classList.remove('flex');
}

function markThumb(prefix, selectedSlot, total) {
    for (let i = 0; i < total; i++) {
        const label = document.getElementById(prefix + '-' + i);
        if (label) label.classList.toggle('selected', i === selectedSlot);
    }
}

// ─── SIZE CHART PREVIEW ───────────────────────────────
function onSizeChartChange(input) {
    const file = input.files[0];
    if (!file) return;
    const label = document.getElementById('add-sz-label');
    if (label) label.textContent = file.name;
}

function formatRupiah(input) {
    let raw = input.value.replace(/\D/g, '');
    input.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
    input.dataset.raw = raw;
}
</script>
@endpush