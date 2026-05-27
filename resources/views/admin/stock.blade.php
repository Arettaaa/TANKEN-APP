@extends('layouts.admin')

@section('title', 'Stock Management')
@section('page-title', 'Stock Management')
@section('breadcrumb', 'Admin / Stock Management')

@push('styles')
<style>
    .stat-icon { width: 42px; height: 42px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .stock-bar-wrap { width: 120px; height: 5px; background: #e5e7eb; border-radius: 99px; overflow: hidden; }
    .stock-bar-fill { height: 100%; border-radius: 99px; transition: width 0.4s ease; }
    .filter-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; background-size: 14px; padding-right: 32px; cursor: pointer; }
    .search-box { position: relative; }
    .search-box input { padding: 9px 14px 9px 36px; border: 1.5px solid #e5e7eb; border-radius: 6px; font-size: 0.8rem; font-family: 'Inter', sans-serif; outline: none; width: 240px; background: #fff; transition: border-color 0.2s; }
    .search-box input:focus { border-color: #111; }
    .search-box svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; pointer-events: none; }
    .badge-instock  { background: #dcfce7; color: #16a34a; }
    .badge-lowstock { background: #fef9c3; color: #ca8a04; }
    .badge-outstock { background: #fee2e2; color: #dc2626; }
    .stock-row:hover { background: #fafafa; }
    .product-thumb { width: 44px; height: 44px; border-radius: 6px; object-fit: cover; background: #f3f4f6; flex-shrink: 0; }
    .btn-update { padding: 6px 12px; background: #fff; color: #111; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.75rem; font-weight: 600; transition: all 0.2s; }
    .btn-update:hover { background: #f9fafb; border-color: #9ca3af; }
    
    /* Scrollbar khusus list variasi di modal */
    .variation-list::-webkit-scrollbar { width: 4px; }
    .variation-list::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
</style>
@endpush

@section('content')
{{-- ===== STAT CARDS ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-gray-100 rounded-md p-5 shadow-sm">
        <div class="stat-icon bg-blue-50 mb-4"><i class="fa-solid fa-box-open text-[#3b82f6]"></i></div>
        <div class="text-2xl font-extrabold text-gray-900">{{ $totalProducts ?? 0 }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium">Total Products</div>
    </div>
    <div class="bg-white border border-gray-100 rounded-md p-5 shadow-sm">
        <div class="stat-icon bg-yellow-50 mb-4"><i class="fa-solid fa-triangle-exclamation text-[#eab308]"></i></div>
        <div class="text-2xl font-extrabold text-gray-900">{{ $lowStockItems ?? 0 }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium">Low Stock Items</div>
    </div>
    <div class="bg-white border border-gray-100 rounded-md p-5 shadow-sm">
        <div class="stat-icon bg-red-50 mb-4"><i class="fa-solid fa-circle-exclamation text-[#ef4444]"></i></div>
        <div class="text-2xl font-extrabold text-gray-900">{{ $outOfStock ?? 0 }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium">Out of Stock</div>
    </div>
    <div class="bg-white border border-gray-100 rounded-md p-5 shadow-sm">
        <div class="stat-icon bg-green-50 mb-4"><i class="fa-solid fa-arrow-trend-up text-[#22c55e]"></i></div>
        <div class="text-2xl font-extrabold text-gray-900">{{ $totalUnits ?? 0 }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium">Total Units Available</div>
    </div>
</div>

{{-- ===== FILTER & SEARCH ===== --}}
<div class="bg-white border border-gray-100 rounded-md p-4 mb-4 shadow-sm">
    <div class="flex flex-wrap items-center gap-3">
        <select class="filter-select text-sm border border-gray-200 rounded-md px-3 py-2 text-gray-600 bg-white focus:border-gray-900" id="filterStatus">
            <option value="">&nbsp;&nbsp;&nbsp;Status Stok</option>
            <option value="instock">In Stock</option>
            <option value="lowstock">Low Stock</option>
            <option value="outstock">Out of Stock</option>
        </select>
        <div class="flex-1"></div>
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" id="searchInput" placeholder="Cari nama produk atau SKU...">
        </div>
        <a href="{{ route('admin.stock.export') }}" class="flex items-center gap-2 border border-gray-200 rounded-md px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            <i class="fa-solid fa-download"></i> Export Excel
        </a>
    </div>
</div>

{{-- ===== TABLE ===== --}}
<div class="bg-white border border-gray-100 rounded-md overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="stockTable">
            <thead>
                <tr class="bg-[#111111] text-white">
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Product</th>
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">SKU</th>
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Category</th>
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Total Stock</th>
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Min Stock</th>
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Status</th>
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="stockBody">

                @forelse($products as $item)
                @php
                    $stock = $item->total_stock;
                    $minStock = 20;

                    if ($stock <= 0) {
                        $statusKey = 'outstock'; $statusLabel = 'Out of Stock'; $statusClass = 'badge-outstock'; $barColor = '#ef4444';
                    } elseif ($stock < $minStock) {
                        $statusKey = 'lowstock'; $statusLabel = 'Low Stock'; $statusClass = 'badge-lowstock'; $barColor = '#eab308';
                    } else {
                        $statusKey = 'instock'; $statusLabel = 'In Stock'; $statusClass = 'badge-instock'; $barColor = '#22c55e';
                    }
                    $barPct = min(100, round(($stock / 100) * 100));
                @endphp

                <tr class="stock-row transition-colors" data-status="{{ $statusKey }}" data-name="{{ strtolower($item->name) }}" data-sku="{{ strtolower($item->sku) }}" data-stock="{{ $stock }}"> 
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $item->main_image_url }}" alt="{{ $item->name }}" class="product-thumb">
                            <span class="font-semibold text-gray-900">{{ $item->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4"><span class="text-gray-500 text-xs font-mono">{{ $item->sku }}</span></td>
                    <td class="px-5 py-4">
                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-md bg-gray-100 text-gray-600">
                            {{ $item->category->name ?? 'Uncategorized' }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="font-bold text-gray-900 text-sm mb-1.5">{{ $stock }} units</div>
                        <div class="stock-bar-wrap">
                            <div class="stock-bar-fill" style="width: {{ $barPct }}%; background: {{ $barColor }};"></div>
                        </div>
                    </td>
                    <td class="px-5 py-4"><span class="text-gray-500 text-sm">{{ $minStock }}</span></td>
                    <td class="px-5 py-4">
                        <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-md {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        {{-- Mengirim data array variasi ke Javascript via atribut data --}}
                        <button class="btn-update flex items-center gap-1.5" 
                                data-id="{{ $item->id }}" 
                                data-name="{{ htmlspecialchars($item->name) }}" 
                                data-variations="{{ $item->stocks->toJson() }}"
                                onclick="openModal(this)">
                            <i class="fa-solid fa-pen-to-square"></i> Update
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                            <i class="fa-solid fa-box-open text-2xl text-gray-300"></i>
                        </div>
                        <p class="text-sm text-gray-500 font-medium">Belum ada produk di database</p>
                    </td>
                </tr>
                @endforelse

            </tbody>
            <tbody id="emptyState" class="hidden">
            <tr>
                <td colspan="7" class="py-16 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                        <i class="fa-solid fa-box-open text-2xl text-gray-300"></i>
                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Tidak ada data yang tersedia
                    </p>

                    <p class="text-xs text-gray-400 mt-1">
                        Coba gunakan kata kunci lain atau ubah filter
                    </p>
                </td>
            </tr>
        </tbody>
        </table>
    </div>
</div>

{{-- ===== MODAL UPDATE STOCK (DETAIL VARIASI) ===== --}}
<div id="stockModal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
    
    <div class="bg-white w-full max-w-md rounded-xl shadow-2xl relative z-10 transform scale-95 opacity-0 transition-all duration-200" id="modalContent">
        <div class="p-5 border-b border-gray-100 flex items-start justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Update Stok Variasi</h3>
                <p class="text-xs text-gray-500 mt-1" id="modalProductName">Nama Produk</p>
            </div>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-800"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        
        {{-- Tempat render list variasi --}}
        <div class="p-5 variation-list max-h-64 overflow-y-auto" id="variationListContainer">
            </div>

        <input type="hidden" id="modalProductId">
        
        <div class="p-5 border-t border-gray-100 flex items-center justify-end gap-3 bg-gray-50 rounded-b-xl">
            <button onclick="closeModal()" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-200 rounded-lg transition-colors">Batal</button>
            <button onclick="submitStock()" id="modalSaveBtn" class="px-5 py-2 text-sm font-semibold text-white bg-black hover:bg-gray-800 rounded-lg transition-colors flex items-center gap-2">
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function applyFilters() {

    const search = document.getElementById('searchInput').value.toLowerCase();

    const status = document.getElementById('filterStatus').value;

    const rows = document.querySelectorAll('#stockBody tr.stock-row');

    let visibleCount = 0;

    rows.forEach(row => {

        const matchSearch =
            !search ||
            row.dataset.name.includes(search) ||
            row.dataset.sku.includes(search) ||
            row.dataset.stock.includes(search);

        const matchStatus =
            !status ||
            row.dataset.status === status;

        if (matchSearch && matchStatus) {

            row.style.display = '';
            visibleCount++;

        } else {

            row.style.display = 'none';
        }
    });

    // EMPTY STATE
    const emptyState = document.getElementById('emptyState');

    if (visibleCount === 0) {
        emptyState.classList.remove('hidden');
    } else {
        emptyState.classList.add('hidden');
    }
}

    document.getElementById('searchInput').addEventListener('input', applyFilters);
    document.getElementById('filterStatus').addEventListener('change', applyFilters);

    // ---- Modal Logic ----
    const modal = document.getElementById('stockModal');
    const modalContent = document.getElementById('modalContent');
    const inputId = document.getElementById('modalProductId');
    const productNameText = document.getElementById('modalProductName');
    const variationListContainer = document.getElementById('variationListContainer');

    function openModal(btn) {
        // Ambil data dari tombol yang diklik
        const id = btn.dataset.id;
        const name = btn.dataset.name;
        const variations = JSON.parse(btn.dataset.variations);

        inputId.value = id;
        productNameText.innerText = name;
        
        // Render daftar variasi (Warna & Size)
        variationListContainer.innerHTML = '';
        if(variations.length === 0) {
            variationListContainer.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">Produk ini belum memiliki variasi stok.</p>';
        } else {
            let html = '<div class="space-y-3">';
            variations.forEach(v => {
                const color = v.color || 'Default';
                const size = v.size || '-';
                html += `
                    <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-shirt"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">${color}</p>
                                <p class="text-[11px] text-gray-500 font-medium">Size: ${size}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-gray-400">Qty:</span>
                            <input type="number" class="var-input w-20 px-3 py-1.5 border-1.5 border-gray-300 rounded-md text-sm text-center focus:border-black focus:ring-0 outline-none" data-id="${v.id}" value="${v.quantity}" min="0">
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            variationListContainer.innerHTML = html;
        }
        
        // Tampilkan Modal
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModal() {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { modal.classList.add('hidden'); }, 200);
    }

    function submitStock() {
        const id = inputId.value;
        const btn = document.getElementById('modalSaveBtn');
        const inputs = document.querySelectorAll('.var-input');
        
        let payloadVariations = [];
        inputs.forEach(input => {
            payloadVariations.push({
                id: input.dataset.id,
                quantity: parseInt(input.value) || 0
            });
        });

        if(payloadVariations.length === 0) return closeModal(); // Jika tidak ada variasi

        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
        btn.disabled = true;

        fetch(`/admin/stock/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ variations: payloadVariations })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload(); // Reload untuk memperbarui Total Stok di tabel utama
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.innerHTML = 'Simpan Perubahan';
            btn.disabled = false;
            alert('Gagal menyimpan stok. Silakan coba lagi.');
        });
    }

</script>
@endpush