@extends('layouts.akun-pelanggan')

@section('title', 'Riwayat Pesanan — TANKEN')

{{-- Panggil FontAwesome menggunakan push ke 'styles' utama agar tidak tertelan tag <style> --}}
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

{{-- CSS khusus untuk halaman ini (tanpa tag link) --}}
@push('akun-styles')
    /* Menyembunyikan scrollbar untuk tab navigasi */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* Transisi untuk accordion icon */
    .accordion-icon { transition: transform 0.3s ease; }
    .accordion-icon.open { transform: rotate(180deg); }

    /* Timeline tracking line */
    .tracking-line {
        position: absolute;
        top: 50%;
        left: 30px;
        right: 30px;
        height: 2px;
        background-color: #e5e7eb;
        transform: translateY(-50%);
        z-index: 0;
    }
    @media (max-width: 640px) {
        .tracking-line { left: 20px; right: 20px; }
    }
@endpush

@section('akun-content')

@php
// Data dummy pesanan
$orders = collect([
    [
        'id'       => 'TKN-2026-0001',
        'date'     => '10 Feb 2026',
        'est_date' => '17 Feb 2026',
        'items'    => 1,
        'total'    => 1499000,
        'status'   => 'shipped',
        'tracking' => 'TJN123456789',
        'product'  => ['name' => 'Flex Performance Jogger', 'size' => 'M', 'color' => 'Black', 'qty' => 1, 'price' => 1499000],
        'address'  => 'Jl. Raya Dramaga, Margajaya, Kec. Bogor Barat, Kota Bogor, Jawa Barat 16680',
        'payment'  => 'GoPay',
    ],
    [
        'id'       => 'TKN-2026-0002',
        'date'     => '05 Feb 2026',
        'est_date' => '12 Feb 2026',
        'items'    => 1,
        'total'    => 899000,
        'status'   => 'delivered',
        'tracking' => 'TJN987654321',
        'product'  => ['name' => 'Classic Chino Pants', 'size' => 'L', 'color' => 'Khaki', 'qty' => 1, 'price' => 899000],
        'address'  => 'Jl. Raya Dramaga, Margajaya, Kec. Bogor Barat, Kota Bogor, Jawa Barat 16680',
        'payment'  => 'Bank Transfer (BCA)',
    ],
    [
        'id'       => 'TKN-2026-0003',
        'date'     => '28 Jan 2026',
        'est_date' => '04 Feb 2026',
        'items'    => 2,
        'total'    => 2150000,
        'status'   => 'processing',
        'tracking' => '-',
        'product'  => ['name' => 'Urban Cargo Pants', 'size' => 'XL', 'color' => 'Olive', 'qty' => 2, 'price' => 1075000],
        'address'  => 'Jl. Raya Dramaga, Margajaya, Kec. Bogor Barat, Kota Bogor, Jawa Barat 16680',
        'payment'  => 'Credit Card ending in 4242',
    ]
]);
@endphp

