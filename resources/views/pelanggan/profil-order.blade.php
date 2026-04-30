@extends('layouts.akun-pelanggan')

@section('title', 'Riwayat Pesanan — TANKEN')

@push('akun-styles')
<style>
    /* Order card */
    .order-card { border: 1.5px solid #e5e7eb; border-radius: 8px; transition: border-color 0.2s, box-shadow 0.2s; cursor: pointer; text-decoration: none; display: block; color: inherit; }
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

@section('akun-content')

@php
// Data dummy pesanan
$orders = $orders ?? collect([
    [
        'id'       => 'ORD-1771750835368',
        'date'     => '2026-02-22',
        'items'    => 1,
        'total'    => 1032200,
        'status'   => 'confirmed',
    ],
    [
        'id'       => 'ORD-1771740123456',
        'date'     => '2026-02-18',
        'items'    => 2,
        'total'    => 2195000,
        'status'   => 'delivered',
    ],
]);
@endphp

<div>
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
                            <span class="status-badge {{ $statusClass }} sm:hidden">{{ $statusLabel }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Tanggal: <span class="font-medium text-gray-700">{{ $date }}</span></p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Jumlah: <span class="font-medium text-gray-700">{{ $items }} produk</span>
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
@endsection