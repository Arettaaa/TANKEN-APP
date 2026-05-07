@extends('layouts.admin')

@section('title', 'Orders Management')
@section('page-title', 'Orders Management')
@section('breadcrumb', 'Admin / Orders Management')

@push('styles')
<style>
    /* Stat icon */
    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-bottom: 16px;
    }

    /* Custom Dropdown Filter */
    .dropdown-item:hover {
        background-color: #f9fafb;
    }

    .custom-dropdown-btn {
        appearance: none;
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 8px 14px;
        font-size: 0.875rem;
        color: #4b5563;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .custom-dropdown-btn:focus {
        border-color: #111;
        outline: none;
    }

    /* Search */
    .search-box {
        position: relative;
    }

    .search-box input {
        padding: 8px 14px 8px 36px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        outline: none;
        width: 260px;
        background: #fff;
        transition: border-color 0.2s;
    }

    .search-box input:focus {
        border-color: #111;
    }

    .search-box svg {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
    }

    /* Status pill */
    .status-pill {
        display: inline-block;
        width: 110px;
        height: 28px;
        border-radius: 4px;
    }

    .pill-pending {
        background: #fef9c3;
    }

    .pill-confirmed {
        background: #dbeafe;
    }

    .pill-processing {
        background: #ede9fe;
    }

    .pill-shipped {
        background: #e0f2fe;
    }

    .pill-delivered {
        background: #dcfce7;
    }

    .pill-cancelled {
        background: #fee2e2;
    }

    /* Payment badge */
    .pay-paid {
        background: #dcfce7;
        color: #16a34a;
    }

    .pay-pending {
        background: #fef9c3;
        color: #ca8a04;
    }

    .pay-failed {
        background: #fee2e2;
        color: #dc2626;
    }

    .pay-refund {
        background: #ede9fe;
        color: #7c3aed;
    }

    /* Table row */
    .order-row:hover {
        background: #fafafa;
        cursor: pointer;
    }

    /* Action btn */
    .action-btn {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: none;
        background: transparent;
        color: #6b7280;
        transition: background 0.15s, color 0.15s;
    }

    .action-btn:hover {
        background: #f3f4f6;
        color: #111;
    }
</style>
@endpush

