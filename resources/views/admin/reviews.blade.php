@extends('layouts.admin')

@section('title', 'Review Management')
@section('page-title', 'Review Management')
@section('breadcrumb', 'Admin / Review Management')

@push('styles')
<style>
    .stat-card-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Table row hover */
    .review-row:hover {
        background: #fafafa;
    }

    /* Status badge */
    .badge-approved {
        background: #dcfce7;
        color: #16a34a;
    }

    .badge-pending {
        background: #fef9c3;
        color: #ca8a04;
    }

    .badge-rejected {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Action icon btn */
    .action-btn {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: none;
        background: transparent;
        transition: background 0.15s;
    }

    .action-btn.approve {
        color: #16a34a;
    }

    .action-btn.approve:hover {
        background: #dcfce7;
    }

    .action-btn.reject {
        color: #dc2626;
    }

    .action-btn.reject:hover {
        background: #fee2e2;
    }

    .action-btn.delete {
        color: #dc2626;
    }

    .action-btn.delete:hover {
        background: #fee2e2;
    }

    /* Search input */
    .search-box {
        position: relative;
    }

    .search-box input {
        padding: 9px 14px 9px 36px;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.8rem;
        font-family: 'Inter', sans-serif;
        outline: none;
        width: 240px;
        background: #fff;
        transition: border-color 0.2s;
    }

    .search-box input:focus {
        border-color: #111;
    }

    .search-box svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
    }

    /* Custom Dropdown Hover */
    .dropdown-item:hover {
        background-color: #f9fafb;
    }
</style>
@endpush

