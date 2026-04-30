@extends('layouts.main')

@section('title', 'Riwayat Pesanan — TANKEN')

@push('styles')
<style>
    /* Sidebar menu item */
    .menu-item { display: flex; align-items: center; gap: 10px; padding: 11px 16px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; color: #6b7280; cursor: pointer; transition: background 0.15s, color 0.15s; text-decoration: none; white-space: nowrap; flex-shrink: 0; }
    .menu-item:hover { background: #f3f4f6; color: #111; }
    .menu-item.active { background: #111; color: #fff; }
    .menu-item.active svg { stroke: #fff; }
    .menu-item.logout { color: #9ca3af; }
    .menu-item.logout:hover { background: #fee2e2; color: #dc2626; }

    /* Custom Horizontal Scrollbar for Mobile Nav */
    .mobile-nav-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .mobile-nav-scroll::-webkit-scrollbar { height: 8px; }
    .mobile-nav-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 8px; margin: 0 4px; }
    .mobile-nav-scroll::-webkit-scrollbar-thumb { background: #374151; border-radius: 8px; }

    /* Hide scrollbar completely option */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* Avatar */
    .avatar-circle { width: 56px; height: 56px; border-radius: 8px; background: #111; color: #fff; font-size: 1.4rem; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

    /* Order card */
    .order-card { border: 1.5px solid #e5e7eb; border-radius: 8px; padding: 20px 22px; transition: border-color 0.2s, box-shadow 0.2s; cursor: pointer; text-decoration: none; display: block; color: inherit; }
    .order-card:hover { border-color: #111; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }

    /* Status badge */
    .status-badge { display: inline-flex; padding: 5px 12px; border-radius: 4px; border: 1.5px solid #d1d5db; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #374151; background: #fff; white-space: nowrap; }
    .status-pending    { border-color: #fde68a; background: #fefce8; color: #92400e; }
    .status-confirmed  { border-color: #d1d5db; background: #fff;    color: #374151; }
    .status-processing { border-color: #c4b5fd; background: #f5f3ff; color: #5b21b6; }
    .status-shipped    { border-color: #93c5fd; background: #eff6ff; color: #1d4ed8; }
    .status-delivered  { border-color: #86efac; background: #f0fdf4; color: #166534; }
    .status-cancelled  { border-color: #fca5a5; background: #fff1f2; color: #991b1b; }

    /* Empty state */
    .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 64px 24px; text-align: center; }
</style>
@endpush

@section('content')

@php
// Data dummy — nanti diganti dari controller
$orders = $orders ?? collect([
    [
        'id'       => 'ORD-1771750835368',
        'date'     => '2026-02-22',
        'items'    => 1,
        'total'    => 1032200,
        'status'   => 'confirmed',
        'shipping' => 'JNE Reguler',
    ],
    [
        'id'       => 'ORD-1771740123456',
        'date'     => '2026-02-18',
        'items'    => 2,
        'total'    => 2195000,
        'status'   => 'delivered',
        'shipping' => 'SiCepat',
    ],
    [
        'id'       => 'ORD-1771720987654',
        'date'     => '2026-02-10',
        'items'    => 1,
        'total'    => 899000,
        'status'   => 'cancelled',
        'shipping' => '-',
    ],
]);
@endphp

<div class="bg-gray-50/30 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 py-8 lg:py-12">

        {{-- Breadcrumb --}}
        <p class="text-[10px] sm:text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Akun</p>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-6 lg:mb-8">Akun Saya</h1>

        <div class="flex flex-col lg:flex-row gap-6 items-start">

            {{-- ===== SIDEBAR ===== --}}
            <aside class="w-full lg:w-64 flex-shrink-0">
                <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-5">

                    {{-- Avatar & nama (Sembunyikan di layar kecil) --}}
                    <div class="flex flex-col items-center text-center pb-4 mb-4 border-b border-gray-100 hidden lg:flex">
                        <div class="avatar-circle mb-3">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <p class="font-bold text-sm text-gray-900">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                        <p class="text-xs text-gray-400 mt-0.5 break-all">{{ auth()->user()->email ?? 'email@example.com' }}</p>
                    </div>

                    {{-- Menu navigasi (Horizontal Scroll on Mobile, Vertical on Desktop) --}}
                    <nav class="flex flex-row lg:flex-col lg:overflow-visible gap-2 lg:gap-1 pb-3 lg:pb-0 mobile-nav-scroll w-full">
                        <a href="{{ route('pelanggan.profil-edit') }}" class="menu-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Edit Profil
                        </a>
                        <a href="{{ route('pelanggan.profil-password') }}" class="menu-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="15" height="15"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            Ganti Password
                        </a>
                        <a href="{{ route('pelanggan.profil-order') }}" class="menu-item active">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            Pesanan
                        </a>
                        <a href="{{ route('pelanggan.profil-wishlist') }}" class="menu-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            Wishlist
                        </a>
                        <a href="{{ route('pelanggan.profil-alamat') }}" class="menu-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            Alamat
                        </a>
                        <hr class="border-gray-100 my-1 hidden lg:block">
                        <a href="{{ route('logout') }}" class="menu-item logout hidden lg:flex" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="15" height="15"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Keluar
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                    </nav>
                </div>
            </aside>

            {{-- ===== MAIN CONTENT: PESANAN ===== --}}
            <div class="flex-1 min-w-0 w-full">
                <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8">

                    {{-- Section header --}}
                    <p class="text-[10px] sm:text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Riwayat</p>
                    <h2 class="text-lg sm:text-xl font-extrabold text-gray-900 mb-6">Riwayat Pesanan</h2>

                    @if(count($orders) > 0)
                        <div class="flex flex-col gap-4">

                            @foreach($orders as $order)
                            @php
                                $oid     = is_array($order) ? $order['id']       : $order->order_number;
                                $date    = is_array($order) ? $order['date']     : \Carbon\Carbon::parse($order->created_at)->format('d-m-Y');
                                $items   = is_array($order) ? $order['items']    : ($order->items_count ?? 0);
                                $total   = is_array($order) ? $order['total']    : $order->total_amount;
                                $status  = is_array($order) ? $order['status']   : $order->status;

                                $statusLabel = match($status) {
                                    'pending'    => 'Menunggu Pembayaran',
                                    'confirmed'  => 'Dikonfirmasi',
                                    'processing' => 'Sedang Diproses',
                                    'shipped'    => 'Sedang Dikirim',
                                    'delivered'  => 'Pesanan Selesai',
                                    'cancelled'  => 'Dibatalkan',
                                    default      => ucfirst($status),
                                };
                                $statusClass = 'status-' . $status;
                            @endphp

                            <a href="#" class="order-card p-4 sm:p-5">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">

                                    {{-- Kiri: info pesanan --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between sm:justify-start gap-4 mb-2 sm:mb-0">
                                            <p class="font-bold text-sm text-gray-900 font-mono tracking-wide">{{ $oid }}</p>
                                            {{-- Status tampil di atas pada tampilan mobile kecil --}}
                                            <span class="status-badge {{ $statusClass }} sm:hidden">{{ $statusLabel }}</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Tanggal: <span class="font-medium text-gray-700">{{ $date }}</span></p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            Jumlah: <span class="font-medium text-gray-700">{{ $items }} {{ $items > 1 ? 'produk' : 'produk' }}</span>
                                        </p>
                                    </div>

                                    {{-- Kanan: status (Desktop) + total --}}
                                    <div class="flex flex-col sm:items-end gap-2 flex-shrink-0 mt-2 sm:mt-0 pt-3 sm:pt-0 border-t border-gray-100 sm:border-0">
                                        <span class="status-badge {{ $statusClass }} hidden sm:inline-flex">{{ $statusLabel }}</span>
                                        <div class="flex justify-between sm:flex-col sm:items-end items-center w-full">
                                            <span class="text-xs text-gray-500 sm:hidden">Total Belanja</span>
                                            <span class="font-extrabold text-sm sm:text-base text-gray-900">
                                                Rp {{ number_format($total, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </a>
                            @endforeach

                        </div>
                    @else
                        {{-- Empty state --}}
                        <div class="empty-state">
                            <div class="w-16 h-16 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#9ca3af" stroke-width="1.5" width="28" height="28">
                                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
                                </svg>
                            </div>
                            <p class="font-bold text-gray-900 text-base mb-1">Belum ada pesanan</p>
                            <p class="text-sm text-gray-500 mb-6">Kamu belum pernah melakukan pembelian produk TANKEN.</p>
                            <a href="{{ route('pelanggan.katalog') }}" class="inline-flex items-center gap-2 bg-gray-900 text-white text-xs font-bold tracking-widest uppercase px-6 py-3.5 rounded-md hover:bg-gray-800 transition-colors shadow-sm">
                                Mulai Belanja
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="14" height="14"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>

@endsection