@section('content')
{{-- ===== STAT CARDS ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-gray-100 rounded-lg p-5 shadow-sm">
        <div class="stat-icon bg-blue-50">
            <i class="fa-solid fa-box text-[#3b82f6] text-xl"></i>
        </div>
        <div class="text-2xl font-extrabold text-gray-900">{{ $totalOrders ?? 0 }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium">Total Orders</div>
    </div>
    <div class="bg-white border border-gray-100 rounded-lg p-5 shadow-sm">
        <div class="stat-icon bg-yellow-50">
            <i class="fa-solid fa-clipboard-check text-[#eab308] text-xl"></i>
        </div>
        <div class="text-2xl font-extrabold text-gray-900">{{ $confirmed ?? 0 }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium">Confirmed</div>
    </div>
    <div class="bg-white border border-gray-100 rounded-lg p-5 shadow-sm">
        <div class="stat-icon bg-purple-50">
            <i class="fa-solid fa-truck-fast text-[#a855f7] text-xl"></i>
        </div>
        <div class="text-2xl font-extrabold text-gray-900">{{ $shipped ?? 0 }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium">Shipped</div>
    </div>
    <div class="bg-white border border-gray-100 rounded-lg p-5 shadow-sm">
        <div class="stat-icon bg-green-50">
            <i class="fa-solid fa-check-double text-[#22c55e] text-xl"></i>
        </div>
        <div class="text-2xl font-extrabold text-gray-900">{{ $delivered ?? 0 }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium">Delivered</div>
    </div>
</div>

{{-- ===== FILTER BAR ===== --}}
<div class="bg-white border border-gray-100 rounded-lg p-4 mb-4 shadow-sm">
    <div class="flex flex-wrap items-center gap-3">

        {{-- Custom Dropdown: Status --}}
        <div class="relative custom-dropdown">
            <button type="button" onclick="toggleFilterMenu('statusMenu')" class="custom-dropdown-btn min-w-[140px]">
                <span id="label-status">Semua Status</span>
                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
            </button>
            <div id="statusMenu"
                class="drop-menu absolute left-0 w-full mt-1 bg-white border border-gray-100 rounded-md shadow-lg hidden z-30 overflow-hidden">
                <ul class="text-sm text-gray-700">
                    <li class="dropdown-item px-4 py-2.5 cursor-pointer font-medium"
                        onclick="selectFilterItem('status', '', 'Semua Status')">Semua Status</li>
                    <li class="dropdown-item px-4 py-2.5 cursor-pointer flex items-center gap-2"
                        onclick="selectFilterItem('status', 'pending', 'Pending')"><span
                            class="w-2 h-2 rounded-full bg-yellow-300"></span> Pending</li>
                    <li class="dropdown-item px-4 py-2.5 cursor-pointer flex items-center gap-2"
                        onclick="selectFilterItem('status', 'processing', 'Processing')"><span
                            class="w-2 h-2 rounded-full bg-purple-300"></span> Processing</li>
                    <li class="dropdown-item px-4 py-2.5 cursor-pointer flex items-center gap-2"
                        onclick="selectFilterItem('status', 'shipped', 'Shipped')"><span
                            class="w-2 h-2 rounded-full bg-blue-100"></span> Shipped</li>
                    <li class="dropdown-item px-4 py-2.5 cursor-pointer flex items-center gap-2"
                        onclick="selectFilterItem('status', 'delivered', 'Delivered')"><span
                            class="w-2 h-2 rounded-full bg-green-400"></span> Delivered</li>
                    <li class="dropdown-item px-4 py-2.5 cursor-pointer flex items-center gap-2"
                        onclick="selectFilterItem('status', 'cancelled', 'Cancelled')"><span
                            class="w-2 h-2 rounded-full bg-red-400"></span> Cancelled</li>
                </ul>
            </div>
            <input type="hidden" id="input-status" value="">
        </div>

        {{-- Custom Dropdown: Payment --}}
        <div class="relative custom-dropdown">
            <button type="button" onclick="toggleFilterMenu('paymentMenu')" class="custom-dropdown-btn min-w-[150px]">
                <span id="label-payment">Semua Bayar</span>
                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
            </button>
            <div id="paymentMenu"
                class="drop-menu absolute left-0 w-full mt-1 bg-white border border-gray-100 rounded-md shadow-lg hidden z-30 overflow-hidden">
                <ul class="text-sm text-gray-700">
                    <li class="dropdown-item px-4 py-2.5 cursor-pointer font-medium"
                        onclick="selectFilterItem('payment', '', 'Semua Bayar')">Semua Bayar</li>
                    <li class="dropdown-item px-4 py-2.5 cursor-pointer flex items-center gap-2"
                        onclick="selectFilterItem('payment', 'paid', 'Sudah Bayar')"><i
                            class="fa-solid fa-check text-green-500"></i> Sudah Bayar</li>
                    <li class="dropdown-item px-4 py-2.5 cursor-pointer flex items-center gap-2"
                        onclick="selectFilterItem('payment', 'unpaid', 'Belum Bayar')"><i
                            class="fa-regular fa-clock text-yellow-500"></i> Belum Bayar</li>
                    <li class="dropdown-item px-4 py-2.5 cursor-pointer flex items-center gap-2"
                        onclick="selectFilterItem('payment', 'refunded', 'Refunded')"><i
                            class="fa-solid fa-rotate-left text-purple-500"></i> Refunded</li>
                </ul>
            </div>
            <input type="hidden" id="input-payment" value="">
        </div>

        {{-- Custom Dropdown: Tanggal --}}
        <div class="relative custom-dropdown">
            <button type="button" onclick="toggleFilterMenu('dateMenu')" class="custom-dropdown-btn min-w-[150px]">
                <span id="label-date">Semua Waktu</span>
                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
            </button>
            <div id="dateMenu"
                class="drop-menu absolute left-0 w-full mt-1 bg-white border border-gray-100 rounded-md shadow-lg hidden z-30 overflow-hidden">
                <ul class="text-sm text-gray-700">
                    <li class="dropdown-item px-4 py-2.5 cursor-pointer font-medium"
                        onclick="selectFilterItem('date', '', 'Semua Waktu')">Semua Waktu</li>
                    <li class="dropdown-item px-4 py-2.5 cursor-pointer"
                        onclick="selectFilterItem('date', 'today', 'Hari Ini')">Hari Ini</li>
                    <li class="dropdown-item px-4 py-2.5 cursor-pointer"
                        onclick="selectFilterItem('date', 'week', '7 Hari Terakhir')">7 Hari Terakhir</li>
                    <li class="dropdown-item px-4 py-2.5 cursor-pointer"
                        onclick="selectFilterItem('date', 'month', 'Bulan Ini')">Bulan Ini</li>
                </ul>
            </div>
            <input type="hidden" id="input-date" value="">
        </div>

        <div class="flex-1"></div>

        {{-- Search (Cukup filter via Javascript) --}}
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" id="searchInput" placeholder="Cari nama atau No. Order...">
        </div>

        {{-- Tombol Export (Direct Download URL) --}}
        <a href="{{ route('admin.orders.export') }}" target="_blank"
            class="flex items-center gap-2 px-4 py-2 border border-gray-900 text-gray-900 text-sm font-semibold rounded-md hover:bg-gray-900 hover:text-white transition-colors shadow-sm">
            <i class="fa-solid fa-download"></i> Export Excel
        </a>
    </div>
</div>

{{-- ===== TABLE ===== --}}
<div class="bg-white border border-gray-100 rounded-lg overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="orderTable">
            <thead>
                <tr class="bg-[#111111] text-white">
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Order ID</th>
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Customer</th>
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Date</th>
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Total</th>
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Status</th>
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Payment</th>
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="orderBody">

                @forelse($orders as $order)
                @php
                $oid = $order->order_number;
                $customer = $order->customer_name;
                $email = $order->customer_email;
                $date = $order->created_at->format('Y-m-d H:i');
                $total = $order->total;
                $status = $order->status;
                $payment = $order->payment_status;
                $items = $order->items->sum('quantity');
                $shipping = $order->courier ?? '-';
                $dbId = $order->id;

                $pillClass = match($status) {
                'pending' => 'pill-pending',
                'confirmed' => 'pill-confirmed',
                'processing' => 'pill-processing',
                'shipped' => 'pill-shipped',
                'delivered' => 'pill-delivered',
                'cancelled' => 'pill-cancelled',
                'refunded' => 'pill-cancelled',
                default => 'pill-pending',
                };

                $payClass = match($payment) {
                'paid' => 'pay-paid',
                'unpaid' => 'pay-pending',
                'refunded'=> 'pay-refund',
                default => 'pay-pending',
                };
                @endphp

                <tr class="order-row transition-colors" data-status="{{ $status }}" data-payment="{{ $payment }}"
                    data-date="{{ $order->created_at->format('Y-m-d') }}"
                    data-search="{{ strtolower($oid . ' ' . $customer . ' ' . $email) }}">

                    <td class="px-5 py-4">
                        <span class="font-bold text-gray-900 font-mono text-xs tracking-wide">{{ $oid }}</span>
                        <div class="text-xs text-gray-400 mt-0.5">{{ $items }} item{{ $items > 1 ? 's' : '' }}</div>
                    </td>

                    <td class="px-5 py-4">
                        <div class="font-medium text-gray-900">{{ $customer }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ $email }}</div>
                    </td>

                    <td class="px-5 py-4">
                        <span class="text-gray-600 text-sm">{{ $date }}</span>
                        <div class="text-xs text-gray-400 mt-0.5 uppercase">{{ $shipping }}</div>
                    </td>

                    <td class="px-5 py-4">
                        <span class="font-bold text-gray-900 text-sm">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </td>

                    <td class="px-5 py-4">
                        <div class="relative inline-block">
                            <span class="status-pill {{ $pillClass }}"></span>
                            <span
                                class="absolute inset-0 flex items-center justify-center text-xs font-bold text-gray-700">
                                {{ ucfirst($status) }}
                            </span>
                        </div>
                    </td>

                    <td class="px-5 py-4">
                        <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-md {{ $payClass }}">
                           {{ $payment === 'waiting_confirmation' ? 'Waiting Confirmation' : ucfirst($payment) }}
                        </span>
                    </td>

                    <td class="px-5 py-4">
                        <div class="relative flex items-center gap-1">
                            {{-- Tombol View --}}
                            <a href="{{ route('admin.orders.show', $dbId) }}" class="action-btn" title="Lihat Detail">
                                <i class="fa-regular fa-eye text-[15px]"></i>
                            </a>


                            {{-- Dropdown Menu --}}
                            <div class="relative">
                                <button class="action-btn" onclick="toggleActionMenu(event, 'action-{{ $dbId }}')">
                                    <i class="fa-solid fa-ellipsis-vertical text-[15px]"></i>
                                </button>

                                <div id="action-{{ $dbId }}"
                                    class="action-menu hidden absolute right-0 mt-1 w-48 bg-white border border-gray-100 rounded-lg shadow-lg z-40 overflow-hidden py-1">

                                    {{-- Kalau masih nunggu konfirmasi → tampilkan Terima/Tolak SAJA --}}
                                    @if($payment === 'waiting_confirmation')
                                    <div class="px-4 py-2 text-xs text-gray-400 font-medium uppercase tracking-wider">
                                        Verifikasi Pembayaran
                                    </div>

                                    <button
                                        onclick="closeActionMenus(); openKonfirmasiModal('{{ $dbId }}', '{{ $oid }}')"
                                        class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-green-600 hover:bg-green-50 transition-colors">
                                        <i class="fa-solid fa-circle-check w-4"></i>
                                        Terima Pembayaran
                                    </button>

                                    <button onclick="closeActionMenus(); openTolakModal('{{ $dbId }}', '{{ $oid }}')"
                                        class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                        <i class="fa-solid fa-circle-xmark w-4"></i>
                                        Tolak Pembayaran
                                    </button>

                                    {{-- Kalau sudah confirmed/paid → tampilkan Update Status --}}
                                    @elseif($payment === 'paid' && !in_array($status,
                                    ['delivered','cancelled','refunded']))
                                    <button
                                        onclick="closeActionMenus(); openStatusModal('{{ $dbId }}', '{{ $status }}', '{{ $oid }}')"
                                        class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <i class="fa-solid fa-pen-to-square text-gray-400 w-4"></i>
                                        Update Status
                                    </button>

                                    {{-- Sudah selesai / dibatalkan → tidak ada aksi --}}
                                    @else
                                    @php
                                    $actionMsg = match(true) {
                                    in_array($status, ['delivered']) => ['icon' => 'fa-check-double', 'color' =>
                                    'text-green-500', 'bg' => 'bg-green-50', 'title' => 'Order Selesai', 'desc' =>
                                    'Sudah diterima customer'],
                                    in_array($status, ['cancelled','refunded'])=> ['icon' => 'fa-ban', 'color' =>
                                    'text-red-400', 'bg' => 'bg-red-50', 'title' => ucfirst($status), 'desc' => 'Tidak
                                    ada aksi tersedia'],
                                    $payment === 'unpaid' => ['icon' => 'fa-clock', 'color' => 'text-yellow-500','bg' =>
                                    'bg-yellow-50', 'title' => 'Menunggu Bayar', 'desc' => 'Customer belum membayar'],
                                    default => ['icon' => 'fa-circle-info', 'color' => 'text-gray-400', 'bg' =>
                                    'bg-gray-50', 'title' => 'Tidak Ada Aksi', 'desc' => 'Tidak ada aksi tersedia'],
                                    };
                                    @endphp
                                    <div class="px-4 py-3 flex items-center gap-2.5">
                                        <div
                                            class="w-7 h-7 rounded-full {{ $actionMsg['bg'] }} flex items-center justify-center flex-shrink-0">
                                            <i
                                                class="fa-solid {{ $actionMsg['icon'] }} {{ $actionMsg['color'] }} text-[10px]"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-gray-700">{{ $actionMsg['title'] }}</div>
                                            <div class="text-[10px] text-gray-400 mt-0.5">{{ $actionMsg['desc'] }}</div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="py-16 text-center">
                            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-50 mb-3">
                                <i class="fa-solid fa-box-open text-xl text-gray-300"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-400">Tidak ada order yang ditemukan</p>
                        </div>
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="p-4 border-t border-gray-100">
        {{ $orders->links() }}
    </div>
</div>

{{-- ===== MODAL UPDATE STATUS ===== --}}
<div id="statusModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-6 transform scale-95 opacity-0 transition-all duration-200"
        id="modalContent">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-bold text-gray-900 text-base">Update Status Order</h3>
                <p class="text-xs text-gray-400 mt-0.5 font-mono" id="modalOrderId">—</p>
            </div>
            <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-700 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <label class="block text-sm font-semibold text-gray-700 mb-2">Status Baru</label>
        <select id="newStatusSelect" onchange="handleStatusChange(this.value)"
            class="w-full border border-gray-200 rounded-md px-3 py-2.5 text-sm focus:outline-none focus:border-gray-900 transition-colors mb-4 bg-white">
            <option value="processing">Processing — Sedang dikemas</option>
            <option value="shipped">Shipped — Sudah dikirim</option>
            <option value="delivered">Delivered — Sudah diterima</option>
            <option value="cancelled">Cancelled — Dibatalkan</option>
        </select>

        {{-- Input No Resi — hanya muncul kalau pilih Shipped --}}
        <div id="resiSection" class="hidden mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                Nomor Resi <span class="text-red-400">*</span>
            </label>
            <input type="text" id="trackingNumber" placeholder="Contoh: JNE1234567890"
                class="w-full border border-gray-200 rounded-md px-3 py-2.5 text-sm focus:outline-none focus:border-gray-900 transition-colors uppercase tracking-wider">
            <p class="text-xs text-gray-400 mt-1">Nomor resi dari kurir pengiriman.</p>
        </div>

        <div class="flex gap-3">
            <button onclick="closeStatusModal()"
                class="flex-1 py-2.5 border border-gray-200 rounded-md text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors shadow-sm">
                Batal
            </button>
            <button onclick="submitStatus()" id="submitStatusBtn"
                class="flex-1 py-2.5 bg-gray-900 rounded-md text-sm font-semibold text-white hover:bg-black transition-colors shadow-sm">
                Simpan
            </button>
        </div>
    </div>
</div>

{{-- ===== MODAL KONFIRMASI PEMBAYARAN ===== --}}
<div id="konfirmasiModal"
    class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-6 transform scale-95 opacity-0 transition-all duration-200"
        id="konfirmasiModalContent">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-check text-green-600"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 text-base">Konfirmasi Pembayaran</h3>
                <p class="text-xs text-gray-400 font-mono mt-0.5" id="konfirmasiOrderId">—</p>
            </div>
        </div>

        <p class="text-sm text-gray-600 mb-5">
            Apakah kamu yakin ingin <span class="font-semibold text-green-600">menerima</span> pembayaran ini?
            Status order akan otomatis berubah menjadi <span class="font-semibold">Confirmed</span>.
        </p>

        <div class="flex gap-3">
            <button onclick="closeKonfirmasiModal()"
                class="flex-1 py-2.5 border border-gray-200 rounded-md text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button onclick="submitKonfirmasi()" id="submitKonfirmasiBtn"
                class="flex-1 py-2.5 bg-green-600 rounded-md text-sm font-semibold text-white hover:bg-green-700 transition-colors">
                Ya, Terima
            </button>
        </div>
    </div>
</div>

{{-- ===== MODAL TOLAK PEMBAYARAN ===== --}}
<div id="tolakModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-6 transform scale-95 opacity-0 transition-all duration-200"
        id="tolakModalContent">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-xmark text-red-500"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 text-base">Tolak Pembayaran</h3>
                <p class="text-xs text-gray-400 font-mono mt-0.5" id="tolakOrderId">—</p>
            </div>
        </div>

        <p class="text-sm text-gray-600 mb-3">
            Pembayaran akan <span class="font-semibold text-red-500">ditolak</span> dan status order berubah menjadi
            <span class="font-semibold">Cancelled</span>.
        </p>

        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Alasan Penolakan <span class="text-gray-400 font-normal">(opsional)</span>
        </label>
        <textarea id="tolakReason" rows="3" placeholder="Contoh: Bukti transfer tidak valid, nominal tidak sesuai..."
            class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-gray-900 resize-none transition-colors mb-5"></textarea>

        <div class="flex gap-3">
            <button onclick="closeTolakModal()"
                class="flex-1 py-2.5 border border-gray-200 rounded-md text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button onclick="submitTolak()" id="submitTolakBtn"
                class="flex-1 py-2.5 bg-red-500 rounded-md text-sm font-semibold text-white hover:bg-red-600 transition-colors">
                Ya, Tolak
            </button>
        </div>
    </div>
</div>

{{-- FLOATING TOAST NOTIFICATION --}}
<div id="toast-container"
    class="fixed top-5 left-1/2 -translate-x-1/2 z-[100] hidden transform transition-all duration-300 ease-out translate-y-[-20px] opacity-0">
    <div id="toast-box"
        class="flex items-center gap-3 bg-white border border-gray-100 text-gray-800 text-sm font-semibold px-5 py-3.5 rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
        <div id="toast-icon" class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center"></div>
        <span id="toast-msg"></span>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ==== 1. FLOATING TOAST SYSTEM ====
    function showToast(msg, type = 'success') {
        const container = document.getElementById('toast-container');
        const icon = document.getElementById('toast-icon');
        const msgEl = document.getElementById('toast-msg');

        msgEl.textContent = msg;

        if(type === 'success') {
            icon.className = 'flex-shrink-0 w-6 h-6 rounded-full bg-green-100 flex items-center justify-center';
            icon.innerHTML = '<i class="fa-solid fa-check text-green-600 text-[10px]"></i>';
        } else {
            icon.className = 'flex-shrink-0 w-6 h-6 rounded-full bg-red-100 flex items-center justify-center';
            icon.innerHTML = '<i class="fa-solid fa-xmark text-red-600 text-[10px]"></i>';
        }

        container.classList.remove('hidden');
        setTimeout(() => {
            container.classList.remove('translate-y-[-20px]', 'opacity-0');
            container.classList.add('translate-y-0', 'opacity-100');
        }, 10);

        setTimeout(() => {
            container.classList.remove('translate-y-0', 'opacity-100');
            container.classList.add('translate-y-[-20px]', 'opacity-0');
            setTimeout(() => { container.classList.add('hidden'); }, 300);
        }, 2500);
    }

    // ==== 2. CUSTOM DROPDOWN FILTER ====
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
        applyFilters(); // Trigger filter JS
    }

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.custom-dropdown')) {
            document.querySelectorAll('.drop-menu').forEach(menu => menu.classList.add('hidden'));
        }
    });

    // ==== 3. JAVASCRIPT FILTER ====
    function applyFilters() {
        const search  = document.getElementById('searchInput').value.toLowerCase();
        const status  = document.getElementById('input-status').value;
        const payment = document.getElementById('input-payment').value;
        const dateF   = document.getElementById('input-date').value;

        const today = new Date();
        today.setHours(0,0,0,0);

        const rows = document.querySelectorAll('#orderBody tr.order-row');
        let visible = 0;

        rows.forEach(row => {
            const matchSearch  = !search  || row.dataset.search.includes(search);
            const matchStatus  = !status  || row.dataset.status  === status;
            const matchPayment = !payment || row.dataset.payment === payment;

            let matchDate = true;
            if (dateF) {
                const rowDate = new Date(row.dataset.date);
                rowDate.setHours(0,0,0,0);
                const diff = (today - rowDate) / (1000 * 60 * 60 * 24);
                if (dateF === 'today')  matchDate = diff === 0;
                if (dateF === 'week')   matchDate = diff <= 7;
                if (dateF === 'month') {
                    matchDate = rowDate.getMonth() === today.getMonth() &&
                                rowDate.getFullYear() === today.getFullYear();
                }
            }

            const show = matchSearch && matchStatus && matchPayment && matchDate;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        document.getElementById('emptyState').classList.toggle('hidden', visible > 0);
    }

    document.getElementById('searchInput').addEventListener('input', applyFilters);

    // ==== KONFIRMASI & TOLAK PEMBAYARAN ====
let currentKonfirmasiId = null;
let currentTolakId      = null;

function openKonfirmasiModal(dbId, orderId) {
    currentKonfirmasiId = dbId;
    document.getElementById('konfirmasiOrderId').textContent = orderId;
    const modal   = document.getElementById('konfirmasiModal');
    const content = document.getElementById('konfirmasiModalContent');
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeKonfirmasiModal() {
    const content = document.getElementById('konfirmasiModalContent');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        document.getElementById('konfirmasiModal').classList.add('hidden');
        currentKonfirmasiId = null;
    }, 200);
}

function submitKonfirmasi() {
    if (!currentKonfirmasiId) return;
    const btn = document.getElementById('submitKonfirmasiBtn');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
    btn.disabled = true;

    fetch(`/admin/orders/${currentKonfirmasiId}/konfirmasi`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({}),
    })
    .then(r => r.ok ? r.json() : Promise.reject())
    .then(() => {
        closeKonfirmasiModal();
        showToast('Pembayaran berhasil dikonfirmasi ✓', 'success');
        setTimeout(() => window.location.reload(), 1200);
    })
    .catch(() => {
        showToast('Gagal mengkonfirmasi pembayaran', 'error');
        btn.innerHTML = 'Ya, Terima';
        btn.disabled = false;
    });
}

function openTolakModal(dbId, orderId) {
    currentTolakId = dbId;
    document.getElementById('tolakOrderId').textContent = orderId;
    document.getElementById('tolakReason').value = '';
    const modal   = document.getElementById('tolakModal');
    const content = document.getElementById('tolakModalContent');
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeTolakModal() {
    const content = document.getElementById('tolakModalContent');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        document.getElementById('tolakModal').classList.add('hidden');
        currentTolakId = null;
    }, 200);
}

function submitTolak() {
    if (!currentTolakId) return;
    const btn    = document.getElementById('submitTolakBtn');
    const reason = document.getElementById('tolakReason').value;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
    btn.disabled = true;

    fetch(`/admin/orders/${currentTolakId}/tolak`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ reason }),
    })
    .then(r => r.ok ? r.json() : Promise.reject())
    .then(() => {
        closeTolakModal();
        showToast('Pembayaran berhasil ditolak.', 'error');
        setTimeout(() => window.location.reload(), 1200);
    })
    .catch(() => {
        showToast('Gagal menolak pembayaran.', 'error');
        btn.innerHTML = 'Ya, Tolak';
        btn.disabled = false;
    });
}

