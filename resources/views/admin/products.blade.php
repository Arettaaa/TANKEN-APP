@extends('layouts.admin')

@section('title', 'Product Management — TANKEN')
@section('page-title', 'Product Management')
@section('breadcrumb', 'Home / Products')

{{-- ====== CUSTOM STYLES ====== --}}
@push('styles')
<style>
    /* Toggle switch */
    .toggle-switch { position: relative; width: 40px; height: 22px; cursor: pointer; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider { position: absolute; inset: 0; background: #e5e7eb; border-radius: 20px; transition: .3s; }
    .toggle-slider:before { content: ''; position: absolute; height: 16px; width: 16px; left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: .3s; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
    input:checked + .toggle-slider { background: #16a34a; }
    input:checked + .toggle-slider:before { transform: translateX(18px); }

    /* Badges */
    .cat-pendek  { background: #eff6ff; color: #2563eb; }
    .cat-panjang { background: #f0fdf4; color: #16a34a; }
    .cat-badge   { padding: 4px 12px; font-size: 11px; font-weight: 600; border-radius: 4px; }
    .rating-badge { background: #fefce8; color: #854d0e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }

    /* Stock color */
    .stock-low    { color: #ef4444; font-weight: 700; }
    .stock-medium { color: #f97316; font-weight: 700; }
    .stock-high   { color: #16a34a; font-weight: 700; }

    /* Scrollable modal body */
    .modal-body { max-height: 70vh; overflow-y: auto; }
    .modal-body::-webkit-scrollbar { width: 6px; }
    .modal-body::-webkit-scrollbar-thumb { background-color: #e5e7eb; border-radius: 4px; }
</style>
@endpush

@section('content')

{{-- Flash message --}}
@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded px-4 py-3 flex items-center gap-2">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M20 6L9 17l-5-5"/></svg>
    {{ session('success') }}
</div>
@endif

<div class="flex items-center justify-between mb-5">
    <p class="text-sm text-gray-500">{{ $products->total() }} products</p>
    <button onclick="openModal('modal-add')" class="flex items-center gap-2 bg-[#111111] text-white text-sm font-semibold px-4 py-2.5 rounded-md hover:bg-black transition-colors">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="15" height="15"><path d="M12 5v14M5 12h14"/></svg>
        Add Product
    </button>
</div>

<form method="GET" action="{{ route('admin.products.index') }}" class="bg-white rounded-md border border-gray-200 p-4 mb-5 flex flex-wrap items-center gap-3 shadow-sm">
    @csrf
    {{-- Revisi: Kategori Pria / Wanita Statis --}}
    <select name="category" onchange="this.form.submit()" class="border border-gray-200 rounded-md px-3 py-2 text-sm text-gray-600 focus:outline-none focus:border-gray-400 bg-white min-w-[130px]">
        <option value="">Semua Kategori</option>
        <option value="1" {{ request('category') == '1' ? 'selected' : '' }}>Pria</option>
        <option value="2" {{ request('category') == '2' ? 'selected' : '' }}>Wanita</option>
    </select>
    
    <select name="type" onchange="this.form.submit()" class="border border-gray-200 rounded-md px-3 py-2 text-sm text-gray-600 focus:outline-none focus:border-gray-400 bg-white min-w-[130px]">
        <option value="">Semua Tipe</option>
        <option value="pendek" {{ request('type') === 'pendek' ? 'selected' : '' }}>Pendek</option>
        <option value="panjang" {{ request('type') === 'panjang' ? 'selected' : '' }}>Panjang</option>
    </select>
    
    <select name="sort" onchange="this.form.submit()" class="border border-gray-200 rounded-md px-3 py-2 text-sm text-gray-600 focus:outline-none focus:border-gray-400 bg-white min-w-[140px]">
        <option value="">Urutkan</option>
        <option value="price-asc"   {{ request('sort') === 'price-asc'   ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
        <option value="price-desc"  {{ request('sort') === 'price-desc'  ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
        <option value="stock-asc"   {{ request('sort') === 'stock-asc'   ? 'selected' : '' }}>Stok: Sedikit</option>
        <option value="stock-desc"  {{ request('sort') === 'stock-desc'  ? 'selected' : '' }}>Stok: Terbanyak</option>
    </select>
    
    <div class="flex-1 relative min-w-[180px]">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" width="15" height="15"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-md text-sm focus:outline-none focus:border-gray-400">
    </div>
    
    <button type="button" class="flex items-center gap-2 border border-gray-200 rounded-md px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 transition-colors">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" width="15" height="15"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Export Excel
    </button>
</form>

<div class="bg-white rounded-md border border-gray-200 overflow-hidden shadow-sm">
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
                    $stockClass = $totalStock <= 20 ? 'stock-low' : ($totalStock <= 40 ? 'stock-medium' : 'stock-high');
                    $avgRating  = $product->reviews->avg('rating') ?? 0;
                    $reviewCount = $product->reviews->count();
                @endphp
                <tr class="hover:bg-gray-50/60 transition-colors group">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-4">
                            @if($product->main_image)
                                <img src="{{ Storage::url($product->main_image) }}" class="w-12 h-12 rounded object-cover border border-gray-100" alt="{{ $product->name }}">
                            @else
                                <div class="w-12 h-12 rounded bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="1.5" width="20" height="20"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M3 9l4-4 4 4 4-4 4 4"/><circle cx="8.5" cy="15" r="1.5"/></svg>
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
                                <svg fill="#f59e0b" viewBox="0 0 24 24" width="12" height="12"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                {{ number_format($avgRating, 1) }}
                            </span>
                        @else
                            <span class="text-xs text-gray-400">–</span>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        <label class="toggle-switch">
                            <input type="checkbox" {{ $product->is_active ? 'checked' : '' }} onchange="toggleStatus({{ $product->id }}, this.checked)">
                            <span class="toggle-slider"></span>
                        </label>
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="openModal('modal-view-{{ $product->id }}')" class="text-gray-400 hover:text-black transition-colors" title="Lihat detail">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" width="18" height="18"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                            <button type="button" onclick="openModal('modal-edit-{{ $product->id }}')" class="text-gray-400 hover:text-black transition-colors" title="Edit">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" width="18" height="18"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button type="button" onclick="openModal('modal-delete-{{ $product->id }}')" class="text-gray-400 hover:text-red-600 transition-colors" title="Hapus">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" width="18" height="18"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="py-16 text-center text-gray-400">
                            <svg class="mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" width="40" height="40"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><circle cx="7" cy="7" r="1.5" fill="currentColor"/></svg>
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
    $totalStock  = $product->stocks->sum('quantity') + $product->stock;
    $stockClass  = $totalStock <= 20 ? 'stock-low' : ($totalStock <= 40 ? 'stock-medium' : 'stock-high');
    $colorsArray = is_array($product->colors) ? $product->colors : json_decode($product->colors, true) ?? [];
    $sizesArray  = is_array($product->sizes) ? $product->sizes : json_decode($product->sizes, true) ?? [];
@endphp

{{-- 1. MODAL: VIEW DETAIL --}}
<div id="modal-view-{{ $product->id }}" class="fixed inset-0 z-[60] hidden items-center justify-center opacity-0 transition-opacity duration-300 bg-black/60 backdrop-blur-sm" onclick="closeOnBackdrop(event,'modal-view-{{ $product->id }}')">
    <div class="bg-white rounded-md w-full max-w-lg mx-4 shadow-2xl transform scale-95 transition-transform duration-300">
        <div class="px-6 pt-6 pb-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">Detail Produk</h2>
            <button type="button" onclick="closeModal('modal-view-{{ $product->id }}')" class="text-gray-400 hover:text-gray-700">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body px-6 py-5">
            <div class="flex gap-3 mb-5">
                @if($product->main_image)
                    <img src="{{ Storage::url($product->main_image) }}" class="w-24 h-24 rounded object-cover border border-gray-200" alt="{{ $product->name }}">
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
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Stok</span>
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
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:ring-1 focus:ring-black focus:border-black outline-none transition-colors" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm font-mono focus:ring-1 focus:ring-black focus:border-black outline-none transition-colors" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori</label>
                        <select name="category_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:ring-1 focus:ring-black focus:border-black outline-none transition-colors bg-white">
                            {{-- Revisi: Kategori Edit Statis Pria / Wanita --}}
                            <option value="1" {{ $product->category_id == 1 ? 'selected' : '' }}>Pria</option>
                            <option value="2" {{ $product->category_id == 2 ? 'selected' : '' }}>Wanita</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga (Rp)</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:ring-1 focus:ring-black focus:border-black outline-none transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe</label>
                        <select name="type" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:ring-1 focus:ring-black focus:border-black outline-none transition-colors bg-white">
                            <option value="pendek"  {{ $product->type === 'pendek'  ? 'selected' : '' }}>Pendek</option>
                            <option value="panjang" {{ $product->type === 'panjang' ? 'selected' : '' }}>Panjang</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:ring-1 focus:ring-black focus:border-black outline-none transition-colors resize-y" rows="3">{{ old('description', $product->description) }}</textarea>
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
                    <input type="hidden" name="sizes" id="hidden-edit-sizes-{{ $product->id }}" value="{{ json_encode($sizesArray) }}">
                </div>
                
                {{-- Edit Warna (Chips) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Warna</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Hitam', 'Putih', 'Abu-abu', 'Navy', 'Coklat', 'Khaki', 'Olive'] as $color)
                            @php $sel = in_array($color, $colorsArray); @endphp
                            <button type="button" data-value="{{ $color }}" onclick="toggleChip(this, 'edit-colors-{{ $product->id }}')" class="px-4 py-1.5 border border-gray-200 rounded-md text-sm transition-colors {{ $sel ? 'bg-[#111111] text-white border-[#111111]' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">{{ $color }}</button>
                        @endforeach
                    </div>
                    <input type="hidden" name="colors" id="hidden-edit-colors-{{ $product->id }}" value="{{ json_encode($colorsArray) }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ganti Foto Produk</label>
                    @if($product->main_image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($product->main_image) }}" class="w-16 h-16 rounded object-cover border border-gray-200" alt="">
                        </div>
                    @endif
                    <div class="w-full border-2 border-dashed border-gray-200 rounded-md p-6 flex flex-col items-center justify-center cursor-pointer hover:border-gray-300 hover:bg-gray-50 transition-colors" onclick="document.getElementById('edit-image-input-{{ $product->id }}').click()">
                        <div id="edit-upload-preview-{{ $product->id }}" class="flex flex-wrap gap-2 justify-center w-full"></div>
                        <div id="edit-upload-placeholder-{{ $product->id }}" class="text-center">
                            <span class="text-3xl text-gray-300 block mb-1">+</span>
                            <span class="text-xs font-medium text-gray-400">Klik untuk upload foto baru</span>
                        </div>
                        <input type="file" id="edit-image-input-{{ $product->id }}" name="main_image" accept="image/*" class="hidden"
                            onchange="previewImages(this,'edit-upload-preview-{{ $product->id }}','edit-upload-placeholder-{{ $product->id }}')">
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
            <svg fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="1.8" width="26" height="26"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
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
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Tambah Produk Baru</h2>
        </div>
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body px-6 py-5 space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:ring-1 focus:ring-black focus:border-black outline-none transition-colors" placeholder="Masukkan nama produk" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">SKU <span class="text-red-500">*</span></label>
                        <input type="text" name="sku" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm font-mono focus:ring-1 focus:ring-black focus:border-black outline-none transition-colors" placeholder="mis. TNK-PDK-001" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:ring-1 focus:ring-black focus:border-black outline-none transition-colors bg-white" required>
                            {{-- Revisi: Kategori Tambah Statis Pria / Wanita --}}
                            <option value="" disabled selected>Pilih kategori</option>
                            <option value="1">Pria</option>
                            <option value="2">Wanita</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:ring-1 focus:ring-black focus:border-black outline-none transition-colors" placeholder="0" min="0" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe <span class="text-red-500">*</span></label>
                        <select name="type" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:ring-1 focus:ring-black focus:border-black outline-none transition-colors bg-white" required>
                            <option value="pendek">Pendek</option>
                            <option value="panjang">Panjang</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stok <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:ring-1 focus:ring-black focus:border-black outline-none transition-colors" placeholder="0" min="0" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:ring-1 focus:ring-black focus:border-black outline-none transition-colors resize-y" rows="3" placeholder="Masukkan deskripsi produk"></textarea>
                </div>
                
                {{-- Ukuran (Chips) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ukuran (Size)</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                            <button type="button" data-value="{{ $size }}" onclick="toggleChip(this, 'add-sizes')" class="px-4 py-1.5 bg-gray-50 border border-gray-200 rounded-md text-sm text-gray-600 hover:bg-gray-100 transition-colors">{{ $size }}</button>
                        @endforeach
                    </div>
                    <input type="hidden" name="sizes" id="hidden-add-sizes" value="[]">
                </div>
                
                {{-- Warna (Chips) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Warna</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Hitam', 'Putih', 'Abu-abu', 'Navy', 'Coklat', 'Khaki', 'Olive'] as $color)
                            <button type="button" data-value="{{ $color }}" onclick="toggleChip(this, 'add-colors')" class="px-4 py-1.5 bg-gray-50 border border-gray-200 rounded-md text-sm text-gray-600 hover:bg-gray-100 transition-colors">{{ $color }}</button>
                        @endforeach
                    </div>
                    <input type="hidden" name="colors" id="hidden-add-colors" value="[]">
                </div>
                
                {{-- Upload Foto --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Foto Produk</label>
                    <div class="w-full border-2 border-dashed border-gray-200 rounded-md p-6 flex flex-col items-center justify-center cursor-pointer hover:border-gray-300 hover:bg-gray-50 transition-colors" onclick="document.getElementById('add-image-input').click()">
                        <div id="add-upload-preview" class="flex flex-wrap gap-2 justify-center w-full"></div>
                        <div id="add-upload-placeholder" class="text-center">
                            <span class="text-3xl text-gray-300 block mb-1">+</span>
                            <span class="text-xs font-medium text-gray-400">Klik untuk upload foto</span>
                        </div>
                        <input type="file" id="add-image-input" name="main_image" accept="image/*" class="hidden" onchange="previewImages(this,'add-upload-preview','add-upload-placeholder')">
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

{{-- TOAST NOTIFICATION --}}
<div id="toast" class="fixed bottom-6 right-6 z-[100] hidden items-center gap-3 bg-gray-900 text-white text-sm px-5 py-3 rounded-md shadow-lg transition-opacity">
    <svg fill="none" viewBox="0 0 24 24" stroke="#4ade80" stroke-width="2.5" width="16" height="16"><path d="M20 6L9 17l-5-5"/></svg>
    <span id="toast-msg"></span>
</div>

@endsection

@push('scripts')
<script>
// 1. Preview Gambar
function previewImages(input, previewId, placeholderId) {
    const preview = document.getElementById(previewId);
    const placeholder = document.getElementById(placeholderId);
    preview.innerHTML = '';
    const files = Array.from(input.files);
    if (!files.length) { placeholder.style.display = ''; return; }
    placeholder.style.display = 'none';
    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-16 h-16 rounded object-cover border border-gray-200';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}

// 2. Interactive Chips
function toggleChip(button, inputId) {
    button.classList.toggle('bg-[#111111]');
    button.classList.toggle('text-white');
    button.classList.toggle('border-[#111111]');
    button.classList.toggle('bg-gray-50');
    button.classList.toggle('text-gray-600');
    
    const container = button.parentElement;
    const activeButtons = Array.from(container.querySelectorAll('.bg-\\[\\#111111\\]'));
    const selectedValues = activeButtons.map(btn => btn.getAttribute('data-value'));
    document.getElementById('hidden-' + inputId).value = JSON.stringify(selectedValues);
}

// 3. Status Toggle
function toggleStatus(id, val) {
    fetch(`/admin/products/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ is_active: val })
    })
    .then(res => {
        showToast(val ? 'Produk ditampilkan di katalog' : 'Produk disembunyikan');
    })
    .catch(() => showToast('Gagal mengubah status produk'));
}

// 4. Modal Engine (100% Tailwind Fix)
function openModal(id) {
    const modal = document.getElementById(id);
    const box = modal.children[0]; 
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        if(box) box.classList.remove('scale-95');
    }, 10);
}

function closeModal(id) {
    const modal = document.getElementById(id);
    const box = modal.children[0];
    
    modal.classList.add('opacity-0');
    if(box) box.classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

function closeOnBackdrop(e, id) {
    if (e.target.classList.contains('modal-backdrop')) {
        closeModal(id);
    }
}

// 5. Toast System
function showToast(msg) {
    const t = document.getElementById('toast');
    document.getElementById('toast-msg').textContent = msg;
    t.classList.remove('hidden');
    t.classList.add('flex');
    setTimeout(() => {
        t.classList.add('hidden');
        t.classList.remove('flex');
    }, 3000);
}

// Auto-open modal saat error validasi Laravel
@if($errors->any()) openModal('modal-add'); @endif
</script>
@endpush