<div>
    {{-- Header --}}
    <p class="text-[10px] sm:text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Manajemen Pesanan</p>
    <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 mb-2">Pesanan Saya</h2>
    <p class="text-sm text-gray-500 mb-8">Lacak dan kelola riwayat pesanan TANKEN kamu.</p>

    {{-- Tabs Navigasi dengan Javascript Filter --}}
    <div class="flex items-center gap-6 sm:gap-8 border-b border-gray-200 overflow-x-auto hide-scrollbar mb-8">
        <button onclick="filterOrders('all', this)" class="tab-btn whitespace-nowrap pb-3 border-b-2 border-black font-bold text-sm text-gray-900 uppercase tracking-wider text-[11px] transition-colors">Semua</button>
        <button onclick="filterOrders('processing', this)" class="tab-btn whitespace-nowrap pb-3 border-b-2 border-transparent font-bold text-gray-400 hover:text-gray-900 uppercase tracking-wider text-[11px] transition-colors">Diproses</button>
        <button onclick="filterOrders('shipped', this)" class="tab-btn whitespace-nowrap pb-3 border-b-2 border-transparent font-bold text-gray-400 hover:text-gray-900 uppercase tracking-wider text-[11px] transition-colors">Dikirim</button>
        <button onclick="filterOrders('delivered', this)" class="tab-btn whitespace-nowrap pb-3 border-b-2 border-transparent font-bold text-gray-400 hover:text-gray-900 uppercase tracking-wider text-[11px] transition-colors">Selesai</button>
        <button onclick="filterOrders('cancelled', this)" class="tab-btn whitespace-nowrap pb-3 border-b-2 border-transparent font-bold text-gray-400 hover:text-gray-900 uppercase tracking-wider text-[11px] transition-colors">Dibatalkan</button>
    </div>

    {{-- List Pesanan --}}
    <div class="flex flex-col gap-4" id="orders-container">

        @forelse($orders as $index => $order)
        @php
            $statusLabel = match($order['status']) {
                'processing' => 'DIPROSES',
                'shipped'    => 'DIKIRIM',
                'delivered'  => 'SELESAI',
                'cancelled'  => 'DIBATALKAN',
                default      => strtoupper($order['status']),
            };
            
            // Warna Badge Status (Hijau Pastel untuk Selesai, sisanya standar TANKEN)
            $badgeClass = match($order['status']) {
                'delivered'  => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                'shipped'    => 'bg-gray-800 text-white',
                'processing' => 'bg-gray-100 text-gray-800 border border-gray-200',
                'cancelled'  => 'bg-red-50 text-red-700 border border-red-100',
                default      => 'bg-gray-100 text-gray-800',
            };
        @endphp

        {{-- Wrapper Card Pesanan --}}
        <div class="order-wrapper border border-gray-200 rounded-lg overflow-hidden bg-white" data-status="{{ $order['status'] }}">
            
            {{-- Bagian Header Kartu --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-5 cursor-pointer hover:bg-gray-50 transition-colors" onclick="toggleAccordion('order-{{ $index }}')">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-1.5">
                        <span class="font-extrabold text-sm text-gray-900 tracking-wide">{{ $order['id'] }}</span>
                        <span class="text-[9px] px-2 py-0.5 rounded uppercase font-bold tracking-widest {{ $badgeClass }}">{{ $statusLabel }}</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs text-gray-500 font-medium">
                        <span>Dipesan: {{ $order['date'] }}</span>
                        <span class="hidden sm:inline-block w-1 h-1 rounded-full bg-gray-300"></span>
                        <span>Est. Tiba: {{ $order['est_date'] }}</span>
                        <span class="hidden sm:inline-block w-1 h-1 rounded-full bg-gray-300"></span>
                        <span>{{ $order['items'] }} Produk</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between sm:justify-end gap-4 mt-3 sm:mt-0 pt-3 sm:pt-0 border-t border-gray-100 sm:border-0 w-full sm:w-auto">
                    <span class="font-extrabold text-sm sm:text-base text-gray-900">Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
                    <svg id="icon-order-{{ $index }}" class="accordion-icon w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            {{-- Bagian Detail Body --}}
            <div id="body-order-{{ $index }}" class="hidden border-t border-gray-100">
                <div class="p-5 sm:p-6 bg-white">
                    
                    {{-- Timeline Tracking (Hanya Tampil Jika Tidak Dibatalkan) --}}
                    @if($order['status'] !== 'cancelled')
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Status Pengiriman</span>
                            <span class="text-[10px] font-mono font-medium text-gray-500">Resi: {{ $order['tracking'] }}</span>
                        </div>
                        
                        <div class="relative w-full max-w-lg mx-auto py-2">
                            <div class="tracking-line"></div>
                            <div class="relative z-10 flex justify-between items-center w-full">
                                
                                {{-- Step 1: Dipesan --}}
                                <div class="flex flex-col items-center bg-white px-2">
                                    <div class="w-8 h-8 rounded-full bg-black flex items-center justify-center shadow-sm">
                                        <i class="fa-solid fa-clipboard-check text-sm" style="color: rgb(255, 255, 255);"></i>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase mt-2">Dipesan</span>
                                </div>
                                
                                {{-- Step 2: Dikirim --}}
                                <div class="flex flex-col items-center bg-white px-2">
                                    <div class="w-8 h-8 rounded-full {{ in_array($order['status'], ['shipped', 'delivered']) ? 'bg-black' : 'bg-gray-100' }} flex items-center justify-center shadow-sm transition-colors">
                                        <i class="fa-solid fa-truck text-sm" {!! in_array($order['status'], ['shipped', 'delivered']) ? 'style="color: rgb(255, 254, 254);"' : 'style="color: #9ca3af;"' !!}></i>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase mt-2 {{ in_array($order['status'], ['shipped', 'delivered']) ? 'text-black' : 'text-gray-400' }}">Dikirim</span>
                                </div>
                                
                                {{-- Step 3: Selesai (Hitam & Ikon Kardus Putih) --}}
                                <div class="flex flex-col items-center bg-white px-2">
                                    <div class="w-8 h-8 rounded-full {{ $order['status'] == 'delivered' ? 'bg-black' : 'bg-gray-100' }} flex items-center justify-center shadow-sm transition-colors">
                                        <i class="fa-solid fa-box-open text-sm" {!! $order['status'] == 'delivered' ? 'style="color: rgb(255, 255, 255);"' : 'style="color: #9ca3af;"' !!}></i>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase mt-2 {{ $order['status'] == 'delivered' ? 'text-black' : 'text-gray-400' }}">Selesai</span>
                                </div>

                            </div>
                        </div>
                    </div>
                    @else
                    {{-- Alert jika dibatalkan --}}
                    <div class="bg-red-50 border border-red-100 rounded-md p-4 mb-6 flex items-center gap-3">
                        <i class="fa-regular fa-circle-xmark text-xl" style="color: rgb(244, 86, 86);"></i>
                        <p class="text-sm text-red-800">Pesanan ini telah dibatalkan.</p>
                    </div>
                    @endif

                    {{-- Detail Produk --}}
                    <div class="mb-6">
                        <span class="text-[10px] font-bold tracking-widest text-gray-400 uppercase block mb-3">Daftar Produk</span>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-md bg-gray-100 flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-200">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($order['product']['name']) }}&background=random" alt="Product" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $order['product']['name'] }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">Ukuran: {{ $order['product']['size'] }} | Warna: {{ $order['product']['color'] }} | Qty: {{ $order['product']['qty'] }}</p>
                            </div>
                            <div class="font-bold text-sm text-gray-900 flex-shrink-0">
                                Rp {{ number_format($order['product']['price'], 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    {{-- Alamat & Pembayaran --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50/50 p-4 rounded-md border border-gray-100">
                            <div class="flex items-center gap-2 mb-2 text-[10px] font-bold tracking-widest text-gray-400 uppercase">
                                <i class="fa-solid fa-location-dot text-gray-400"></i>
                                Alamat Pengiriman
                            </div>
                            <p class="text-xs text-gray-700 leading-relaxed">{{ $order['address'] }}</p>
                        </div>
                        
                        <div class="bg-gray-50/50 p-4 rounded-md border border-gray-100">
                            <div class="flex items-center gap-2 mb-2 text-[10px] font-bold tracking-widest text-gray-400 uppercase">
                                <i class="fa-solid fa-credit-card text-gray-400"></i>
                                Metode Pembayaran
                            </div>
                            <p class="text-xs text-gray-700 font-medium">{{ $order['payment'] }}</p>
                        </div>
                    </div>

                    {{-- Tombol Aksi Dinamis (Berubah sesuai status pesanan) --}}
                    <div class="flex flex-col sm:flex-row items-center gap-3 pt-4 border-t border-gray-100">
                        <button class="w-full sm:w-1/2 bg-black text-white text-[10px] sm:text-xs font-bold tracking-widest uppercase py-3.5 rounded-md hover:bg-gray-800 transition-colors">
                            Beli Lagi
                        </button>
                        
                        {{-- Logika Tombol Ulasan ala Shopee --}}
                        @if($order['status'] == 'delivered')
                            <button class="w-full sm:w-1/2 bg-white text-black border border-gray-200 text-[10px] sm:text-xs font-bold tracking-widest uppercase py-3.5 rounded-md hover:bg-gray-50 transition-colors">
                                Beri Ulasan
                            </button>
                        @elseif($order['status'] == 'cancelled')
                            <button class="w-full sm:w-1/2 bg-white text-black border border-gray-200 text-[10px] sm:text-xs font-bold tracking-widest uppercase py-3.5 rounded-md hover:bg-gray-50 transition-colors">
                                Rincian Batal
                            </button>
                        @else
                            <button class="w-full sm:w-1/2 bg-white text-black border border-gray-200 text-[10px] sm:text-xs font-bold tracking-widest uppercase py-3.5 rounded-md hover:bg-gray-50 transition-colors">
                                Lacak Paket
                            </button>
                        @endif
                    </div>

                </div>
            </div>
        </div>
        @empty
        {{-- State kosong mutlak dari Database --}}
        <div class="flex flex-col items-center justify-center py-16 text-center bg-white border border-gray-200 rounded-lg">
            <div class="w-16 h-16 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center mb-4">
                <i class="fa-solid fa-box-open text-gray-400 text-2xl"></i>
            </div>
            <p class="font-bold text-gray-900 text-base mb-1">Belum ada pesanan</p>
            <p class="text-sm text-gray-500 mb-6">Kamu belum pernah melakukan pembelian produk TANKEN.</p>
            <a href="{{ route('pelanggan.katalog') }}" class="inline-flex items-center gap-2 bg-gray-900 text-white text-xs font-bold tracking-widest uppercase px-6 py-3.5 rounded-md hover:bg-gray-800 transition-colors shadow-sm">
                Mulai Belanja
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        @endforelse

        {{-- STATE KOSONG DARI HASIL FILTER TAB --}}
        <div id="filter-empty-state" class="hidden flex-col items-center justify-center py-16 text-center bg-white border border-gray-200 rounded-lg">
            <i class="fa-regular fa-circle-xmark text-5xl mb-4" style="color: rgb(244, 86, 86);"></i>
            <p class="font-bold text-gray-900 text-base mb-1">Pesanan Tidak Ditemukan</p>
            <p class="text-sm text-gray-500 mb-0">Kamu tidak memiliki pesanan dengan status ini.</p>
        </div>

    </div>
</div>
@endsection

@push('akun-scripts')
<script>
    // Fungsi Accordion
    function toggleAccordion(id) {
        const body = document.getElementById('body-' + id);
        const icon = document.getElementById('icon-' + id);
        
        if (body.classList.contains('hidden')) {
            body.classList.remove('hidden');
            icon.classList.add('open');
        } else {
            body.classList.add('hidden');
            icon.classList.remove('open');
        }
    }

    // Fungsi Filter Kategori Tab
    function filterOrders(status, btnElement) {
        const tabs = document.querySelectorAll('.tab-btn');
        tabs.forEach(tab => {
            tab.classList.remove('border-black', 'text-gray-900');
            tab.classList.add('border-transparent', 'text-gray-400');
        });
        btnElement.classList.remove('border-transparent', 'text-gray-400');
        btnElement.classList.add('border-black', 'text-gray-900');

        const orders = document.querySelectorAll('.order-wrapper');
        let visibleCount = 0;

        orders.forEach(order => {
            if (status === 'all' || order.dataset.status === status) {
                order.style.display = 'block';
                visibleCount++;
            } else {
                order.style.display = 'none';
            }
        });

        const emptyState = document.getElementById('filter-empty-state');
        if (visibleCount === 0) {
            emptyState.classList.remove('hidden');
            emptyState.classList.add('flex');
        } else {
            emptyState.classList.remove('flex');
            emptyState.classList.add('hidden');
        }
    }
</script>
@endpush