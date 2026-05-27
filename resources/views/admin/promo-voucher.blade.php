@extends('layouts.admin')

@section('title', 'Promo & Voucher')
@section('page-title', 'Promo & Voucher')
@section('breadcrumb', 'Admin / Promo & Voucher')

@push('styles')
<style>
    .stat-icon { width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-bottom: 16px; }
    .dropdown-item:hover { background-color: #f9fafb; }
    .custom-dropdown-btn { appearance: none; background-color: white; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 14px; font-size: 0.875rem; color: #4b5563; display: flex; align-items: center; justify-content: space-between; gap: 10px; cursor: pointer; transition: border-color 0.2s; }
    .custom-dropdown-btn:focus { border-color: #111; outline: none; }
    .search-box { position: relative; }
    .search-box input { padding: 8px 14px 8px 36px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 0.875rem; outline: none; width: 240px; background: #fff; transition: border-color 0.2s; }
    .search-box input:focus { border-color: #111; }
    .badge-fixed      { background: #eff6ff; color: #2563eb; }
    .badge-percentage { background: #fefce8; color: #ea580c; }
    .badge-active     { background: #ecfdf5; color: #059669; }
    .badge-disabled   { background: #fee2e2; color: #dc2626; }
    .badge-expired    { background: #f3f4f6; color: #6b7280; }
    .badge-welcome    { background: #fdf4ff; color: #9333ea; }
    .quota-bar-wrap   { width: 90px; height: 5px; background: #e5e7eb; border-radius: 99px; overflow: hidden; margin-top: 4px; }
    .quota-bar-fill   { height: 100%; border-radius: 99px; }
    .action-btn { width: 30px; height: 30px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; border: none; background: transparent; transition: background 0.15s, color 0.15s; }
    .action-btn.edit:hover   { background: #f3f4f6; color: #111; }
    .action-btn.delete { color: #dc2626; }
    .action-btn.delete:hover { background: #fee2e2; }
    .toggle-input { position: absolute; opacity: 0; width: 0; height: 0; }
    .toggle-track { width: 34px; height: 18px; border-radius: 99px; background: #d1d5db; position: relative; transition: background 0.2s; flex-shrink: 0; display: block; }
    .toggle-track::after { content: ''; position: absolute; top: 2px; left: 2px; width: 14px; height: 14px; border-radius: 50%; background: #fff; transition: transform 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.2); }
    .toggle-input:checked + .toggle-track { background: #111 !important; }
    .toggle-input:checked + .toggle-track::after { transform: translateX(16px) !important; }
</style>
@endpush

@section('content')

{{-- STAT CARDS --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-gray-100 rounded-md p-5 shadow-sm">
        <div class="stat-icon bg-green-50 text-green-500"><i class="fa-solid fa-tags text-lg"></i></div>
        <div class="text-2xl font-extrabold text-gray-900">{{ number_format($activePromos) }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium uppercase tracking-wider">Active Promos</div>
    </div>
    <div class="bg-white border border-gray-100 rounded-md p-5 shadow-sm">
        <div class="stat-icon bg-gray-50 text-gray-400"><i class="fa-solid fa-calendar-xmark text-lg"></i></div>
        <div class="text-2xl font-extrabold text-gray-900">{{ number_format($expiredPromos) }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium uppercase tracking-wider">Expired Promos</div>
    </div>
    <div class="bg-white border border-gray-100 rounded-md p-5 shadow-sm">
        <div class="stat-icon bg-blue-50 text-blue-500"><i class="fa-solid fa-users text-lg"></i></div>
        <div class="text-2xl font-extrabold text-gray-900">{{ number_format($totalUsage) }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium uppercase tracking-wider">Total Usage</div>
    </div>
    <div class="bg-white border border-gray-100 rounded-md p-5 shadow-sm">
        <div class="stat-icon bg-purple-50 text-purple-500"><i class="fa-solid fa-hand-holding-dollar text-lg"></i></div>
        <div class="text-2xl font-extrabold text-gray-900">Rp {{ number_format($totalDiscount, 0, ',', '.') }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium uppercase tracking-wider">Est. Diskon Keluar</div>
    </div>
</div>

{{-- FILTER --}}
<form id="filterForm" method="GET" action="{{ route('admin.promos.index') }}" class="bg-white border border-gray-100 rounded-md p-4 mb-4 shadow-sm flex flex-wrap items-center gap-3">
    <div class="relative custom-dropdown">
        <button type="button" onclick="toggleFilterMenu('statusMenu')" class="custom-dropdown-btn min-w-[120px]">
            <span id="label-status">{{ request('status') ? ucfirst(request('status')) : 'Semua Status' }}</span>
            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
        </button>
        <div id="statusMenu" class="drop-menu absolute left-0 w-full mt-1 bg-white border border-gray-100 rounded-md shadow-lg hidden z-30 overflow-hidden">
            <ul class="text-sm text-gray-700">
                <li class="dropdown-item px-4 py-2 cursor-pointer font-bold" onclick="selectFilterItem('status', '', 'Semua Status')">Semua Status</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('status', 'active', 'Active')">Active</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('status', 'disabled', 'Disabled')">Disabled</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('status', 'expired', 'Expired')">Expired</li>
            </ul>
        </div>
        <input type="hidden" name="status" id="input-status" value="{{ request('status') }}">
    </div>

    <div class="relative custom-dropdown">
        <button type="button" onclick="toggleFilterMenu('typeMenu')" class="custom-dropdown-btn min-w-[130px]">
            <span id="label-type">
                @if(request('type') == 'fixed') Fixed Amount
                @elseif(request('type') == 'percentage') Persentase
                @else Tipe Diskon @endif
            </span>
            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
        </button>
        <div id="typeMenu" class="drop-menu absolute left-0 w-full mt-1 bg-white border border-gray-100 rounded-md shadow-lg hidden z-30 overflow-hidden text-nowrap">
            <ul class="text-sm text-gray-700">
                <li class="dropdown-item px-4 py-2 cursor-pointer font-bold" onclick="selectFilterItem('type', '', 'Semua Tipe')">Semua Tipe</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('type', 'fixed', 'Fixed Amount')">Fixed Amount</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('type', 'percentage', 'Persentase')">Persentase</li>
            </ul>
        </div>
        <input type="hidden" name="type" id="input-type" value="{{ request('type') }}">
    </div>

    <div class="flex-1"></div>

    <div class="search-box">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode promo...">
        <button type="submit" class="hidden"></button>
    </div>

    @if(request()->anyFilled(['status','type','search']))
    <a href="{{ route('admin.promos.index') }}" class="flex items-center gap-1.5 px-3 py-2 border border-gray-200 text-gray-500 text-sm rounded-md hover:bg-gray-50 transition-colors">
        <i class="fa-solid fa-rotate-left"></i>
    </a>
    @endif

    <a href="{{ route('admin.promos.export') }}" class="flex items-center gap-2 border border-gray-200 rounded-md px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
        <i class="fa-solid fa-download"></i> Export Excel
    </a>

    <button type="button" onclick="openAddModal()" class="flex items-center gap-2 bg-[#111111] text-white text-sm font-semibold px-4 py-2 rounded-md hover:bg-black transition-colors shadow-sm ml-1">
        <i class="fa-solid fa-plus text-xs"></i> Add Promo
    </button>
</form>

{{-- TABLE --}}
<div class="bg-white border border-gray-100 rounded-md overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-[#111111] text-white">
                    <th class="px-5 py-4 text-[10px] font-bold tracking-widest uppercase">Promo Code</th>
                    <th class="px-5 py-4 text-[10px] font-bold tracking-widest uppercase">Type</th>
                    <th class="px-5 py-4 text-[10px] font-bold tracking-widest uppercase">Value</th>
                    <th class="px-5 py-4 text-[10px] font-bold tracking-widest uppercase">Min. Purchase</th>
                    <th class="px-5 py-4 text-[10px] font-bold tracking-widest uppercase">Kuota</th>
                    <th class="px-5 py-4 text-[10px] font-bold tracking-widest uppercase">Expiry</th>
                    <th class="px-5 py-4 text-[10px] font-bold tracking-widest uppercase">Status</th>
                    <th class="px-5 py-4 text-[10px] font-bold tracking-widest uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($vouchers as $promo)
                @php
                    $isExpired = $promo->expires_at && now()->gt($promo->expires_at);
                    if (!$promo->is_active) {
                        $statusClass = 'badge-disabled'; $statusLabel = 'Disabled';
                    } elseif ($isExpired) {
                        $statusClass = 'badge-expired'; $statusLabel = 'Expired';
                    } else {
                        $statusClass = 'badge-active'; $statusLabel = 'Active';
                    }
                    $typeClass = $promo->type === 'fixed' ? 'badge-fixed' : 'badge-percentage';
                    $typeLabel = $promo->type === 'fixed' ? 'Fixed Amount' : 'Persentase';
                    $valStr    = $promo->type === 'fixed' ? 'Rp '.number_format($promo->value,0,',','.') : $promo->value.'%';

                    // Kuota: claimed = user_vouchers_count, total = quota
                    $claimed   = $promo->user_vouchers_count ?? 0;
                    $quotaTotal = $promo->quota;
                    $quotaPct  = $quotaTotal > 0 ? min(100, round(($claimed / $quotaTotal) * 100)) : 0;
                    $barColor  = $quotaPct >= 100 ? '#ef4444' : '#22c55e';
                @endphp

                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-900 font-mono tracking-wide text-sm">{{ $promo->code }}</span>
                            @if($promo->is_welcome ?? false)
                                <span class="inline-flex px-2 py-0.5 text-[9px] font-bold rounded badge-welcome">WELCOME</span>
                            @endif
                        </div>
                        <div class="text-[10px] text-gray-400 mt-0.5 truncate max-w-[140px]">{{ $promo->description ?: 'Tanpa deskripsi' }}</div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-md {{ $typeClass }}">{{ $typeLabel }}</span>
                    </td>
                    <td class="px-5 py-4 font-bold text-gray-900">{{ $valStr }}</td>
                    <td class="px-5 py-4 text-gray-600">Rp {{ number_format($promo->min_purchase,0,',','.') }}</td>
                    <td class="px-5 py-4">
                        @if($quotaTotal)
                            <div class="text-sm text-gray-700 font-medium">{{ number_format($claimed) }} / {{ number_format($quotaTotal) }}</div>
                            <div class="quota-bar-wrap"><div class="quota-bar-fill" style="width:{{ $quotaPct }}%; background:{{ $barColor }};"></div></div>
                            <div class="text-[10px] text-gray-400 mt-0.5">sisa {{ number_format($quotaTotal - $claimed) }}</div>
                        @else
                            <span class="text-sm text-gray-400">∞ Unlimited</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-600">
                        {{ $promo->expires_at ? $promo->expires_at->format('Y-m-d') : 'Tanpa Batas' }}
                    </td>
                   <td class="px-5 py-4">
                        <label style="position:relative;display:inline-flex;align-items:center;cursor:pointer;margin-bottom:4px;">
                            <input type="checkbox" class="toggle-input" {{ $promo->is_active ? 'checked' : '' }} onchange="toggleStatus({{ $promo->id }}, this)">
                            <span class="toggle-track"></span>
                        </label>
                        <br>
                        <span class="inline-flex px-2 py-0.5 text-[10px] font-bold rounded {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-1">
                            <button class="action-btn edit" title="Edit Promo"
                                onclick="openEditModal(
                                    {{ $promo->id }},
                                    '{{ $promo->code }}',
                                    '{{ $promo->type }}',
                                    {{ $promo->value }},
                                    {{ $promo->min_purchase }},
                                    '{{ $promo->quota ?? '' }}',
                                    '{{ $promo->expires_at ? $promo->expires_at->format('Y-m-d') : '' }}',
                                    {{ $promo->is_active ? 'true' : 'false' }},
                                    '{{ addslashes($promo->description) }}',
                                    {{ ($promo->is_welcome ?? false) ? 'true' : 'false' }}
                                )">
                                <i class="fa-regular fa-pen-to-square text-[15px]"></i>
                            </button>
                            <button class="action-btn delete" title="Hapus Promo" onclick="openDeleteModal({{ $promo->id }}, '{{ $promo->code }}')">
                                <i class="fa-regular fa-trash-can text-[15px]"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-16 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-50 mb-3">
                            <i class="fa-solid fa-ticket text-xl text-gray-300"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-400">Tidak ada promo yang ditemukan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">{{ $vouchers->links() }}</div>
</div>

{{-- MODAL ADD / EDIT --}}
<div id="promoModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-md w-full max-w-lg mx-4 p-7 shadow-2xl transform scale-95 transition-transform duration-300" style="max-height:90vh;overflow-y:auto;">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-bold text-gray-900 text-lg" id="modalTitle">Tambah Promo Baru</h3>
                <p class="text-xs text-gray-500 mt-0.5" id="modalSubtitle">Buat kode promo dan atur diskon</p>
            </div>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-900 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <input type="hidden" id="modalPromoId">

        <div class="space-y-4">
            {{-- Code --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Kode Promo <span class="text-red-500">*</span></label>
                <input type="text" id="mCode" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors" placeholder="WELCOME10" oninput="this.value=this.value.toUpperCase()" style="font-family:monospace;">
            </div>

            {{-- Type & Value --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Tipe Diskon <span class="text-red-500">*</span></label>
                    <select id="mType" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors bg-white" onchange="toggleValuePrefix()">
                        <option value="">Pilih Tipe</option>
                        <option value="fixed">Fixed (Rp)</option>
                        <option value="percentage">Persentase (%)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Nilai <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-bold" id="valuePrefix">Rp</span>
                        <input type="number" id="mValue" class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors" placeholder="0" min="1">
                    </div>
                </div>
            </div>

            {{-- Min & Kuota --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Min. Pembelian</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-bold">Rp</span>
                        <input type="number" id="mMinPurchase" class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors" placeholder="0" min="0">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Kuota</label>
                    <input type="number" id="mQuota" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors" placeholder="Kosong = Unlimited" min="1">
                    <p class="text-[10px] text-gray-400 mt-1">Jumlah pengguna yang bisa klaim</p>
                </div>
            </div>

            {{-- Expiry --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Kadaluarsa</label>
                <input type="date" id="mExpiry" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors">
            </div>

            {{-- Desc --}}
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Deskripsi</label>
                <textarea id="mDesc" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors" rows="2" placeholder="Catatan internal..."></textarea>
            </div>

           {{-- Welcome Voucher Toggle --}}
            <div class="flex items-start gap-3 p-4 bg-purple-50 rounded-lg border border-purple-100">
                <div class="flex-shrink-0 mt-0.5 cursor-pointer" onclick="toggleWelcome(isWelcome)" id="welcomeTrack"
                    style="width:34px;height:18px;border-radius:99px;background:#d1d5db;position:relative;transition:background 0.2s;flex-shrink:0;">
                    <span id="welcomeThumb" style="content:'';position:absolute;top:2px;left:2px;width:14px;height:14px;border-radius:50%;background:#fff;transition:transform 0.2s;box-shadow:0 1px 3px rgba(0,0,0,0.2);display:block;"></span>
                </div>
                <input type="checkbox" id="mIsWelcome" class="hidden">
                <div>
                    <p class="text-sm font-bold text-gray-800">Voucher Sambutan (Welcome)</p>
                    <p class="text-xs text-gray-500 mt-0.5">Jika diaktifkan, voucher ini otomatis diberikan ke pelanggan baru saat registrasi.</p>
                </div>
            </div>

            <input type="hidden" id="mIsActive" value="true">
        </div>

        <div class="flex gap-3 mt-7">
            <button onclick="closeModal()" class="flex-1 py-2.5 border border-gray-200 rounded-md text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
            <button onclick="submitModal()" id="modalSubmitBtn" class="flex-1 py-2.5 bg-[#111111] text-white rounded-md text-sm font-bold hover:bg-black transition-colors shadow-sm">Buat Promo</button>
        </div>
    </div>
</div>

{{-- MODAL DELETE --}}
<div id="deletePromoModal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-md w-full max-w-sm mx-4 p-6 text-center shadow-2xl transform scale-95 transition-transform duration-300">
        <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-triangle-exclamation text-red-500 text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Promo?</h3>
        <p class="text-base font-mono font-bold text-gray-800 mb-1" id="deletePromoCode">"KODE"</p>
        <p class="text-sm text-red-500 mb-6">Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex gap-3">
            <button type="button" onclick="closeDeleteModal()" class="flex-1 py-2.5 rounded-md border border-gray-300 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
            <button type="button" id="confirmDeleteBtn" class="flex-1 py-2.5 rounded-md bg-[#dc2626] text-white text-sm font-bold hover:bg-red-700 transition-colors shadow-sm">Ya, Hapus</button>
        </div>
    </div>
</div>

{{-- TOAST --}}
<div id="toast-container" class="fixed top-5 left-1/2 -translate-x-1/2 z-[100] hidden transform transition-all duration-300 ease-out translate-y-[-20px] opacity-0">
    <div class="flex items-center gap-3 bg-white border border-gray-100 text-gray-800 text-sm font-bold px-5 py-3.5 rounded-full shadow-lg">
        <div id="toast-icon" class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center"></div>
        <span id="toast-msg"></span>
    </div>
</div>

@endsection

@push('scripts')
<script>
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
        container.classList.remove('translate-y-0', 'opacity-100'); container.classList.add('translate-y-[-20px]', 'opacity-0');
        setTimeout(() => container.classList.add('hidden'), 300);
    }, 2500);
}

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
document.addEventListener('click', e => {
    if (!e.target.closest('.custom-dropdown')) document.querySelectorAll('.drop-menu').forEach(m => m.classList.add('hidden'));
});

function toggleStatus(id, checkbox) {
    fetch(`/admin/promos/${id}/toggle-status`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(res => res.json())
    .then(() => { showToast('Status promo diupdate!'); setTimeout(() => window.location.reload(), 800); })
    .catch(() => { showToast('Gagal merubah status', 'error'); checkbox.checked = !checkbox.checked; });
}

let editMode = false;
const modal    = document.getElementById('promoModal');
const modalBox = modal.children[0];

function toggleValuePrefix() {
    document.getElementById('valuePrefix').textContent = document.getElementById('mType').value === 'percentage' ? '%' : 'Rp';
}

function openModal() {
    modal.classList.remove('hidden'); modal.classList.add('flex');
    setTimeout(() => { modal.classList.remove('opacity-0'); modalBox.classList.remove('scale-95'); }, 10);
}
function closeModal() {
    modal.classList.add('opacity-0'); modalBox.classList.add('scale-95');
    setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
}

function openAddModal() {
    editMode = false;
    document.getElementById('modalTitle').textContent    = 'Tambah Promo Baru';
    document.getElementById('modalSubtitle').textContent = 'Buat kode promo dan atur diskon';
    document.getElementById('modalSubmitBtn').textContent = 'Buat Promo';
    document.getElementById('modalPromoId').value = '';
    document.getElementById('mCode').value        = '';
    document.getElementById('mType').value        = '';
    document.getElementById('mValue').value       = '';
    document.getElementById('mMinPurchase').value = '';
    document.getElementById('mQuota').value       = '';
    document.getElementById('mExpiry').value      = '';
    document.getElementById('mDesc').value        = '';
    document.getElementById('mIsActive').value    = 'true';
    document.getElementById('mIsWelcome').checked = false;
    toggleValuePrefix();
    openModal();
}

function openEditModal(id, code, type, value, minPur, quota, expiry, isActive, desc, isWelcome) {
    editMode = true;
    document.getElementById('modalTitle').textContent    = 'Edit Promo';
    document.getElementById('modalSubtitle').textContent = 'Perbarui pengaturan promo';
    document.getElementById('modalSubmitBtn').textContent = 'Simpan Perubahan';
    document.getElementById('modalPromoId').value = id;
    document.getElementById('mCode').value        = code;
    document.getElementById('mType').value        = type;
    document.getElementById('mValue').value       = value;
    document.getElementById('mMinPurchase').value = minPur;
    document.getElementById('mQuota').value       = quota;
    document.getElementById('mExpiry').value      = expiry;
    document.getElementById('mDesc').value        = desc;
    document.getElementById('mIsActive').value    = isActive ? 'true' : 'false';
    document.getElementById('mIsWelcome').checked = isWelcome;
    toggleValuePrefix();
    openModal();
}

function submitModal() {
    const id        = document.getElementById('modalPromoId').value;
    const code      = document.getElementById('mCode').value.toUpperCase().trim();
    const type      = document.getElementById('mType').value;
    const val       = document.getElementById('mValue').value;
    const minPur    = document.getElementById('mMinPurchase').value || 0;
    const quota     = document.getElementById('mQuota').value || null;
    const expiry    = document.getElementById('mExpiry').value;
    const desc      = document.getElementById('mDesc').value;
    const isAct     = document.getElementById('mIsActive').value === 'true';
    const isWelcome = document.getElementById('mIsWelcome').checked;

    if (!code || !type || !val) return showToast('Kode, Tipe, dan Nilai wajib diisi!', 'error');

    const btn = document.getElementById('modalSubmitBtn');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>'; btn.disabled = true;

    const url    = editMode ? `/admin/promos/${id}` : '/admin/promos';
    const method = editMode ? 'PUT' : 'POST';

    fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ code, type, value: val, min_purchase: minPur, quota, expires_at: expiry, description: desc, is_active: isAct, is_welcome: isWelcome }),
    })
    .then(r => r.ok ? r.json() : Promise.reject())
    .then(() => { closeModal(); window.location.reload(); })
    .catch(() => { showToast('Gagal menyimpan data.', 'error'); btn.innerHTML = 'Simpan'; btn.disabled = false; });
}

let promoToDelete = null;
const deleteModal    = document.getElementById('deletePromoModal');
const deleteModalBox = deleteModal.children[0];

function openDeleteModal(id, code) {
    promoToDelete = id;
    document.getElementById('deletePromoCode').textContent = `"${code}"`;
    deleteModal.classList.remove('hidden'); deleteModal.classList.add('flex');
    setTimeout(() => { deleteModal.classList.remove('opacity-0'); deleteModalBox.classList.remove('scale-95'); }, 10);
}
function closeDeleteModal() {
    deleteModal.classList.add('opacity-0'); deleteModalBox.classList.add('scale-95');
    setTimeout(() => { deleteModal.classList.add('hidden'); deleteModal.classList.remove('flex'); promoToDelete = null; }, 300);
}

document.getElementById('promoModal').addEventListener('click', function(e) { if (e.target === this) closeModal(); });
document.getElementById('deletePromoModal').addEventListener('click', function(e) { if (e.target === this) closeDeleteModal(); });

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!promoToDelete) return;
    this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>'; this.disabled = true;
    fetch(`/admin/promos/${promoToDelete}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(r => r.ok ? r.json() : Promise.reject())
    .then(() => { closeDeleteModal(); window.location.reload(); })
    .catch(() => { showToast('Gagal menghapus promo.', 'error'); this.innerHTML = 'Ya, Hapus'; this.disabled = false; });
});

function toggleWelcome(forceState = null) {
    const cb     = document.getElementById('mIsWelcome');
    const track  = document.getElementById('welcomeTrack');
    const thumb  = document.getElementById('welcomeThumb');

    cb.checked = forceState !== null ? forceState : !cb.checked;

    if (cb.checked) {
        track.style.background = '#111';
        thumb.style.transform  = 'translateX(16px)';
    } else {
        track.style.background = '#d1d5db';
        thumb.style.transform  = 'translateX(0)';
    }
}
</script>
@endpush