@extends('layouts.admin')

@section('title', 'Detail Order — ' . $order->order_number)
@section('page-title', 'Detail Order')
@section('breadcrumb', 'Admin / Orders Management / ' . $order->order_number)

@push('styles')
<style>
    .info-label {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        color: #9ca3af;
        margin-bottom: 3px;
    }

    .info-value {
        font-size: 0.875rem;
        color: #111827;
        font-weight: 500;
    }

    .section-card {
        background: #fff;
        border: 1px solid #f3f4f6;
        border-radius: 10px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }

    .section-title {
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #6b7280;
        padding: 14px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 99px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .pill-pending    { background:#fef9c3; color:#854d0e; }
    .pill-confirmed  { background:#dbeafe; color:#1d4ed8; }
    .pill-processing { background:#ede9fe; color:#6d28d9; }
    .pill-shipped    { background:#e0f2fe; color:#0369a1; }
    .pill-delivered  { background:#dcfce7; color:#15803d; }
    .pill-cancelled  { background:#fee2e2; color:#dc2626; }
    .pill-refunded   { background:#fee2e2; color:#dc2626; }

    .pay-paid     { background:#dcfce7; color:#16a34a; }
    .pay-pending  { background:#fef9c3; color:#ca8a04; }
    .pay-waiting  { background:#ffedd5; color:#c2410c; }
    .pay-failed   { background:#fee2e2; color:#dc2626; }
    .pay-refund   { background:#ede9fe; color:#7c3aed; }

    .timeline-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: 4px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        border-radius: 7px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.15s;
    }

    .btn-dark  { background:#111; color:#fff; }
    .btn-dark:hover { background:#000; }
    .btn-green { background:#16a34a; color:#fff; }
    .btn-green:hover { background:#15803d; }
    .btn-red   { background:#dc2626; color:#fff; }
    .btn-red:hover { background:#b91c1c; }
    .btn-outline { background:#fff; color:#374151; border:1px solid #e5e7eb; }
    .btn-outline:hover { background:#f9fafb; }

    .product-row:not(:last-child) {
        border-bottom: 1px solid #f9fafb;
    }

    .proof-img {
        width: 100%;
        max-height: 260px;
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid #f3f4f6;
        background: #fafafa;
        cursor: zoom-in;
    }
</style>
@endpush

@section('content')

@php
    $status  = $order->status;
    $payment = $order->payment_status;

    $pillClass = match($status) {
        'pending'    => 'pill-pending',
        'confirmed'  => 'pill-confirmed',
        'processing' => 'pill-processing',
        'shipped'    => 'pill-shipped',
        'delivered'  => 'pill-delivered',
        'cancelled'  => 'pill-cancelled',
        'refunded'   => 'pill-refunded',
        default      => 'pill-pending',
    };

    $payClass = match($payment) {
        'paid'                 => 'pay-paid',
        'unpaid'               => 'pay-pending',
        'waiting_confirmation' => 'pay-waiting',
        'refunded'             => 'pay-refund',
        'failed'               => 'pay-failed',
        default                => 'pay-pending',
    };

    $payLabel = match($payment) {
        'paid'                 => 'Paid',
        'unpaid'               => 'Unpaid',
        'waiting_confirmation' => 'Waiting Confirmation',
        'refunded'             => 'Refunded',
        'failed'               => 'Failed',
        default                => ucfirst($payment),
    };

    $timeline = [
        'pending'    => ['label' => 'Order Dibuat',         'icon' => 'fa-file-lines',    'color' => 'bg-yellow-400'],
        'confirmed'  => ['label' => 'Pembayaran Dikonfirmasi','icon' => 'fa-circle-check', 'color' => 'bg-blue-500'],
        'processing' => ['label' => 'Sedang Dikemas',       'icon' => 'fa-box',            'color' => 'bg-purple-500'],
        'shipped'    => ['label' => 'Dikirim',              'icon' => 'fa-truck-fast',     'color' => 'bg-sky-500'],
        'delivered'  => ['label' => 'Diterima Customer',    'icon' => 'fa-check-double',   'color' => 'bg-green-500'],
        'cancelled'  => ['label' => 'Dibatalkan',           'icon' => 'fa-ban',            'color' => 'bg-red-400'],
    ];

    $flow = ['pending','confirmed','processing','shipped','delivered'];
    $currentIdx = array_search($status, $flow);
@endphp

{{-- ===== HEADER BAR ===== --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">

    {{-- Kiri: Back + Info --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.orders.index') }}"
            class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-colors">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <div class="flex items-center gap-2.5">
                <span class="font-extrabold text-gray-900 text-lg font-mono tracking-wide">{{ $order->order_number }}</span>
                <span class="status-pill {{ $pillClass }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                    {{ ucfirst($status) }}
                </span>
                <span class="status-pill {{ $payClass }}">
                    {{ $payLabel }}
                </span>
            </div>
            <div class="text-xs text-gray-400 mt-0.5">
                Dibuat {{ $order->created_at->format('d M Y, H:i') }} WIB
            </div>
        </div>
    </div>

    {{-- Kanan: Action Buttons --}}
    <div class="flex items-center gap-2">
        @if($payment === 'waiting_confirmation')
            <button onclick="openKonfirmasiModal('{{ $order->id }}', '{{ $order->order_number }}')"
                class="action-btn btn-green">
                <i class="fa-solid fa-circle-check"></i> Terima Pembayaran
            </button>
            <button onclick="openTolakModal('{{ $order->id }}', '{{ $order->order_number }}')"
                class="action-btn btn-red">
                <i class="fa-solid fa-circle-xmark"></i> Tolak
            </button>

        @elseif($payment === 'paid' && !in_array($status, ['delivered','cancelled','refunded']))
            <button onclick="openStatusModal('{{ $order->id }}', '{{ $status }}', '{{ $order->order_number }}')"
                class="action-btn btn-dark">
                <i class="fa-solid fa-pen-to-square"></i> Update Status
            </button>
        @endif

        <a href="{{ route('admin.orders.index') }}" class="action-btn btn-outline">
            <i class="fa-solid fa-list"></i> Semua Order
        </a>
    </div>
</div>

{{-- ===== GRID UTAMA ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- ==================== KOLOM KIRI (2/3) ==================== --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- PRODUK YANG DIPESAN --}}
        <div class="section-card">
            <div class="section-title">
                <i class="fa-solid fa-box text-gray-400"></i>
                Item Pesanan ({{ $order->items->sum('quantity') }} item)
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($order->items as $item)
                <div class="product-row flex items-center gap-4 px-5 py-4">
                    {{-- Gambar --}}
                    <div class="w-14 h-14 rounded-lg bg-gray-50 border border-gray-100 overflow-hidden flex-shrink-0">
                        @if($item->product && $item->product->main_image)
                            <img src="{{ asset('storage/' . $item->product->main_image) }}"
                                alt="{{ $item->product_name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <i class="fa-solid fa-image text-xl"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Info Produk --}}
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-gray-900 text-sm truncate">{{ $item->product_name }}</div>
                        <div class="flex items-center gap-2 mt-1">
                            @if($item->product_sku)
                                <span class="text-xs text-gray-400 font-mono">{{ $item->product_sku }}</span>
                                <span class="text-gray-200">•</span>
                            @endif
                            @if($item->size)
                                <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded font-medium">Size {{ $item->size }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Qty & Harga --}}
                    <div class="text-right flex-shrink-0">
                        <div class="text-xs text-gray-400 mb-0.5">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        <div class="font-bold text-gray-900 text-sm">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Ringkasan Harga --}}
            <div class="border-t border-gray-100 px-5 py-4 space-y-2.5">
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Subtotal</span>
                    <span class="font-medium text-gray-700">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($order->ppn)
                <div class="flex justify-between text-sm text-gray-500">
                    <span>PPN (11%)</span>
                    <span class="font-medium text-gray-700">Rp {{ number_format($order->ppn, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Ongkos Kirim ({{ strtoupper($order->courier ?? '-') }})</span>
                    <span class="font-medium text-gray-700">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                </div>
                @if($order->discount)
                <div class="flex justify-between text-sm text-green-600">
                    <span>Diskon</span>
                    <span class="font-medium">- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between pt-2.5 border-t border-gray-100">
                <span class="font-bold text-gray-900">Total</span>
                <span class="font-extrabold text-gray-900 text-base">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
            @if($order->unique_code > 0)
            <div class="flex justify-between text-sm text-gray-500">
                <span>Kode Unik</span>
                <span class="font-medium text-gray-700">+ {{ $order->unique_code }}</span>
            </div>
            <div class="flex justify-between pt-2 border-t border-dashed border-gray-100 mt-1">
                <span class="font-bold text-gray-900">Total Transfer</span>
                <span class="font-extrabold text-blue-600 text-base">Rp {{ number_format($order->total_payment, 0, ',', '.') }}</span>
            </div>
            @endif
            </div>
        </div>

        {{-- BUKTI PEMBAYARAN --}}
        @if($order->payment_proof)
        <div class="section-card">
            <div class="section-title">
                <i class="fa-solid fa-receipt text-gray-400"></i>
                Bukti Pembayaran
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <div class="info-label">Metode Bayar</div>
                        <div class="info-value capitalize">{{ str_replace('_', ' ', $order->payment_method ?? '-') }}</div>
                    </div>
                    <div>
                        <div class="info-label">Via / Bank</div>
                        <div class="info-value uppercase">{{ $order->payment_reference ?? '-' }}</div>
                    </div>
                    @if($order->paid_at)
                    <div>
                        <div class="info-label">Waktu Bayar</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($order->paid_at)->format('d M Y, H:i') }} WIB</div>
                    </div>
                    @endif
                </div>

                <img src="{{ asset('storage/' . $order->payment_proof) }}"
                     alt="Bukti Transfer"
                     class="proof-img"
                     onclick="window.open(this.src, '_blank')">
                <p class="text-xs text-gray-400 mt-2 text-center">Klik gambar untuk melihat ukuran penuh</p>
            </div>
        </div>
        @endif

        {{-- TIMELINE STATUS --}}
        <div class="section-card">
            <div class="section-title">
                <i class="fa-solid fa-clock-rotate-left text-gray-400"></i>
                Riwayat Status
            </div>
            <div class="px-5 py-4">
                @if($status !== 'cancelled')
                    <div class="flex items-start gap-4 relative">
                        {{-- Connector line --}}
                        <div class="absolute left-[4px] top-5 bottom-0 w-px bg-gray-100 z-0"></div>

                        <div class="flex flex-col gap-5 w-full">
                            @foreach($flow as $idx => $step)
                            @php
                                $isDone    = $currentIdx !== false && $idx <= $currentIdx;
                                $isCurrent = $idx === $currentIdx;
                                $info      = $timeline[$step] ?? ['label'=>$step,'icon'=>'fa-circle','color'=>'bg-gray-300'];
                            @endphp
                            <div class="flex items-start gap-3 relative z-10">
                                <div class="w-2.5 h-2.5 rounded-full mt-1 flex-shrink-0 {{ $isDone ? $info['color'] : 'bg-gray-200' }} ring-2 {{ $isCurrent ? 'ring-gray-900/20' : 'ring-transparent' }}"></div>
                                <div>
                                    <div class="text-sm font-{{ $isCurrent ? 'bold' : ($isDone ? 'semibold' : 'medium') }} {{ $isDone ? 'text-gray-800' : 'text-gray-400' }}">
                                        {{ $info['label'] }}
                                        @if($isCurrent)
                                            <span class="ml-2 text-xs px-2 py-0.5 bg-gray-900 text-white rounded-full font-medium">Sekarang</span>
                                        @endif
                                    </div>
                                    @if($step === 'pending' && $isDone)
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</div>
                                    @elseif($step === 'confirmed' && $isDone && $order->paid_at)
                                        <div class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($order->paid_at)->format('d M Y, H:i') }}</div>
                                    @elseif($step === 'shipped' && $isDone && $order->tracking_number)
                                        <div class="text-xs text-gray-400 mt-0.5">Resi: <span class="font-mono font-semibold text-gray-600">{{ $order->tracking_number }}</span></div>
                                    @elseif(!$isDone)
                                        <div class="text-xs text-gray-400 mt-0.5">Belum tercapai</div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3 py-2">
                        <div class="w-9 h-9 rounded-full bg-red-50 flex items-center justify-center">
                            <i class="fa-solid fa-ban text-red-400 text-sm"></i>
                        </div>
                        <div>
                            <div class="font-bold text-red-600 text-sm">Order Dibatalkan</div>
                            @if($order->notes)
                                <div class="text-xs text-gray-500 mt-0.5">{{ $order->notes }}</div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ==================== KOLOM KANAN (1/3) ==================== --}}
    <div class="space-y-5">

        {{-- INFO CUSTOMER --}}
        <div class="section-card">
            <div class="section-title">
                <i class="fa-solid fa-user text-gray-400"></i>
                Info Customer
            </div>
            <div class="px-5 py-4 space-y-4">
                <div>
                    <div class="info-label">Nama</div>
                    <div class="info-value">{{ $order->customer_name }}</div>
                </div>
                <div>
                    <div class="info-label">Email</div>
                    <div class="info-value text-blue-600 text-xs break-all">{{ $order->customer_email }}</div>
                </div>
                @if($order->customer_phone)
                <div>
                    <div class="info-label">Telepon</div>
                    <div class="info-value">{{ $order->customer_phone }}</div>
                </div>
                @endif
                @if($order->user)
                <div>
                    <div class="info-label">User ID</div>
                    <div class="info-value font-mono text-xs text-gray-500">#{{ $order->user->id }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- ALAMAT PENGIRIMAN --}}
        <div class="section-card">
            <div class="section-title">
                <i class="fa-solid fa-location-dot text-gray-400"></i>
                Alamat Pengiriman
            </div>
            <div class="px-5 py-4 space-y-3">
                <div>
                    <div class="info-label">Alamat</div>
                    <div class="info-value leading-relaxed">{{ $order->shipping_address }}</div>
                </div>
                @if($order->shipping_city)
                <div>
                    <div class="info-label">Kota / Wilayah</div>
                    <div class="info-value">{{ $order->shipping_city }}</div>
                </div>
                @endif
                @if($order->shipping_postal_code)
                <div>
                    <div class="info-label">Kode Pos</div>
                    <div class="info-value font-mono">{{ $order->shipping_postal_code }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- INFO PENGIRIMAN --}}
        <div class="section-card">
            <div class="section-title">
                <i class="fa-solid fa-truck text-gray-400"></i>
                Info Pengiriman
            </div>
            <div class="px-5 py-4 space-y-3">
                <div>
                    <div class="info-label">Kurir</div>
                    <div class="info-value uppercase">{{ $order->courier ?? '-' }}</div>
                </div>
               @if($order->tracking_number)
                <div>
                    <div class="info-label">Nomor Resi</div>
                    <div class="flex items-center gap-2">
                        <span class="info-value font-mono tracking-wider text-gray-800" id="resiText">{{ $order->tracking_number }}</span>
                        <button onclick="copyResi()" class="text-gray-400 hover:text-gray-700 transition-colors" title="Salin resi">
                            <i class="fa-regular fa-copy text-sm" id="copyIcon"></i>
                        </button>
                    </div>
                </div>

                {{-- Tambah ini --}}
                @php
                    $cn = strtolower($order->courier ?? '');
                    $trackUrl = str_contains($cn, 'jne') ? 'https://www.jne.co.id/id/tracking/trace'
                        : (str_contains($cn, 'j&t') || str_contains($cn, 'jnt') ? 'https://www.jet.co.id/track'
                        : (str_contains($cn, 'sicepat') ? 'https://www.sicepat.com/checkAwb'
                        : 'https://cekresi.com/?noresi=' . $order->tracking_number));
                @endphp
                <div class="mt-3">
                    <a href="{{ $trackUrl }}" target="_blank" rel="noopener"
                        title="Membuka website kurir di tab baru"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-xs font-bold tracking-widest uppercase rounded-md hover:bg-black transition-colors">
                        <i class="fa-solid fa-truck-fast"></i> Lacak Paket
                    </a>
                </div>
                @endif
                @if($order->estimated_arrival)
                <div>
                    <div class="info-label">Estimasi Tiba</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($order->estimated_arrival)->format('d M Y') }}</div>
                </div>
                @endif
                <div>
                    <div class="info-label">Ongkos Kirim</div>
                    <div class="info-value">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        {{-- CATATAN --}}
        @if($order->notes)
        <div class="section-card">
            <div class="section-title">
                <i class="fa-solid fa-note-sticky text-gray-400"></i>
                Catatan
            </div>
            <div class="px-5 py-4">
                <p class="text-sm text-gray-600 leading-relaxed">{{ $order->notes }}</p>
            </div>
        </div>
        @endif

    </div>
</div>


{{-- ===== MODAL UPDATE STATUS ===== --}}
<div id="statusModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-6 transform scale-95 opacity-0 transition-all duration-200" id="modalContent">
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
        </select>

        <div id="resiSection" class="hidden mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                Nomor Resi <span class="text-red-400">*</span>
            </label>
            <input type="text" id="trackingNumber" placeholder="Contoh: JNE1234567890"
                class="w-full border border-gray-200 rounded-md px-3 py-2.5 text-sm focus:outline-none focus:border-gray-900 transition-colors uppercase tracking-wider">
            <p class="text-xs text-gray-400 mt-1">Nomor resi dari kurir pengiriman.</p>
        </div>

        <div class="flex gap-3">
            <button onclick="closeStatusModal()" class="flex-1 py-2.5 border border-gray-200 rounded-md text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors shadow-sm">Batal</button>
            <button onclick="submitStatus()" id="submitStatusBtn" class="flex-1 py-2.5 bg-gray-900 rounded-md text-sm font-semibold text-white hover:bg-black transition-colors shadow-sm">Simpan</button>
        </div>
    </div>
</div>

{{-- ===== MODAL KONFIRMASI PEMBAYARAN ===== --}}
<div id="konfirmasiModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-6 transform scale-95 opacity-0 transition-all duration-200" id="konfirmasiModalContent">
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
            <button onclick="closeKonfirmasiModal()" class="flex-1 py-2.5 border border-gray-200 rounded-md text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
            <button onclick="submitKonfirmasi()" id="submitKonfirmasiBtn" class="flex-1 py-2.5 bg-green-600 rounded-md text-sm font-semibold text-white hover:bg-green-700 transition-colors">Ya, Terima</button>
        </div>
    </div>
</div>

{{-- ===== MODAL TOLAK PEMBAYARAN ===== --}}
<div id="tolakModal" class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-6 transform scale-95 opacity-0 transition-all duration-200" id="tolakModalContent">
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
            Pembayaran akan <span class="font-semibold text-red-500">ditolak</span> dan status order berubah menjadi <span class="font-semibold">Cancelled</span>.
        </p>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Alasan Penolakan <span class="text-gray-400 font-normal">(opsional)</span>
        </label>
        <textarea id="tolakReason" rows="3" placeholder="Contoh: Bukti transfer tidak valid..."
            class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-gray-900 resize-none transition-colors mb-5"></textarea>
        <div class="flex gap-3">
            <button onclick="closeTolakModal()" class="flex-1 py-2.5 border border-gray-200 rounded-md text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
            <button onclick="submitTolak()" id="submitTolakBtn" class="flex-1 py-2.5 bg-red-500 rounded-md text-sm font-semibold text-white hover:bg-red-600 transition-colors">Ya, Tolak</button>
        </div>
    </div>
</div>

{{-- TOAST --}}
<div id="toast-container" class="fixed top-5 left-1/2 -translate-x-1/2 z-[100] hidden transform transition-all duration-300 ease-out translate-y-[-20px] opacity-0">
    <div id="toast-box" class="flex items-center gap-3 bg-white border border-gray-100 text-gray-800 text-sm font-semibold px-5 py-3.5 rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
        <div id="toast-icon" class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center"></div>
        <span id="toast-msg"></span>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ===== TOAST =====
function showToast(msg, type = 'success') {
    const container = document.getElementById('toast-container');
    const icon = document.getElementById('toast-icon');
    document.getElementById('toast-msg').textContent = msg;
    icon.className = type === 'success'
        ? 'flex-shrink-0 w-6 h-6 rounded-full bg-green-100 flex items-center justify-center'
        : 'flex-shrink-0 w-6 h-6 rounded-full bg-red-100 flex items-center justify-center';
    icon.innerHTML = type === 'success'
        ? '<i class="fa-solid fa-check text-green-600 text-[10px]"></i>'
        : '<i class="fa-solid fa-xmark text-red-600 text-[10px]"></i>';

    container.classList.remove('hidden');
    setTimeout(() => { container.classList.remove('translate-y-[-20px]','opacity-0'); container.classList.add('translate-y-0','opacity-100'); }, 10);
    setTimeout(() => {
        container.classList.remove('translate-y-0','opacity-100');
        container.classList.add('translate-y-[-20px]','opacity-0');
        setTimeout(() => container.classList.add('hidden'), 300);
    }, 2500);
}

// ===== COPY RESI =====
function copyResi() {
    const text = document.getElementById('resiText')?.textContent?.trim();
    if (!text) return;
    navigator.clipboard.writeText(text).then(() => {
        const icon = document.getElementById('copyIcon');
        icon.className = 'fa-solid fa-check text-green-500 text-sm';
        setTimeout(() => { icon.className = 'fa-regular fa-copy text-sm'; }, 1500);
    });
}

// ===== STATUS MODAL =====
let currentOrderDbId = null;

function handleStatusChange(value) {
    document.getElementById('resiSection').classList.toggle('hidden', value !== 'shipped');
    if (value !== 'shipped') document.getElementById('trackingNumber').value = '';
}

function openStatusModal(dbId, currentStatus, orderId) {
    currentOrderDbId = dbId;
    document.getElementById('modalOrderId').textContent = orderId;

    const flow = {
        'pending'    : ['processing','cancelled'],
        'confirmed'  : ['processing','cancelled'],
        'processing' : ['shipped','cancelled'],
        'shipped'    : ['delivered','cancelled'],
    };
    const labels = {
        'processing' : 'Processing — Sedang dikemas',
        'shipped'    : 'Shipped — Sudah dikirim',
        'delivered'  : 'Delivered — Sudah diterima',
        'cancelled'  : 'Cancelled — Dibatalkan',
    };

    const allowed = flow[currentStatus] ?? [];
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

    const modal = document.getElementById('statusModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('modalContent').classList.remove('scale-95','opacity-0');
        document.getElementById('modalContent').classList.add('scale-100','opacity-100');
    }, 10);
}

function closeStatusModal() {
    const content = document.getElementById('modalContent');
    content.classList.remove('scale-100','opacity-100');
    content.classList.add('scale-95','opacity-0');
    setTimeout(() => { document.getElementById('statusModal').classList.add('hidden'); currentOrderDbId = null; }, 200);
}

function submitStatus() {
    if (!currentOrderDbId) return;
    const newStatus = document.getElementById('newStatusSelect').value;
    const tracking  = document.getElementById('trackingNumber').value.trim();

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
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ status: newStatus, tracking_number: tracking || null }),
    })
    .then(r => r.ok ? r.json() : Promise.reject())
    .then(() => { closeStatusModal(); showToast('Status berhasil diperbarui', 'success'); setTimeout(() => window.location.reload(), 1000); })
    .catch(() => { showToast('Gagal mengupdate status', 'error'); btn.innerHTML = 'Simpan'; btn.disabled = false; });
}

document.getElementById('statusModal').addEventListener('click', function(e) { if (e.target === this) closeStatusModal(); });

// ===== KONFIRMASI MODAL =====
let currentKonfirmasiId = null;

function openKonfirmasiModal(dbId, orderId) {
    currentKonfirmasiId = dbId;
    document.getElementById('konfirmasiOrderId').textContent = orderId;
    const modal = document.getElementById('konfirmasiModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('konfirmasiModalContent').classList.remove('scale-95','opacity-0');
        document.getElementById('konfirmasiModalContent').classList.add('scale-100','opacity-100');
    }, 10);
}

function closeKonfirmasiModal() {
    const content = document.getElementById('konfirmasiModalContent');
    content.classList.remove('scale-100','opacity-100');
    content.classList.add('scale-95','opacity-0');
    setTimeout(() => { document.getElementById('konfirmasiModal').classList.add('hidden'); currentKonfirmasiId = null; }, 200);
}

function submitKonfirmasi() {
    if (!currentKonfirmasiId) return;
    const btn = document.getElementById('submitKonfirmasiBtn');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
    btn.disabled = true;

    fetch(`/admin/orders/${currentKonfirmasiId}/konfirmasi`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({}),
    })
    .then(r => r.ok ? r.json() : Promise.reject())
    .then(() => { closeKonfirmasiModal(); showToast('Pembayaran berhasil dikonfirmasi ✓', 'success'); setTimeout(() => window.location.reload(), 1200); })
    .catch(() => { showToast('Gagal mengkonfirmasi pembayaran', 'error'); btn.innerHTML = 'Ya, Terima'; btn.disabled = false; });
}

document.getElementById('konfirmasiModal').addEventListener('click', function(e) { if (e.target === this) closeKonfirmasiModal(); });

// ===== TOLAK MODAL =====
let currentTolakId = null;

function openTolakModal(dbId, orderId) {
    currentTolakId = dbId;
    document.getElementById('tolakOrderId').textContent = orderId;
    document.getElementById('tolakReason').value = '';
    const modal = document.getElementById('tolakModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('tolakModalContent').classList.remove('scale-95','opacity-0');
        document.getElementById('tolakModalContent').classList.add('scale-100','opacity-100');
    }, 10);
}

function closeTolakModal() {
    const content = document.getElementById('tolakModalContent');
    content.classList.remove('scale-100','opacity-100');
    content.classList.add('scale-95','opacity-0');
    setTimeout(() => { document.getElementById('tolakModal').classList.add('hidden'); currentTolakId = null; }, 200);
}

function submitTolak() {
    if (!currentTolakId) return;
    const btn = document.getElementById('submitTolakBtn');
    const reason = document.getElementById('tolakReason').value;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
    btn.disabled = true;

    fetch(`/admin/orders/${currentTolakId}/tolak`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ reason }),
    })
    .then(r => r.ok ? r.json() : Promise.reject())
    .then(() => { closeTolakModal(); showToast('Pembayaran berhasil ditolak.', 'error'); setTimeout(() => window.location.reload(), 1200); })
    .catch(() => { showToast('Gagal menolak pembayaran.', 'error'); btn.innerHTML = 'Ya, Tolak'; btn.disabled = false; });
}

document.getElementById('tolakModal').addEventListener('click', function(e) { if (e.target === this) closeTolakModal(); });
</script>
@endpush