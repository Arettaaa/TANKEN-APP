@extends('layouts.admin')

@section('title', 'User Management')
@section('page-title', 'User Management')
@section('breadcrumb', 'Admin / User Management')

@push('styles')
<style>
    /* Custom Dropdown Filter */
    .dropdown-item:hover { background-color: #f9fafb; }
    .custom-dropdown-btn { appearance: none; background-color: white; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 14px; font-size: 0.875rem; color: #4b5563; display: flex; align-items: center; justify-content: space-between; gap: 10px; cursor: pointer; transition: border-color 0.2s; }
    .custom-dropdown-btn:focus { border-color: #111; outline: none; }

    .search-box { position: relative; }
    .search-box input { padding: 8px 14px 8px 36px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 0.875rem; font-family: 'Inter', sans-serif; outline: none; width: 270px; background: #fff; transition: border-color 0.2s; }
    .search-box input:focus { border-color: #111; }

    /* Stat icon circle */
    .stat-icon { width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-bottom: 16px; }

    /* Avatar circle */
    .user-avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 700; flex-shrink: 0; color: #fff; letter-spacing: 0.03em; }

    /* Toggle switch (Direvisi agar tidak tabrakan) */
    .toggle-wrap { position: relative; display: block; width: 36px; height: 20px; cursor: pointer; flex-shrink: 0; }
    .toggle-input { display: none; }
    .toggle-track { position: absolute; inset: 0; border-radius: 99px; background: #d1d5db; transition: background 0.2s; }
    .toggle-input:checked + .toggle-track { background: #111; }
    .toggle-track::after { content: ''; position: absolute; top: 2px; left: 2px; width: 16px; height: 16px; border-radius: 50%; background: #fff; transition: transform 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.2); }
    .toggle-input:checked + .toggle-track::after { transform: translateX(16px); }

    /* Role badge */
    .badge-customer { background: #f3f4f6; color: #4b5563; }
    .badge-admin    { background: #fefce8; color: #ea580c; }
    .badge-gudang   { background: #f0fdf4; color: #16a34a; }

    /* Action btn */
    .action-btn { width: 30px; height: 30px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; border: none; background: transparent; color: #6b7280; transition: background 0.15s, color 0.15s; }
    .action-btn:hover { background: #f3f4f6; color: #111; }
    .action-btn.danger:hover { background: #fee2e2; color: #dc2626; }

    /* Avatar color palette */
    .av-0  { background: #1e293b; } .av-1  { background: #0f766e; } .av-2  { background: #7c3aed; } .av-3  { background: #b45309; }
    .av-4  { background: #be185d; } .av-5  { background: #1d4ed8; } .av-6  { background: #065f46; } .av-7  { background: #92400e; }
</style>
@endpush

@section('content')

{{-- ===== STAT CARDS ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-gray-100 rounded-md p-5 shadow-sm">
        <div class="stat-icon bg-blue-50 text-blue-500"><i class="fa-solid fa-users text-lg"></i></div>
        <div class="text-2xl font-extrabold text-gray-900" id="totalUsersCount">{{ number_format($totalUsers) }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium uppercase tracking-wider">Total Users</div>
    </div>
    <div class="bg-white border border-gray-100 rounded-md p-5 shadow-sm">
        <div class="stat-icon bg-green-50 text-green-500"><i class="fa-solid fa-user-check text-lg"></i></div>
        <div class="text-2xl font-extrabold text-gray-900" id="activeUsersCount">{{ number_format($activeUsers) }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium uppercase tracking-wider">Active Users</div>
    </div>
    <div class="bg-white border border-gray-100 rounded-md p-5 shadow-sm">
        <div class="stat-icon bg-purple-50 text-purple-500"><i class="fa-solid fa-bag-shopping text-lg"></i></div>
        <div class="text-2xl font-extrabold text-gray-900">{{ number_format($customers) }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium uppercase tracking-wider">Customers</div>
    </div>
    <div class="bg-white border border-gray-100 rounded-md p-5 shadow-sm">
        <div class="stat-icon bg-orange-50 text-orange-500"><i class="fa-solid fa-user-shield text-lg"></i></div>
        <div class="text-2xl font-extrabold text-gray-900">{{ number_format($admins) }}</div>
        <div class="text-xs text-gray-400 mt-0.5 font-medium uppercase tracking-wider">Admins</div>
    </div>
</div>

{{-- ===== FILTER BAR ===== --}}
<form id="filterForm" method="GET" action="{{ route('admin.users.index') }}" class="bg-white border border-gray-100 rounded-md p-4 mb-4 shadow-sm flex flex-wrap items-center gap-3">
    
    {{-- Custom Dropdown: Role --}}
    <div class="relative custom-dropdown">
        <button type="button" onclick="toggleFilterMenu('roleMenu')" class="custom-dropdown-btn min-w-[120px]">
            <span id="label-role">
                @if(request('role') == 'customer') Customer
                @elseif(request('role') == 'super_admin') Super Admin
                @elseif(request('role') == 'admin_gudang') Admin Gudang
                @else Role @endif
            </span>
            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
        </button>
        <div id="roleMenu" class="drop-menu absolute left-0 w-full mt-1 bg-white border border-gray-100 rounded-md shadow-lg hidden z-30 overflow-hidden">
            <ul class="text-sm text-gray-700">
                <li class="dropdown-item px-4 py-2 cursor-pointer font-bold" onclick="selectFilterItem('role', '', 'Semua Role')">Semua Role</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('role', 'customer', 'Customer')">Customer</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('role', 'super_admin', 'Super Admin')">Super Admin</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('role', 'admin_gudang', 'Admin Gudang')">Admin Gudang</li>
            </ul>
        </div>
        <input type="hidden" name="role" id="input-role" value="{{ request('role') }}">
    </div>

    {{-- Custom Dropdown: Status --}}
    <div class="relative custom-dropdown">
        <button type="button" onclick="toggleFilterMenu('statusMenu')" class="custom-dropdown-btn min-w-[110px]">
            <span id="label-status">{{ request('status') ? ucfirst(request('status')) : 'Status' }}</span>
            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
        </button>
        <div id="statusMenu" class="drop-menu absolute left-0 w-full mt-1 bg-white border border-gray-100 rounded-md shadow-lg hidden z-30 overflow-hidden">
            <ul class="text-sm text-gray-700">
                <li class="dropdown-item px-4 py-2 cursor-pointer font-bold" onclick="selectFilterItem('status', '', 'Semua Status')">Semua Status</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('status', 'active', 'Active')">Active</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('status', 'inactive', 'Inactive')">Inactive</li>
            </ul>
        </div>
        <input type="hidden" name="status" id="input-status" value="{{ request('status') }}">
    </div>

    {{-- Custom Dropdown: Orders --}}
    <div class="relative custom-dropdown">
        <button type="button" onclick="toggleFilterMenu('ordersMenu')" class="custom-dropdown-btn min-w-[130px]">
            <span id="label-orders">
                @if(request('orders') == '0') 0 Order
                @elseif(request('orders') == '1-5') 1 - 5 Orders
                @elseif(request('orders') == '6-10') 6 - 10 Orders
                @elseif(request('orders') == '11+') 11+ Orders
                @else Total Order @endif
            </span>
            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
        </button>
        <div id="ordersMenu" class="drop-menu absolute left-0 w-full mt-1 bg-white border border-gray-100 rounded-md shadow-lg hidden z-30 overflow-hidden text-nowrap">
            <ul class="text-sm text-gray-700">
                <li class="dropdown-item px-4 py-2 cursor-pointer font-bold" onclick="selectFilterItem('orders', '', 'Semua Order')">Semua Order</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('orders', '0', '0 Order')">Belum Pernah Order</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('orders', '1-5', '1 - 5 Orders')">1 - 5 Orders</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('orders', '6-10', '6 - 10 Orders')">6 - 10 Orders</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('orders', '11+', '11+ Orders')">11+ Orders</li>
            </ul>
        </div>
        <input type="hidden" name="orders" id="input-orders" value="{{ request('orders') }}">
    </div>

    {{-- Custom Dropdown: Sort --}}
    <div class="relative custom-dropdown">
        <button type="button" onclick="toggleFilterMenu('sortMenu')" class="custom-dropdown-btn min-w-[120px]">
            <span id="label-sort">
                @if(request('sort') == 'az') A-Z
                @elseif(request('sort') == 'orders_desc') Order Terbanyak
                @elseif(request('sort') == 'spent_desc') Pengeluaran Terbesar
                @elseif(request('sort') == 'newest') Terbaru
                @else Urutkan @endif
            </span>
            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
        </button>
        <div id="sortMenu" class="drop-menu absolute left-0 w-full mt-1 bg-white border border-gray-100 rounded-md shadow-lg hidden z-30 overflow-hidden">
            <ul class="text-sm text-gray-700">
                <li class="dropdown-item px-4 py-2 cursor-pointer font-bold" onclick="selectFilterItem('sort', '', 'Default')">Default</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('sort', 'az', 'A-Z')">Nama: A → Z</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('sort', 'orders_desc', 'Order Terbanyak')">Order Terbanyak</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('sort', 'spent_desc', 'Pengeluaran Terbesar')">Pengeluaran Terbesar</li>
                <li class="dropdown-item px-4 py-2 cursor-pointer" onclick="selectFilterItem('sort', 'newest', 'Terbaru')">Terbaru Bergabung</li>
            </ul>
        </div>
        <input type="hidden" name="sort" id="input-sort" value="{{ request('sort') }}">
    </div>

    <div class="flex-1"></div>

    {{-- Search --}}
    <div class="search-box">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, ID...">
        <button type="submit" class="hidden"></button>
    </div>

    {{-- Reset --}}
    @if(request()->anyFilled(['role','status','orders','sort','search']))
    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-1.5 px-3 py-2 border border-gray-200 text-gray-500 text-sm rounded-md hover:bg-gray-50 transition-colors">
        <i class="fa-solid fa-rotate-left"></i>
    </a>
    @endif

    {{-- Add User Button --}}
    <button type="button" onclick="openAddModal()" class="flex items-center gap-2 bg-[#111111] text-white text-sm font-semibold px-4 py-2 rounded-md hover:bg-black transition-colors shadow-sm ml-1">
        <i class="fa-solid fa-plus text-xs"></i> Add User
    </button>
</form>

{{-- ===== TABLE ===== --}}
<div class="bg-white border border-gray-100 rounded-md overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-[#111111] text-white">
                    <th class="text-left px-5 py-4 text-[10px] font-bold tracking-widest uppercase">User</th>
                    <th class="text-left px-5 py-4 text-[10px] font-bold tracking-widest uppercase">Email</th>
                    <th class="text-left px-5 py-4 text-[10px] font-bold tracking-widest uppercase">Role</th>
                    <th class="text-left px-5 py-4 text-[10px] font-bold tracking-widest uppercase">Status</th>
                    <th class="text-left px-5 py-4 text-[10px] font-bold tracking-widest uppercase">Total Orders</th>
                    <th class="text-left px-5 py-4 text-[10px] font-bold tracking-widest uppercase">Total Spent</th>
                    <th class="text-left px-5 py-4 text-[10px] font-bold tracking-widest uppercase">Joined Date</th>
                    <th class="text-left px-5 py-4 text-[10px] font-bold tracking-widest uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">

                @forelse($users as $user)
                @php
                    $parts    = explode(' ', $user->name);
                    $initials = strtoupper(substr($parts[0],0,1) . (isset($parts[1]) ? substr($parts[1],0,1) : ''));
                    $avColor  = 'av-' . ($user->id % 8);

                    $roleClass = match($user->role) {
                        'super_admin'  => 'badge-admin',
                        'admin_gudang' => 'badge-gudang',
                        default        => 'badge-customer',
                    };
                    $roleLabel = match($user->role) {
                        'super_admin'  => 'Super Admin',
                        'admin_gudang' => 'Admin Gudang',
                        default        => 'Customer',
                    };
                @endphp

                <tr class="user-row transition-colors hover:bg-gray-50/50">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="user-avatar {{ $avColor }}">{{ $initials }}</div>
                            <div>
                                <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                <div class="text-[10px] text-gray-400 font-mono mt-0.5">USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                    </td>

                    <td class="px-5 py-4 text-gray-600 text-sm font-medium">{{ $user->email }}</td>

                    <td class="px-5 py-4">
                        <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-md {{ $roleClass }}">{{ $roleLabel }}</span>
                    </td>

                    {{-- Toggle Status (Sudah diperbaiki agar tidak tabrakan) --}}
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2.5">
                            <label class="toggle-wrap mb-0" title="Aktif/Nonaktifkan">
                                <input type="checkbox" class="toggle-input" {{ $user->is_active ? 'checked' : '' }} onchange="toggleStatus({{ $user->id }}, this)">
                                <span class="toggle-track"></span>
                            </label>
                            <span class="text-xs text-gray-600 font-bold status-label">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                        </div>
                    </td>

                    <td class="px-5 py-4 font-bold text-gray-800">{{ $user->orders_count }}</td>
                    <td class="px-5 py-4 font-extrabold text-gray-900">Rp {{ number_format($user->orders_sum_total ?? 0, 0, ',', '.') }}</td>
                    <td class="px-5 py-4 text-gray-500 text-xs">{{ $user->created_at->format('d M Y') }}</td>

                    <td class="px-5 py-4">
                        <div class="flex items-center gap-1">
                            <button class="action-btn" title="Edit User" onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->role }}')">
                                <i class="fa-regular fa-pen-to-square text-[15px]"></i>
                            </button>
                            <button class="action-btn danger" title="Hapus User" onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                <i class="fa-regular fa-trash-can text-[15px]"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-16 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-50 mb-3">
                            <i class="fa-solid fa-users-slash text-xl text-gray-300"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-400">Tidak ada user yang ditemukan</p>
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
</div>

{{-- ===== MODAL ADD / EDIT USER ===== --}}
<div id="userModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-md w-full max-w-md mx-4 p-7 shadow-2xl transform scale-95 transition-transform duration-300">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-bold text-gray-900 text-lg" id="modalTitle">Add User</h3>
                <p class="text-xs text-gray-500 mt-0.5" id="modalSubtitle">Isi detail untuk membuat user baru</p>
            </div>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-900 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <input type="hidden" id="modalUserId">

        <div class="flex flex-col gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Nama Lengkap</label>
                <input type="text" id="modalName" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors" placeholder="John Doe">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Email Address</label>
                <input type="email" id="modalEmail" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors" placeholder="john@example.com">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Role</label>
                <select id="modalRole" class="w-full px-4 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors bg-white">
                    <option value="customer">Customer</option>
                    <option value="super_admin">Super Admin</option>
                    <option value="admin_gudang">Admin Gudang</option>
                </select>
            </div>
            <div id="passwordField">
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wide">Password</label>
                <div class="relative">
                    <input type="password" id="modalPassword" class="w-full pl-4 pr-10 py-2.5 border border-gray-200 rounded-md text-sm focus:border-black outline-none transition-colors" placeholder="Min. 8 karakter">
                    <button type="button" onclick="toggleModalPassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700">
                        <i id="eyeIcon" class="fa-regular fa-eye"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex gap-3 mt-8">
            <button onclick="closeModal()" class="flex-1 py-2.5 border border-gray-200 rounded-md text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors">Batal</button>
            <button onclick="submitModal()" id="modalSubmitBtn" class="flex-1 py-2.5 bg-[#111111] text-white rounded-md text-sm font-bold hover:bg-black transition-colors shadow-sm">Simpan</button>
        </div>

    </div>
</div>

{{-- ===== CUSTOM MODAL DELETE ===== --}}
<div id="deleteUserModal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-md w-full max-w-sm mx-4 p-6 text-center shadow-2xl transform scale-95 transition-transform duration-300">
        <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-triangle-exclamation text-red-500 text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus User?</h3>
        <p class="text-base font-bold text-gray-800 mb-1" id="deleteUserName">"Nama User"</p>
        <p class="text-sm text-red-500 mb-6">Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex gap-3">
            <button type="button" onclick="closeDeleteModal()" class="flex-1 py-2.5 rounded-md border border-gray-300 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
            <button type="button" id="confirmDeleteBtn" class="flex-1 py-2.5 rounded-md bg-[#dc2626] text-white text-sm font-bold hover:bg-red-700 transition-colors shadow-sm">Ya, Hapus</button>
        </div>
    </div>
</div>

{{-- FLOATING TOAST --}}
<div id="toast-container" class="fixed top-5 left-1/2 -translate-x-1/2 z-[100] hidden transform transition-all duration-300 ease-out translate-y-[-20px] opacity-0">
    <div class="flex items-center gap-3 bg-white border border-gray-100 text-gray-800 text-sm font-bold px-5 py-3.5 rounded-full shadow-lg">
        <div id="toast-icon" class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center"></div>
        <span id="toast-msg"></span>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ==== 1. Toast System ====
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
        setTimeout(() => { container.classList.remove('translate-y-[-20px]', 'opacity-0'); container.classList.add('translate-y-0', 'opacity-100'); }, 10);
        setTimeout(() => {
            container.classList.remove('translate-y-0', 'opacity-100'); container.classList.add('translate-y-[-20px]', 'opacity-0');
            setTimeout(() => { container.classList.add('hidden'); }, 300);
        }, 2500);
    }

    // ==== 2. Custom Dropdown Filter ====
    function toggleFilterMenu(id) {
        document.querySelectorAll('.drop-menu').forEach(menu => { if(menu.id !== id) menu.classList.add('hidden'); });
        document.getElementById(id).classList.toggle('hidden');
    }
    function selectFilterItem(type, value, labelHtml) {
        document.getElementById('input-' + type).value = value;
        document.getElementById('label-' + type).innerHTML = labelHtml;
        document.getElementById(type + 'Menu').classList.add('hidden');
        document.getElementById('filterForm').submit();
    }
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-dropdown')) document.querySelectorAll('.drop-menu').forEach(m => m.classList.add('hidden'));
    });

    // ==== 3. Toggle Status (AJAX) dengan Real-Time Counter Update ====
    function toggleStatus(id, checkbox) {
        const label = checkbox.closest('.flex').querySelector('.status-label');
        fetch(`/admin/users/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            label.textContent = data.is_active ? 'Active' : 'Inactive';
            showToast('Status user diperbarui');

            // Update Stat Card "Active Users" secara langsung
            const activeCountEl = document.getElementById('activeUsersCount');
            if (activeCountEl) {
                let currentCount = parseInt(activeCountEl.textContent.replace(/,/g, ''));
                currentCount = data.is_active ? currentCount + 1 : currentCount - 1;
                activeCountEl.textContent = currentCount.toLocaleString('id-ID');
            }
        })
        .catch(() => { showToast('Gagal merubah status', 'error'); checkbox.checked = !checkbox.checked; });
    }

    // ==== 4. Add & Edit Modal Engine ====
    let editMode = false;
    const modal = document.getElementById('userModal');
    const modalBox = modal.children[0];

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
        document.getElementById('modalTitle').textContent = 'Add User';
        document.getElementById('modalSubtitle').textContent = 'Buat akun pengguna baru';
        document.getElementById('modalSubmitBtn').textContent = 'Tambah User';
        document.getElementById('modalUserId').value = '';
        document.getElementById('modalName').value = '';
        document.getElementById('modalEmail').value = '';
        document.getElementById('modalRole').value = 'customer';
        document.getElementById('modalPassword').value = '';
        document.getElementById('passwordField').style.display = 'block';
        openModal();
    }

    function openEditModal(id, name, email, role) {
        editMode = true;
        document.getElementById('modalTitle').textContent = 'Edit User';
        document.getElementById('modalSubtitle').textContent = 'Perbarui informasi pengguna';
        document.getElementById('modalSubmitBtn').textContent = 'Simpan Perubahan';
        document.getElementById('modalUserId').value = id;
        document.getElementById('modalName').value = name;
        document.getElementById('modalEmail').value = email;
        document.getElementById('modalRole').value = role;
        document.getElementById('passwordField').style.display = 'none';
        openModal();
    }

    function submitModal() {
        const id    = document.getElementById('modalUserId').value;
        const name  = document.getElementById('modalName').value.trim();
        const email = document.getElementById('modalEmail').value.trim();
        const role  = document.getElementById('modalRole').value;
        const pass  = document.getElementById('modalPassword').value;
        const btn   = document.getElementById('modalSubmitBtn');

        if (!name || !email) return showToast('Nama dan Email wajib diisi!', 'error');

        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        btn.disabled = true;

        const url = editMode ? `/admin/users/${id}` : '/admin/users';
        const method = editMode ? 'PUT' : 'POST';
        const body = editMode ? { name, email, role } : { name, email, role, password: pass };

        fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(body),
        })
        .then(r => r.ok ? r.json() : Promise.reject())
        .then(() => { closeModal(); window.location.reload(); })
        .catch(() => { showToast('Gagal menyimpan data.', 'error'); btn.innerHTML = 'Simpan'; btn.disabled = false; });
    }

    function toggleModalPassword() {
        const input = document.getElementById('modalPassword');
        const icon = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // ==== 5. Custom Delete Modal Engine ====
    let userToDelete = null;
    const deleteModal = document.getElementById('deleteUserModal');
    const deleteModalBox = deleteModal.children[0];

    function openDeleteModal(id, name) {
        userToDelete = id;
        document.getElementById('deleteUserName').textContent = `"${name}"`;
        deleteModal.classList.remove('hidden'); deleteModal.classList.add('flex');
        setTimeout(() => { deleteModal.classList.remove('opacity-0'); deleteModalBox.classList.remove('scale-95'); }, 10);
    }

    function closeDeleteModal() {
        deleteModal.classList.add('opacity-0'); deleteModalBox.classList.add('scale-95');
        setTimeout(() => { deleteModal.classList.add('hidden'); deleteModal.classList.remove('flex'); userToDelete = null; }, 300);
    }

    // Close on backdrop click for both modals
    document.getElementById('userModal').addEventListener('click', function(e) { if (e.target === this) closeModal(); });
    document.getElementById('deleteUserModal').addEventListener('click', function(e) { if (e.target === this) closeDeleteModal(); });

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (!userToDelete) return;
        this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        this.disabled = true;

        fetch(`/admin/users/${userToDelete}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        })
        .then(r => r.ok ? r.json() : Promise.reject())
        .then(() => { closeDeleteModal(); window.location.reload(); })
        .catch(() => { showToast('Gagal menghapus user.', 'error'); this.innerHTML = 'Ya, Hapus'; this.disabled = false; });
    });
</script>
@endpush