// Klik backdrop untuk close modal
document.getElementById('konfirmasiModal').addEventListener('click', function(e) {
    if (e.target === this) closeKonfirmasiModal();
});
document.getElementById('tolakModal').addEventListener('click', function(e) {
    if (e.target === this) closeTolakModal();
});


    // ==== 4. MODAL LOGIC ====
    const statusModal = document.getElementById('statusModal');
    const modalContent = document.getElementById('modalContent');
    let currentOrderDbId = null;

  function handleStatusChange(value) {
    const resiSection = document.getElementById('resiSection');
    if (value === 'shipped') {
        resiSection.classList.remove('hidden');
    } else {
        resiSection.classList.add('hidden');
        document.getElementById('trackingNumber').value = '';
    }
}

// function openStatusModal(dbId, currentStatus, orderId) {
//     currentOrderDbId = dbId;
//     document.getElementById('modalOrderId').textContent = orderId;

//     // Set value dropdown, tapi skip 'confirmed' & 'pending' karena tidak ada di opsi
//     const allowedStatuses = ['processing', 'shipped', 'delivered', 'cancelled'];
//     const select = document.getElementById('newStatusSelect');
//     select.value = allowedStatuses.includes(currentStatus) ? currentStatus : 'processing';

//     // Reset resi section
//     document.getElementById('resiSection').classList.add('hidden');
//     document.getElementById('trackingNumber').value = '';
//     handleStatusChange(select.value);

