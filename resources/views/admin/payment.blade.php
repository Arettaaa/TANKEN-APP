@extends('layouts.admin')

@section('title', 'Payment Management')
@section('page-title', 'Payment Management')
@section('breadcrumb', 'Admin / Payment Management')

@push('styles')
<style>
    /* Custom Dropdown Filter */
    .dropdown-item:hover { background-color: #f9fafb; }
    .custom-dropdown-btn { appearance: none; background-color: white; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 14px; font-size: 0.875rem; color: #4b5563; display: flex; align-items: center; justify-content: space-between; gap: 10px; cursor: pointer; transition: border-color 0.2s; }
    .custom-dropdown-btn:focus { border-color: #111; outline: none; }
    
    .search-box { position: relative; }
    .search-box input { padding: 8px 14px 8px 36px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 0.875rem; font-family: 'Inter', sans-serif; outline: none; width: 280px; background: #fff; transition: border-color 0.2s; }
    .search-box input:focus { border-color: #111; }
</style>
@endpush

@section('content')

{{-- ===== STAT CARDS ===== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-7">
    {{-- Total Revenue --}}
    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-5 flex items-start gap-4">
        <div class="w-11 h-11 rounded bg-green-50 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
        <div>
            <p class="text-2xl font-extrabold text-gray-900 leading-none">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1.5 font-medium">Total Revenue</p>
        </div>
    </div>

    {{-- Completed Payments --}}
    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-5 flex items-start gap-4">
        <div class="w-11 h-11 rounded bg-blue-50 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div>
            <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ $completedCount ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1.5 font-medium">Completed Payments</p>
        </div>
    </div>

    {{-- Pending Payments --}}
    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-5 flex items-start gap-4">
        <div class="w-11 h-11 rounded bg-yellow-50 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
        <div>
            <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ $pendingCount ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1.5 font-medium">Pending Payments</p>
        </div>
    </div>

    {{-- Failed/Refunded --}}
    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-5 flex items-start gap-4">
        <div class="w-11 h-11 rounded bg-red-50 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </div>
        <div>
            <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ $failedRefundedCount ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1.5 font-medium">Failed/Refunded</p>
        </div>
    </div>
</div>

{{-- ===== REVENUE TREND CHART ===== --}}
<div class="bg-white rounded-md border border-gray-100 shadow-sm p-6 mb-7">
    <h2 class="text-base font-bold text-gray-900 mb-4">Revenue Trend</h2>
    <div class="relative h-52">
        <canvas id="revenueTrendChart"></canvas>
    </div>
</div>

{{-- ===== FILTER ROW ===== --}}
<form method="GET" action="{{ route('admin.payments.index') }}" id="filterForm" class="bg-white rounded-md border border-gray-100 shadow-sm p-4 mb-5 flex flex-wrap items-center gap-3">
    
    {{-- Custom Dropdown: Status --}}
    <div class="relative custom-dropdown">
        <button type="button" onclick="toggleFilterMenu('statusMenu')" class="custom-dropdown-btn min-w-[140px]">
            <span id="label-status">
                @if(request('status') == 'completed') Completed
                @elseif(request('status') == 'pending') Pending
                @elseif(request('status') == 'failed') Failed
                @elseif(request('status') == 'refunded') Refunded
                @else Semua Status @endif
            </span>
            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
        </button>
        <div id="statusMenu" class="drop-menu absolute left-0 w-full mt-1 bg-white border border-gray-100 rounded-md shadow-lg hidden z-30 overflow-hidden">
            <ul class="text-sm text-gray-700">
                <li class="dropdown-item px-4 py-2.5 cursor-pointer font-medium" onclick="selectFilterItem('status', '', 'Semua Status')">Semua Status</li>
                <li class="dropdown-item px-4 py-2.5 cursor-pointer" onclick="selectFilterItem('status', 'completed', 'Completed')">Completed</li>
                <li class="dropdown-item px-4 py-2.5 cursor-pointer" onclick="selectFilterItem('status', 'pending', 'Pending')">Pending</li>
                <li class="dropdown-item px-4 py-2.5 cursor-pointer" onclick="selectFilterItem('status', 'failed', 'Failed')">Failed</li>
                <li class="dropdown-item px-4 py-2.5 cursor-pointer" onclick="selectFilterItem('status', 'refunded', 'Refunded')">Refunded</li>
            </ul>
        </div>
        <input type="hidden" name="status" id="input-status" value="{{ request('status') }}">
    </div>

    {{-- Custom Dropdown: Method --}}
    <div class="relative custom-dropdown">
        <button type="button" onclick="toggleFilterMenu('methodMenu')" class="custom-dropdown-btn min-w-[160px]">
            <span id="label-method">
                @if(request('method') == 'credit_card') Credit Card
                @elseif(request('method') == 'bank_transfer') Bank Transfer
                @elseif(request('method') == 'qris') QRIS
                @else Semua Metode @endif
            </span>
            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
        </button>
        <div id="methodMenu" class="drop-menu absolute left-0 w-full mt-1 bg-white border border-gray-100 rounded-md shadow-lg hidden z-30 overflow-hidden">
            <ul class="text-sm text-gray-700">
                <li class="dropdown-item px-4 py-2.5 cursor-pointer font-medium" onclick="selectFilterItem('method', '', 'Semua Metode')">Semua Metode</li>
                <li class="dropdown-item px-4 py-2.5 cursor-pointer" onclick="selectFilterItem('method', 'credit_card', 'Credit Card')">Credit Card</li>
                <li class="dropdown-item px-4 py-2.5 cursor-pointer" onclick="selectFilterItem('method', 'bank_transfer', 'Bank Transfer')">Bank Transfer</li>
                <li class="dropdown-item px-4 py-2.5 cursor-pointer" onclick="selectFilterItem('method', 'qris', 'QRIS')">QRIS</li>
            </ul>
        </div>
        <input type="hidden" name="method" id="input-method" value="{{ request('method') }}">
    </div>

    <div class="flex-1"></div>

    {{-- Search --}}
    <div class="search-box">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Payment ID atau Customer...">
        <button type="submit" class="hidden"></button>
    </div>

    {{-- Export --}}
    <a href="{{ route('admin.payments.export', request()->all()) }}" class="flex items-center gap-2 px-4 py-2 border-2 border-gray-900 text-gray-900 text-sm font-semibold rounded-md hover:bg-gray-900 hover:text-white transition-colors shadow-sm">
        <i class="fa-solid fa-download"></i> Export Excel
    </a>

    {{-- Reset Filter --}}
    @if(request()->anyFilled(['status','method','search']))
    <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-1.5 px-3 py-2 border border-gray-200 text-gray-500 text-sm rounded-md hover:bg-gray-50 transition-colors">
        <i class="fa-solid fa-rotate-left"></i> Reset
    </a>
    @endif
</form>

{{-- ===== PAYMENT TABLE ===== --}}
<div class="bg-white rounded-md border border-gray-100 shadow-sm overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-[#111111] text-white">
                    <th class="text-xs font-bold tracking-widest uppercase px-5 py-4">Payment ID</th>
                    <th class="text-xs font-bold tracking-widest uppercase px-5 py-4">Order ID</th>
                    <th class="text-xs font-bold tracking-widest uppercase px-5 py-4">Customer</th>
                    <th class="text-xs font-bold tracking-widest uppercase px-5 py-4">Amount</th>
                    <th class="text-xs font-bold tracking-widest uppercase px-5 py-4">Method</th>
                    <th class="text-xs font-bold tracking-widest uppercase px-5 py-4">Status</th>
                    <th class="text-xs font-bold tracking-widest uppercase px-5 py-4">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4 text-gray-800 font-mono text-xs font-bold">{{ $payment->payment_id }}</td>
                    <td class="px-5 py-4 text-gray-500 font-mono text-xs">{{ $payment->order->order_number ?? '-' }}</td>
                    <td class="px-5 py-4 text-gray-900 font-medium">{{ $payment->customer_name }}</td>
                    <td class="px-5 py-4 font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td class="px-5 py-4">
                        @php
                            $methodClass = match($payment->method) {
                                'credit_card'   => 'bg-purple-50 text-purple-600',
                                'bank_transfer' => 'bg-teal-50 text-teal-600',
                                'qris'          => 'bg-orange-50 text-orange-600',
                                default         => 'bg-gray-100 text-gray-600'
                            };
                            $methodLabel = str_replace('_', ' ', $payment->method);
                        @endphp
                        <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-semibold capitalize {{ $methodClass }}">{{ $methodLabel ?: '-' }}</span>
                    </td>
                    <td class="px-5 py-4">
                        @php
                            $statusClass = match($payment->status) {
                                'completed' => 'bg-green-50 text-green-600',
                                'pending'   => 'bg-yellow-50 text-yellow-600',
                                'failed'    => 'bg-red-50 text-red-600',
                                'refunded'  => 'bg-gray-100 text-gray-600',
                                default     => 'bg-gray-100 text-gray-600'
                            };
                        @endphp
                        <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-semibold capitalize {{ $statusClass }}">{{ $payment->status }}</span>
                    </td>
                    <td class="px-5 py-4 text-gray-500 text-xs">{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-50 mb-3">
                            <i class="fa-regular fa-credit-card text-xl text-gray-300"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-400">Tidak ada data pembayaran.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-gray-100">
        {{ $payments->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
// ==== Dropdown Filter Logic ====
function toggleFilterMenu(id) {
    document.querySelectorAll('.drop-menu').forEach(menu => {
        if(menu.id !== id) menu.classList.add('hidden');
    });
    document.getElementById(id).classList.toggle('hidden');
}

function selectFilterItem(type, value, labelHtml) {
    document.getElementById('input-' + type).value = value;
    document.getElementById('label-' + type).innerHTML = labelHtml;
    document.getElementById(type + 'Menu').classList.add('hidden');
    document.getElementById('filterForm').submit(); // Auto submit
}

document.addEventListener('click', function(event) {
    if (!event.target.closest('.custom-dropdown')) {
        document.querySelectorAll('.drop-menu').forEach(menu => menu.classList.add('hidden'));
    }
});

// ==== Chart.js Logic ====
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('revenueTrendChart').getContext('2d');
    const labels = @json($chartLabels ?? []);
    const data   = @json($chartData ?? []);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue',
                data: data,
                borderColor: '#111111',
                borderWidth: 2,
                pointBackgroundColor: '#111111',
                pointRadius: 4,
                fill: true,
                backgroundColor: 'rgba(17,17,17,0.04)',
                tension: 0.3,
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
                        font: { size: 11 },
                        callback: function(val) {
                            if (val >= 1000000) return (val/1000000).toFixed(0) + 'jt';
                            return val;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush