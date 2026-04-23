@extends('layouts.admin')

@section('title', 'Sales Reports & Analytics')
@section('page-title', 'Reports & Analytics')
@section('breadcrumb', 'Admin / Reports')

@push('styles')
<style>
    /* Custom Dropdown Filter */
    .dropdown-item:hover { background-color: #f9fafb; }
    .custom-dropdown-btn { appearance: none; background-color: white; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 14px; font-size: 0.875rem; color: #4b5563; display: flex; align-items: center; justify-content: space-between; gap: 10px; cursor: pointer; transition: border-color 0.2s; }
    .custom-dropdown-btn:focus { border-color: #111; outline: none; }
</style>
@endpush

@section('content')
{{-- ===== FILTER BAR ===== --}}
<div class="bg-white rounded-md border border-gray-100 shadow-sm px-5 py-4 mb-6">
    <form method="GET" action="{{ route('admin.reports.index') }}" id="reportFilterForm" class="flex flex-wrap items-end gap-3">

        {{-- Custom Dropdown: Periode --}}
        <div>
            <label class="block text-xs text-gray-400 mb-1 font-medium">Periode</label>
            <div class="relative custom-dropdown">
                <button type="button" onclick="toggleFilterMenu('periodMenu')" class="custom-dropdown-btn min-w-[155px]">
                    <div class="flex items-center gap-2">
                        <i class="fa-regular fa-calendar text-gray-400 text-xs"></i>
                        <span id="label-period">
                            @if(request('period') == 'today') Hari Ini
                            @elseif(request('period') == 'this_week') Minggu Ini
                            @elseif(request('period') == 'this_month' || !request('period')) Bulan Ini
                            @elseif(request('period') == 'last_month') Bulan Lalu
                            @elseif(request('period') == 'this_year') Tahun Ini
                            @elseif(request('period') == 'custom') Custom Range
                            @endif
                        </span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
                </button>
                <div id="periodMenu" class="drop-menu absolute left-0 w-full mt-1 bg-white border border-gray-100 rounded-md shadow-lg hidden z-30 overflow-hidden">
                    <ul class="text-sm text-gray-700">
                        <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('period', 'today', 'Hari Ini')">Hari Ini</li>
                        <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('period', 'this_week', 'Minggu Ini')">Minggu Ini</li>
                        <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('period', 'this_month', 'Bulan Ini')">Bulan Ini</li>
                        <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('period', 'last_month', 'Bulan Lalu')">Bulan Lalu</li>
                        <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('period', 'this_year', 'Tahun Ini')">Tahun Ini</li>
                        <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('period', 'custom', 'Custom Range')">Custom Range</li>
                    </ul>
                </div>
                <input type="hidden" name="period" id="input-period" value="{{ request('period', 'this_month') }}">
            </div>
        </div>

        {{-- Custom date range (shown conditionally via JS) --}}
        <div id="customDateRange" class="{{ request('period')=='custom' ? 'flex' : 'hidden' }} items-end gap-2">
            <div>
                <label class="block text-xs text-gray-400 mb-1 font-medium">Dari</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-3 py-1.5 border border-gray-200 rounded-md text-sm text-gray-700 bg-white focus:outline-none focus:border-gray-900 min-w-[140px]">
            </div>
            <div>
                <label class="block text-xs text-gray-400 mb-1 font-medium">Sampai</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-3 py-1.5 border border-gray-200 rounded-md text-sm text-gray-700 bg-white focus:outline-none focus:border-gray-900 min-w-[140px]">
            </div>
        </div>

        {{-- Custom Dropdown: Kategori --}}
        <div>
            <label class="block text-xs text-gray-400 mb-1 font-medium">Kategori</label>
            <div class="relative custom-dropdown">
                <button type="button" onclick="toggleFilterMenu('categoryMenu')" class="custom-dropdown-btn min-w-[155px]">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-filter text-gray-400 text-xs"></i>
                        <span id="label-category">
                            {{ request('category') ? ($categories->where('id', request('category'))->first()->name ?? 'Semua Kategori') : 'Semua Kategori' }}
                        </span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
                </button>
                <div id="categoryMenu" class="drop-menu absolute left-0 w-full mt-1 bg-white border border-gray-100 rounded-md shadow-lg hidden z-30 overflow-hidden">
                    <ul class="text-sm text-gray-700">
                        <li class="dropdown-item px-4 py-2 cursor-pointer font-bold" onclick="selectFilterItem('category', '', 'Semua Kategori')">Semua Kategori</li>
                        @foreach($categories ?? [] as $cat)
                        <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('category', '{{ $cat->id }}', '{{ $cat->name }}')">{{ $cat->name }}</li>
                        @endforeach
                    </ul>
                </div>
                <input type="hidden" name="category" id="input-category" value="{{ request('category') }}">
            </div>
        </div>

        <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-md hover:bg-black transition-colors shadow-sm">
            Terapkan
        </button>

        @if(request()->anyFilled(['period','date_from','date_to','category']))
        <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 border border-gray-200 text-gray-600 text-sm rounded-md hover:bg-gray-50 transition-colors flex items-center gap-1.5">
            <i class="fa-solid fa-rotate-left"></i> Reset
        </a>
        @endif

        <div class="flex-1"></div>

        <a href="{{ route('admin.reports.export.excel', request()->all()) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-md hover:bg-green-700 transition-colors shadow-sm">
            <i class="fa-solid fa-file-excel"></i> Excel
        </a>

        <a href="{{ route('admin.reports.export.pdf', request()->all()) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-md hover:bg-red-700 transition-colors shadow-sm">
            <i class="fa-solid fa-file-pdf"></i> PDF
        </a>
    </form>
</div>

{{-- ===== STAT CARDS ===== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-5">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded bg-green-50 flex items-center justify-center">
                <i class="fa-solid fa-dollar-sign text-green-500 text-lg"></i>
            </div>
            <span class="text-xs font-semibold {{ $revenueGrowth >= 0 ? 'text-green-500' : 'text-red-500' }} flex items-center gap-1">
                <i class="fa-solid {{ $revenueGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i> {{ number_format(abs($revenueGrowth), 1) }}%
            </span>
        </div>
        <p class="text-2xl font-extrabold text-gray-900 leading-none">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-500 mt-1.5 font-medium">Total Revenue</p>
    </div>

    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-5">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded bg-blue-50 flex items-center justify-center">
                <i class="fa-solid fa-cart-shopping text-blue-500 text-lg"></i>
            </div>
            <span class="text-xs font-semibold text-green-500 flex items-center gap-1">
                <i class="fa-solid fa-arrow-trend-up"></i> {{ number_format($ordersGrowth, 1) }}%
            </span>
        </div>
        <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ number_format($totalOrders) }}</p>
        <p class="text-xs text-gray-500 mt-1.5 font-medium">Total Orders</p>
    </div>

    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-5">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded bg-purple-50 flex items-center justify-center">
                <i class="fa-solid fa-users text-purple-500 text-lg"></i>
            </div>
            <span class="text-xs font-semibold text-green-500 flex items-center gap-1">
                <i class="fa-solid fa-arrow-trend-up"></i> {{ number_format($customersGrowth, 1) }}%
            </span>
        </div>
        <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ number_format($newCustomers) }}</p>
        <p class="text-xs text-gray-500 mt-1.5 font-medium">New Customers</p>
    </div>

    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-5">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded bg-orange-50 flex items-center justify-center">
                <i class="fa-solid fa-box-open text-orange-400 text-lg"></i>
            </div>
            <span class="text-xs font-semibold {{ $avgGrowth >= 0 ? 'text-green-500' : 'text-red-500' }} flex items-center gap-1">
                <i class="fa-solid {{ $avgGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i> {{ number_format(abs($avgGrowth), 1) }}%
            </span>
        </div>
        <p class="text-2xl font-extrabold text-gray-900 leading-none">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-500 mt-1.5 font-medium">Avg. Order Value</p>
    </div>
</div>

{{-- ===== CHARTS ROW ===== --}}
<div class="grid grid-cols-1 xl:grid-cols-5 gap-5 mb-6">
    <div class="xl:col-span-3 bg-white rounded-md border border-gray-100 shadow-sm p-6">
        <h2 class="text-base font-bold text-gray-800 mb-4">Sales Trend</h2>
        <div class="relative h-56">
            <canvas id="salesTrendChart"></canvas>
        </div>
    </div>
    <div class="xl:col-span-2 bg-white rounded-md border border-gray-100 shadow-sm p-6">
        <h2 class="text-base font-bold text-gray-800 mb-4">Sales by Category</h2>
        <div class="relative h-48">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

{{-- ===== MONTHLY ORDERS BAR CHART ===== --}}
<div class="bg-white rounded-md border border-gray-100 shadow-sm p-6 mb-6">
    <h2 class="text-base font-bold text-gray-800 mb-4">Daily/Monthly Orders</h2>
    <div class="relative h-56">
        <canvas id="monthlyOrdersChart"></canvas>
    </div>
</div>

{{-- ===== TOP SELLING PRODUCTS ===== --}}
<div class="bg-white rounded-md border border-gray-100 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-gray-100">
        <h2 class="text-base font-bold text-gray-800">Top Selling Products</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-xs font-semibold tracking-widest uppercase text-gray-500 px-6 py-3 w-24">Rank</th>
                    <th class="text-xs font-semibold tracking-widest uppercase text-gray-500 px-6 py-3">Product Name</th>
                    <th class="text-xs font-semibold tracking-widest uppercase text-gray-500 px-6 py-3">Units Sold</th>
                    <th class="text-xs font-semibold tracking-widest uppercase text-gray-500 px-6 py-3">Revenue</th>
                    <th class="text-xs font-semibold tracking-widest uppercase text-gray-500 px-6 py-3">Avg. Price</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($topProducts as $i => $product)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded bg-gray-900 text-white text-xs font-bold">
                            {{ $i + 1 }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $product->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ number_format($product->units_sold) }} units</td>
                    <td class="px-6 py-4 font-bold text-gray-900">Rp {{ number_format($product->revenue, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-gray-500">Rp {{ number_format($product->avg_price, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-10 text-gray-400 text-sm">Tidak ada data produk terjual di periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Logic Dropdown Filter
function toggleFilterMenu(id) {
    document.querySelectorAll('.drop-menu').forEach(m => { if(m.id !== id) m.classList.add('hidden'); });
    document.getElementById(id).classList.toggle('hidden');
}

function selectFilterItem(type, value, labelHtml) {
    document.getElementById('input-' + type).value = value;
    document.getElementById('label-' + type).innerHTML = labelHtml;
    document.getElementById(type + 'Menu').classList.add('hidden');
    
    // Khusus Periode, jika "Custom" tampilkan tanggal, jika bukan langsung submit
    if(type === 'period') {
        const box = document.getElementById('customDateRange');
        if (value === 'custom') {
            box.classList.remove('hidden'); box.classList.add('flex');
            return; 
        } else {
            box.classList.add('hidden'); box.classList.remove('flex');
        }
    }
    document.getElementById('reportFilterForm').submit();
}

document.addEventListener('click', e => {
    if (!e.target.closest('.custom-dropdown')) {
        document.querySelectorAll('.drop-menu').forEach(m => m.classList.add('hidden'));
    }
});

// Chart.js Data
document.addEventListener('DOMContentLoaded', function () {
    const trendLabels = @json($trendLabels);
    const trendData   = @json($trendData);

    new Chart(document.getElementById('salesTrendChart'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Sales (Rp)',
                data: trendData,
                borderColor: '#111827', borderWidth: 2, pointBackgroundColor: '#111827',
                pointRadius: 4, pointHoverRadius: 6, fill: true,
                backgroundColor: 'rgba(17,24,39,0.06)', tension: 0.3,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font:{size:10} } },
                y: { grid: { color:'#f3f4f6' }, ticks: { font:{size:10}, callback: v => v >= 1e6 ? (v/1e6).toFixed(0)+'jt' : v } }
            }
        }
    });

    const catLabels = @json($categoryLabels);
    const catData   = @json($categoryData);

    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: catLabels.length ? catLabels : ['Belum Ada Data'],
            datasets: [{
                data: catData.length ? catData : [100],
                backgroundColor: catData.length ? ['#111827','#374151','#6b7280','#9ca3af','#d1d5db'] : ['#f3f4f6'],
                borderWidth: 2, borderColor: '#fff', hoverOffset: 4,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '65%',
            plugins: { legend: { position: 'right', labels: { usePointStyle:true, pointStyleWidth:8, font:{size:10} } } }
        }
    });

    const orderLabels = @json($orderLabels);
    const orderData   = @json($orderData);

    new Chart(document.getElementById('monthlyOrdersChart'), {
        type: 'bar',
        data: {
            labels: orderLabels,
            datasets: [{
                label: 'Orders',
                data: orderData,
                backgroundColor: '#111827',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } },
            scales: {
                x: { grid:{display:false}, ticks:{font:{size:10}} },
                y: { grid:{color:'#f3f4f6'}, ticks:{font:{size:10}}, beginAtZero:true }
            }
        }
    });
});
</script>
@endpush