//     statusModal.classList.remove('hidden');
//     setTimeout(() => {
//         modalContent.classList.remove('scale-95', 'opacity-0');
//         modalContent.classList.add('scale-100', 'opacity-100');
//     }, 10);
// }

function openStatusModal(dbId, currentStatus, orderId) {
    currentOrderDbId = dbId;
    document.getElementById('modalOrderId').textContent = orderId;

    const flow = {
        'pending'    : ['processing', 'cancelled'],
        'confirmed'  : ['processing', 'cancelled'],
        'processing' : ['shipped',    'cancelled'],
        'shipped'    : ['delivered',  'cancelled'],
    };

    const allowed = flow[currentStatus] ?? [];
    const labels = {
        'processing' : 'Processing — Sedang dikemas',
        'shipped'    : 'Shipped — Sudah dikirim',
        'delivered'  : 'Delivered — Sudah diterima',
        'cancelled'  : 'Cancelled — Dibatalkan',
    };

    const select = document.getElementById('newStatusSelect');
    select.innerHTML = ''; 

    allowed.forEach(val => {
        const opt = document.createElement('option');
        opt.value = val;
        opt.textContent = labels[val];
        select.appendChild(opt);
    });

    document.getElementById('resiSection').classList.add('hidden');
    document.getElementById('trackingNumber').value = '';
    handleStatusChange(select.value);

    statusModal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}


    function closeStatusModal() {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            statusModal.classList.add('hidden');
            currentOrderDbId = null;
        }, 200);
    }

    statusModal.addEventListener('click', function(e) {
        if (e.target === this) closeStatusModal();
    });

   function submitStatus() {
    if (!currentOrderDbId) return;
    const newStatus = document.getElementById('newStatusSelect').value;
    const tracking  = document.getElementById('trackingNumber').value.trim();

    // Validasi: kalau Shipped, resi wajib diisi
    if (newStatus === 'shipped' && !tracking) {
        document.getElementById('trackingNumber').focus();
        document.getElementById('trackingNumber').classList.add('border-red-400');
        return;
    }
    document.getElementById('trackingNumber').classList.remove('border-red-400');

    const btn = document.getElementById('submitStatusBtn');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
    btn.disabled = true;

    fetch(`/admin/orders/${currentOrderDbId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            status: newStatus,
            tracking_number: tracking || null,
        }),
    })
    .then(r => r.ok ? r.json() : Promise.reject())
    .then(() => {
        closeStatusModal();
        showToast('Status berhasil diperbarui', 'success');
        setTimeout(() => window.location.reload(), 1000);
    })
    .catch(() => {
        showToast('Gagal mengupdate status', 'error');
        btn.innerHTML = 'Simpan';
        btn.disabled = false;
    });
}

    // ==== ACTION DROPDOWN MENU ====
function toggleActionMenu(event, menuId) {
    event.stopPropagation();
    closeActionMenus(); // tutup semua yang lain dulu

    const menu = document.getElementById(menuId);
    if (menu) menu.classList.toggle('hidden');
}

function closeActionMenus() {
    document.querySelectorAll('.action-menu').forEach(m => m.classList.add('hidden'));
}

// Klik di luar → tutup semua dropdown
document.addEventListener('click', function () {
    closeActionMenus();
});
</script>
@endpush