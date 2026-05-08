@extends('layouts.admin')

@section('title', 'Dashboard Overview')
@section('page-title', 'Dashboard Overview')
@section('breadcrumb', 'Home / Dashboard')

@section('content')

{{-- ====== STATS CARDS ====== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    {{-- Total Sales --}}
    <div class="bg-white rounded-md p-5 shadow-sm border border-gray-100">
        <div class="w-11 h-11 rounded flex items-center justify-center mb-4" style="background:#d1fae5">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#10b981" stroke-width="2" width="22" height="22">
                <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
        </div>
        <p class="text-2xl font-extrabold text-gray-900 leading-tight">
            Rp{{ number_format($totalSales ?? 125430000, 0, ',', '.') }}
        </p>
        <p class="text-xs text-gray-400 mt-1 font-medium uppercase tracking-wide">Total Sales</p>
    </div>

    {{-- Total Orders --}}
    <div class="bg-white rounded-md p-5 shadow-sm border border-gray-100">
        <div class="w-11 h-11 rounded flex items-center justify-center mb-4" style="background:#dbeafe">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#3b82f6" stroke-width="2" width="22" height="22">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
        </div>
        <p class="text-2xl font-extrabold text-gray-900 leading-tight">
            {{ number_format($totalOrders ?? 543, 0, ',', '.') }}
        </p>
        <p class="text-xs text-gray-400 mt-1 font-medium uppercase tracking-wide">Total Orders</p>
    </div>

    {{-- Total Users --}}
    <div class="bg-white rounded-md p-5 shadow-sm border border-gray-100">
        <div class="w-11 h-11 rounded flex items-center justify-center mb-4" style="background:#ede9fe">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#8b5cf6" stroke-width="2" width="22" height="22">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <p class="text-2xl font-extrabold text-gray-900 leading-tight">
            {{ number_format($totalUsers ?? 1247, 0, ',', '.') }}
        </p>
        <p class="text-xs text-gray-400 mt-1 font-medium uppercase tracking-wide">Total Users</p>
    </div>

    {{-- Low Stock --}}
    <div class="bg-white rounded-md p-5 shadow-sm border border-gray-100">
        <div class="w-11 h-11 rounded flex items-center justify-center mb-4" style="background:#fee2e2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="2" width="22" height="22">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>
        <p class="text-2xl font-extrabold text-gray-900 leading-tight">
            {{ $lowStockItems ?? 8 }}
        </p>
        <p class="text-xs text-gray-400 mt-1 font-medium uppercase tracking-wide">Low Stock Items</p>
    </div>
</div>

{{-- ====== CHARTS ROW ====== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

    {{-- Sales Overview Chart --}}
    <div class="bg-white rounded-md p-5 shadow-sm border border-gray-100">
        <h2 class="text-base font-bold text-gray-900 mb-4">Sales Overview</h2>
        <div class="relative h-52">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    {{-- Best Selling Products Chart --}}
    <div class="bg-white rounded-md p-5 shadow-sm border border-gray-100">
        <h2 class="text-base font-bold text-gray-900 mb-4">Best Selling Products</h2>
        <div class="relative h-52">
            <canvas id="bestSellingChart"></canvas>
        </div>
    </div>
</div>

{{-- ====== RECENT ACTIVITY ====== --}}
<div class="bg-white rounded-md shadow-sm border border-gray-100">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-base font-bold text-gray-900">Recent Activity</h2>
        <a href="{{ route('admin.orders.index') }}" class="text-xs text-gray-400 hover:text-black transition-colors">View all →</a>
    </div>
    <div class="divide-y divide-gray-50">
        @forelse($recentActivity ?? [] as $log)
        <div class="flex items-center justify-between px-5 py-4">
            <div>
                <p class="text-sm font-semibold text-gray-900">{{ $log->subject }}</p>
                @if($log->description)
                <p class="text-xs text-gray-400 mt-0.5">{{ $log->description }}</p>
                @endif
            </div>
            <span class="text-xs text-gray-400 flex-shrink-0 ml-4">{{ $log->created_at->diffForHumans() }}</span>
        </div>
        @empty
        {{-- Dummy data fallback saat belum ada log --}}
        @foreach([
            ['subject' => 'New order received',    'desc' => 'ORD-001 — Rp1.850.000',            'time' => '5 mins ago'],
            ['subject' => 'Stok diupdate',          'desc' => 'Athletic Flow Joggers — 45 unit', 'time' => '1 jam lalu'],
            ['subject' => 'User baru terdaftar',    'desc' => 'jane@example.com',                'time' => '2 jam lalu'],
            ['subject' => 'Pembayaran dikonfirmasi','desc' => 'ORD-002 — Rp2.350.000',           'time' => '3 jam lalu'],
        ] as $item)
        <div class="flex items-center justify-between px-5 py-4">
            <div>
                <p class="text-sm font-semibold text-gray-900">{{ $item['subject'] }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $item['desc'] }}</p>
            </div>
            <span class="text-xs text-gray-400 flex-shrink-0 ml-4">{{ $item['time'] }}</span>
        </div>
        @endforeach
        @endforelse
    </div>
</div>

@endsection

@php
    $salesLabels = $salesChart->isNotEmpty() 
        ? $salesChart->pluck('month')->toArray() 
        : ['Jan','Feb','Mar','Apr','Mei','Jun'];

    $salesData = $salesChart->isNotEmpty() 
        ? $salesChart->pluck('total')->map(function($v) { return (int)$v; })->toArray()
        : [12800000, 13200000, 19300000, 14300000, 21000000, 25900000];

    $bsLabels = $bestSelling->isNotEmpty() 
        ? $bestSelling->pluck('product_name')->toArray() 
        : ['Athletic Flow','Sport Luxe','Cargo Pants','Joggers','Chinos'];

    $bsData = $bestSelling->isNotEmpty() 
        ? $bestSelling->pluck('total_sold')->map(function($v) { return (int)$v; })->toArray()
        : [142, 128, 110, 89, 74];
@endphp

@push('scripts')
<script>
    const salesLabels = @json($salesLabels);
    const salesData   = @json($salesData);

    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: salesLabels,
            datasets: [{
                data: salesData,
                borderColor: '#111111',
                backgroundColor: 'rgba(0,0,0,0.04)',
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: '#111',
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                y: {
                    grid: { color: '#f3f4f6' },
                    ticks: {
                        font: { size: 10 },
                        callback: v => {
                            if (v >= 1000000) return 'Rp' + (v/1000000).toFixed(1) + 'jt';
                            if (v >= 1000)    return 'Rp' + (v/1000).toFixed(0) + 'rb';
                            return 'Rp' + v;
                        }
                    }
                }
            }
        }
    });

    const bsLabels = @json($bsLabels);
    const bsData   = @json($bsData);

    new Chart(document.getElementById('bestSellingChart'), {
        type: 'bar',
        data: {
            labels: bsLabels,
            datasets: [{
                data: bsData,
                backgroundColor: '#111111',
                borderRadius: 3,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                y: { grid: { color: '#f3f4f6' }, ticks: { font: { size: 11 } } }
            }
        }
    });
</script>
@endpush