@section('content')
{{-- ===== STAT CARDS ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    {{-- Total Reviews --}}
    <div class="bg-white rounded-md border border-gray-100 p-5 shadow-sm">
        <div class="stat-card-icon bg-blue-50 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#3b82f6" stroke-width="1.8"
                width="20" height="20">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
            </svg>
        </div>
        <div class="text-2xl font-extrabold text-gray-900">{{ $total ?? 0 }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium">Total Reviews</div>
    </div>

    {{-- Approved --}}
    <div class="bg-white rounded-md border border-gray-100 p-5 shadow-sm">
        <div class="stat-card-icon bg-green-50 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#22c55e" stroke-width="1.8"
                width="20" height="20">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
            </svg>
        </div>
        <div class="text-2xl font-extrabold text-gray-900">{{ $approved ?? 0 }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium">Approved</div>
    </div>

    {{-- Pending --}}
    <div class="bg-white rounded-md border border-gray-100 p-5 shadow-sm">
        <div class="stat-card-icon bg-yellow-50 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#eab308" stroke-width="1.8"
                width="20" height="20">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
            </svg>
        </div>
        <div class="text-2xl font-extrabold text-gray-900">{{ $pending ?? 0 }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium">Pending</div>
    </div>

    {{-- Avg Rating --}}
    <div class="bg-white rounded-md border border-gray-100 p-5 shadow-sm">
        <div class="stat-card-icon bg-purple-50 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#a855f7" stroke-width="1.8"
                width="20" height="20">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
            </svg>
        </div>
        <div class="text-2xl font-extrabold text-gray-900">{{ $avgRating ?? '0.0' }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium">Avg Rating</div>
    </div>

</div>

{{-- ===== FILTER & SEARCH BAR ===== --}}
<div class="bg-white rounded-md border border-gray-100 p-4 mb-4 shadow-sm flex flex-wrap items-center gap-3">

    {{-- 1. Custom Dropdown: Filter Status --}}
    <div class="relative custom-dropdown">
        <button type="button" onclick="toggleDropdown('statusMenu')"
            class="flex items-center justify-between w-36 px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-gray-900 transition-colors">
            <span id="statusLabel" class="text-gray-600 font-medium">Semua Status</span>
            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
        </button>
        {{-- Menu --}}
        <div id="statusMenu"
            class="drop-menu absolute left-0 w-full mt-2 bg-white border border-gray-100 rounded-lg shadow-lg hidden z-30 overflow-hidden">
            <ul class="text-sm text-gray-700">
                <li class="dropdown-item px-4 py-2.5 cursor-pointer font-medium"
                    onclick="selectFilter('status', '', 'Semua Status')">Semua Status</li>
                <li class="dropdown-item px-4 py-2.5 cursor-pointer flex items-center gap-2"
                    onclick="selectFilter('status', 'approved', 'Approved')">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span> Approved
                </li>
                <li class="dropdown-item px-4 py-2.5 cursor-pointer flex items-center gap-2"
                    onclick="selectFilter('status', 'pending', 'Pending')">
                    <span class="w-2 h-2 rounded-full bg-yellow-400"></span> Pending
                </li>
                <li class="dropdown-item px-4 py-2.5 cursor-pointer flex items-center gap-2"
                    onclick="selectFilter('status', 'rejected', 'Rejected')">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span> Rejected
                </li>
            </ul>
        </div>
        {{-- Hidden Input untuk JS --}}
        <input type="hidden" id="filterStatus" value="">
    </div>

    {{-- 2. Custom Dropdown: Filter Rating --}}
    <div class="relative custom-dropdown">
        <button type="button" onclick="toggleDropdown('ratingMenu')"
            class="flex items-center justify-between w-40 px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-gray-900 transition-colors">
            <span id="ratingLabel" class="text-gray-600 font-medium"><i class="fa-regular fa-star mr-1"></i> Semua
                Rating</span>
            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
        </button>
        {{-- Menu --}}
        <div id="ratingMenu"
            class="drop-menu absolute left-0 w-48 mt-2 bg-white border border-gray-100 rounded-lg shadow-lg hidden z-30 overflow-hidden">
            <ul class="text-sm text-gray-700">
                <li class="dropdown-item px-4 py-2.5 cursor-pointer font-medium"
                    onclick="selectFilter('rating', '', '<i class=\'fa-regular fa-star mr-1\'></i> Semua Rating')">Semua
                    Rating</li>

                {{-- 5 Bintang --}}
                <li class="dropdown-item px-4 py-2.5 cursor-pointer flex items-center gap-1.5"
                    onclick="selectFilter('rating', '5', '5 Bintang')">
                    <span class="flex gap-0.5">
                        <i class="fa-solid fa-star text-[11px]" style="color: rgb(255, 212, 59);"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: rgb(255, 212, 59);"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: rgb(255, 212, 59);"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: rgb(255, 212, 59);"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: rgb(255, 212, 59);"></i>
                    </span>
                    <span class="ml-1 text-gray-500 text-xs">(5)</span>
                </li>

                {{-- 4 Bintang --}}
                <li class="dropdown-item px-4 py-2.5 cursor-pointer flex items-center gap-1.5"
                    onclick="selectFilter('rating', '4', '4 Bintang')">
                    <span class="flex gap-0.5">
                        <i class="fa-solid fa-star text-[11px]" style="color: rgb(255, 212, 59);"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: rgb(255, 212, 59);"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: rgb(255, 212, 59);"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: rgb(255, 212, 59);"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: #e5e7eb;"></i>
                    </span>
                    <span class="ml-1 text-gray-500 text-xs">(4)</span>
                </li>

                {{-- 3 Bintang --}}
                <li class="dropdown-item px-4 py-2.5 cursor-pointer flex items-center gap-1.5"
                    onclick="selectFilter('rating', '3', '3 Bintang')">
                    <span class="flex gap-0.5">
                        <i class="fa-solid fa-star text-[11px]" style="color: rgb(255, 212, 59);"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: rgb(255, 212, 59);"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: rgb(255, 212, 59);"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: #e5e7eb;"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: #e5e7eb;"></i>
                    </span>
                    <span class="ml-1 text-gray-500 text-xs">(3)</span>
                </li>

                {{-- 2 Bintang --}}
                <li class="dropdown-item px-4 py-2.5 cursor-pointer flex items-center gap-1.5"
                    onclick="selectFilter('rating', '2', '2 Bintang')">
                    <span class="flex gap-0.5">
                        <i class="fa-solid fa-star text-[11px]" style="color: rgb(255, 212, 59);"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: rgb(255, 212, 59);"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: #e5e7eb;"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: #e5e7eb;"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: #e5e7eb;"></i>
                    </span>
                    <span class="ml-1 text-gray-500 text-xs">(2)</span>
                </li>

                {{-- 1 Bintang --}}
                <li class="dropdown-item px-4 py-2.5 cursor-pointer flex items-center gap-1.5"
                    onclick="selectFilter('rating', '1', '1 Bintang')">
                    <span class="flex gap-0.5">
                        <i class="fa-solid fa-star text-[11px]" style="color: rgb(255, 212, 59);"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: #e5e7eb;"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: #e5e7eb;"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: #e5e7eb;"></i>
                        <i class="fa-solid fa-star text-[11px]" style="color: #e5e7eb;"></i>
                    </span>
                    <span class="ml-1 text-gray-500 text-xs">(1)</span>
                </li>
            </ul>
        </div>
        <input type="hidden" id="filterRating" value="">
    </div>

    {{-- Spacer agar search & export pindah ke kanan --}}
    <div class="flex-1"></div>

    {{-- Search --}}
    <div class="search-box">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
            width="15" height="15">
            <circle cx="11" cy="11" r="8" />
            <path d="m21 21-4.35-4.35" />
        </svg>
        <input type="text" id="searchInput" placeholder="Search reviews...">
    </div>

    {{-- Export Excel --}}
    <button
        class="flex items-center gap-2 px-4 py-2.5 border-2 border-gray-900 text-gray-900 text-sm font-semibold rounded-lg hover:bg-gray-900 hover:text-white transition-colors">
        <i class="fa-solid fa-download"></i>
        Export Excel
    </button>

</div>

{{-- ===== TABLE ===== --}}
<div class="bg-white rounded-md border border-gray-100 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="reviewTable">
            {{-- Head --}}
            <thead>
                <tr class="bg-[#111111] text-white">
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Product</th>
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">User</th>
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Rating</th>
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Comment</th>
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Date</th>
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Status</th>
                    <th class="text-left px-5 py-4 text-xs font-bold tracking-wider uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="reviewBody">

                @forelse($reviews ?? [] as $review)
                @php
                $status = $review->status ?? 'pending';
                $product = $review->product->name ?? 'Produk';
                $user = $review->user->name ?? 'User';
                $email = $review->user->email ?? '';
                $rating = floor($review->rating ?? 0);
                $comment = $review->comment ?? '';
                $date = $review->created_at ?? now();

                $badgeClass = match($status) {
                'approved' => 'badge-approved',
                'pending' => 'badge-pending',
                'rejected' => 'badge-rejected',
                default => 'badge-pending',
                };
                $badgeLabel = match($status) {
                'approved' => 'Approved',
                'pending' => 'Pending',
                'rejected' => 'Rejected',
                default => ucfirst($status),
                };
                @endphp
                <tr class="review-row transition-colors" id="row-{{ $review->id }}" data-status="{{ $status }}"
                    data-rating="{{ $rating }}">

                    {{-- Product --}}
                    <td class="px-5 py-4">
                        <div class="font-semibold text-gray-900 leading-snug">{{ $product }}</div>
                    </td>

                    {{-- User --}}
                    <td class="px-5 py-4">
                        <div class="font-medium text-gray-900">{{ $user }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ $email }}</div>
                    </td>

                    {{-- Rating --}}
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <div class="flex gap-0.5">
                                {{-- Ikon FontAwesome --}}
                                @for($s = 1; $s <= 5; $s++) @if($s <=$rating) <i class="fa-solid fa-star text-[12px]"
                                    style="color: rgb(255, 212, 59);"></i>
                                    @else
                                    <i class="fa-solid fa-star text-[12px]" style="color: #e5e7eb;"></i>
                                    @endif
                                    @endfor
                            </div>
                            <span class="text-sm font-bold text-gray-700">{{ number_format($review->rating ?? 0, 1)
                                }}</span>
                        </div>
                    </td>

                    {{-- Comment --}}
                    <td class="px-5 py-4 max-w-[220px]">
                        <p class="text-gray-600 text-sm leading-relaxed line-clamp-2">{{ $comment }}</p>
                    </td>

                    {{-- Date --}}
                    <td class="px-5 py-4">
                        <span class="text-sm text-gray-500 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}
                        </span>
                    </td>

                    {{-- Status --}}
                    <td class="px-5 py-4">
                        <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">
                            {{ $badgeLabel }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-1">
                            @if($status === 'pending' || $status === 'rejected')
                            <button class="action-btn approve" title="Approve"
                                onclick="changeStatus({{ $review->id }}, 'approved')">
                                <i class="fa-solid fa-check"></i>
                            </button>
                            @endif

                            @if($status === 'pending' || $status === 'approved')
                            <button class="action-btn reject" title="Reject"
                                onclick="changeStatus({{ $review->id }}, 'rejected')">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                            @endif

                          <button class="action-btn delete" title="Hapus" onclick="deleteReview({{ $review->id }}, '{{ addslashes($user) }}')">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-20 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                            <i class="fa-regular fa-comment-dots text-2xl text-gray-300"></i>
                        </div>
                        <p class="text-sm text-gray-500 font-medium">Tidak ada review saat ini</p>
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    {{-- Empty state search --}}
    <div id="emptyState" class="hidden py-20 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
            <i class="fa-solid fa-magnifying-glass text-2xl text-gray-300"></i>
        </div>
        <p class="text-sm text-gray-500 font-medium">Tidak ada review yang sesuai dengan filter.</p>
    </div>
</div>

{{-- MODAL: Konfirmasi Hapus Review --}}
<div id="modal-delete-review"
    class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60 backdrop-blur-sm"
    onclick="if(event.target===this) closeDeleteModal()">
    <div class="bg-white rounded-md w-full max-w-sm mx-4 p-6 text-center shadow-2xl">
        <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-triangle-exclamation text-red-500 text-2xl"></i>
        </div>
        <h3 class="text-base font-bold text-gray-900 mb-1">Hapus Review?</h3>
        <p class="text-xs text-gray-500 mb-1">Review dari <span id="delete-review-user"
                class="font-semibold text-gray-700"></span></p>
        <p class="text-xs text-red-500 mb-5">Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex gap-3">
            <button type="button" onclick="closeDeleteModal()"
                class="flex-1 py-2.5 rounded-md border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button type="button" id="btn-confirm-delete"
                class="flex-1 py-2.5 rounded-md bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition-colors">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ---- Handle Custom Dropdown Toggle ----
    function toggleDropdown(id) {
        // Tutup semua menu dropdown yang lain dulu
        document.querySelectorAll('.drop-menu').forEach(menu => {
            if(menu.id !== id) menu.classList.add('hidden');
        });
        // Buka/Tutup menu yang diklik
        document.getElementById(id).classList.toggle('hidden');
    }

    // ---- Handle Option Selection ----
    function selectFilter(type, value, labelHtml) {
        if (type === 'status') {
            document.getElementById('filterStatus').value = value;
            document.getElementById('statusLabel').innerHTML = labelHtml;
            document.getElementById('statusMenu').classList.add('hidden');
        } else if (type === 'rating') {
            document.getElementById('filterRating').value = value;
            document.getElementById('ratingLabel').innerHTML = labelHtml;
            document.getElementById('ratingMenu').classList.add('hidden');
        }
        applyFilters();
    }

    // Klik di luar dropdown untuk menutupnya
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.custom-dropdown')) {
            document.querySelectorAll('.drop-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });

    // ---- Live search & filter ----
    function applyFilters() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const status = document.getElementById('filterStatus').value;
        const rating = document.getElementById('filterRating').value;

        const rows = document.querySelectorAll('#reviewBody tr.review-row');
        let visible = 0;

        rows.forEach(row => {
            const rowText   = row.innerText.toLowerCase();
            const rowStatus = row.dataset.status;
            const rowRating = row.dataset.rating; // ini angka bulat (1-5)

            const matchSearch = !search || rowText.includes(search);
            const matchStatus = !status || rowStatus === status;
            const matchRating = !rating || rowRating === rating;

            const show = matchSearch && matchStatus && matchRating;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        // Tampilkan peringatan jika hasil filter kosong
        document.getElementById('emptyState').classList.toggle('hidden', visible > 0 || rows.length === 0);
    }

    // Pasang event listener untuk Search Bar
    document.getElementById('searchInput').addEventListener('input', applyFilters);

    // ---- Fetch API: Ubah Status (Placeholder) ----
    function changeStatus(id, newStatus) {
        // Karena kamu sudah ada backend-nya, logika ini akan nembak ke route patch admin.reviews.updateStatus
        fetch(`/admin/reviews/${id}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            window.location.reload();
        })
        .catch(error => console.error('Error:', error));
    }

   let deleteTargetId = null;

function deleteReview(id, userName) {
    deleteTargetId = id;
    document.getElementById('delete-review-user').textContent = userName || 'pengguna ini';
    const modal = document.getElementById('modal-delete-review');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    const modal = document.getElementById('modal-delete-review');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    deleteTargetId = null;
}

document.getElementById('btn-confirm-delete').addEventListener('click', function () {
    if (!deleteTargetId) return;

    fetch(`/admin/reviews/${deleteTargetId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(() => window.location.reload())
    .catch(error => console.error('Error:', error));
});
</script>
@endpush