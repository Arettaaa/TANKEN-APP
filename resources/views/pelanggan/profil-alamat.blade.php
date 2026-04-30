@extends('layouts.akun-pelanggan')

@section('title', 'Alamat Saya — TANKEN')

@push('akun-styles')
<style>
    /* Address Card Hover Effect */
    .address-card { transition: border-color 0.2s ease, box-shadow 0.2s ease; }
    .address-card:hover { border-color: #d1d5db; background-color: #fafafa; }

    /* Modal Styles */
    .modal-overlay {
        opacity: 0; visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    .modal-content {
        transform: translateY(20px) scale(0.95); opacity: 0;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
    }
    .modal-overlay.active .modal-content { transform: translateY(0) scale(1); opacity: 1; }

    /* Input Styles */
    .input-group { display: flex; flex-direction: column; gap: 6px; }
    .input-label { font-size: 0.65rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; color: #6b7280; }
    .form-input-box {
        width: 100%; padding: 14px 16px; background-color: #f9fafb; border: 1px solid transparent;
        border-radius: 8px; font-size: 0.875rem; color: #111; outline: none; transition: all 0.2s;
    }
    .form-input-box:focus, .form-input-box.active-dropdown {
        background-color: #fff; border-color: #111; box-shadow: 0 0 0 4px rgba(17, 17, 17, 0.05);
    }
    .form-input-box::placeholder { color: #9ca3af; }
    .form-input-box.error-border { border-color: #ef4444; background-color: #fef2f2; }

    /* Label Pill Button */
    .label-pill {
        padding: 8px 20px; border-radius: 999px; font-size: 0.75rem; font-weight: 700;
        letter-spacing: 0.05em; text-transform: uppercase; border: 1px solid #e5e7eb;
        color: #6b7280; background: #fff; transition: all 0.2s ease; cursor: pointer;
    }
    .label-pill.active { background: #111; border-color: #111; color: #fff; }

    /* Custom Region Dropdown */
    .region-dropdown {
        display: none; position: absolute; top: calc(100% + 4px); left: 0; width: 100%;
        background: #fff; border: 1px solid #e5e7eb; border-radius: 8px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); z-index: 50; overflow: hidden;
    }
    .region-dropdown.show { display: flex; flex-direction: column; }
    
    .region-tab {
        flex: 1; text-align: center; padding: 12px 4px; font-size: 0.75rem; font-weight: 600;
        color: #9ca3af; border-bottom: 2px solid transparent; cursor: pointer; transition: all 0.2s;
    }
    .region-tab.active { color: #111; border-bottom-color: #111; }
    .region-tab:disabled { cursor: not-allowed; opacity: 0.5; }
    
    .region-list-item {
        padding: 10px 16px; font-size: 0.8125rem; font-weight: 500; color: #374151;
        cursor: pointer; transition: background-color 0.2s;
    }
    .region-list-item:hover { background-color: #f3f4f6; color: #111; }

    /* Custom Search Box in Dropdown */
    .region-search-wrapper {
        padding: 10px 16px; border-bottom: 1px solid #e5e7eb; background: #fff;
    }
    .region-search-input {
        width: 100%; padding: 8px 12px 8px 36px; background-color: #f9fafb;
        border: 1px solid #e5e7eb; border-radius: 6px; font-size: 0.8125rem; outline: none;
    }
    .region-search-input:focus { border-color: #111; }

    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('akun-content')

@php
$addresses = collect([
    [
        'id'         => 1,
        'name'       => 'Aretta Dwi',
        'phone'      => '089666123456',
        'region'     => 'BANJAR WARU, CIAWI, KABUPATEN BOGOR, JAWA BARAT',
        'postal'     => '16720',
        'street'     => 'Jl. Veteran III, Banjar Waru, RT 01/RW 02',
        'details'    => 'Patokan: Gerbang warna hitam dekat minimarket',
        'label'      => 'Rumah',
        'is_default' => true,
    ],
    [
        'id'         => 2,
        'name'       => 'Mahza Aiko Zabrina',
        'phone'      => '081908765432',
        'region'     => 'BABAKAN, DRAMAGA, KABUPATEN BOGOR, JAWA BARAT',
        'postal'     => '16680',
        'street'     => 'Gg. Bara, Kampus Dalam IPB',
        'details'    => 'Kosan warna abu-abu lantai 2',
        'label'      => 'Kantor',
        'is_default' => false,
    ]
]);
@endphp

<div>
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 pb-4 border-b border-gray-200 gap-4">
        <div>
            <p class="text-[10px] sm:text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Pengiriman</p>
            <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900">Alamat Saya</h2>
        </div>
        
        <button type="button" id="btnTambahAlamat" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 bg-black text-white text-[11px] font-bold tracking-widest uppercase px-6 py-3.5 rounded-md hover:bg-gray-800 transition-colors shadow-sm">
            <i class="fa-solid fa-plus text-sm"></i>
            Tambah Alamat
        </button>
    </div>

    {{-- List Alamat --}}
    <div class="flex flex-col gap-4">
        @forelse($addresses as $addr)
        <div class="address-card border border-gray-200 rounded-lg p-5 sm:p-6 bg-white flex flex-col sm:flex-row sm:items-start justify-between gap-4 sm:gap-6" id="address-item-{{ $addr['id'] }}">
            
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-2">
                    <span class="font-bold text-gray-900 text-base">{{ $addr['name'] }}</span>
                    <span class="text-gray-300 hidden sm:inline">|</span>
                    <span class="text-sm text-gray-500 font-medium">(+62) {{ ltrim($addr['phone'], '0') }}</span>
                </div>
                
                <div class="text-sm text-gray-600 leading-relaxed mb-3">
                    <p>{{ $addr['street'] }}</p>
                    <p class="uppercase text-[11px] tracking-wide mt-1 font-semibold text-gray-500">{{ $addr['region'] }}, ID, {{ $addr['postal'] }}</p>
                    @if($addr['details'])
                        <p class="text-gray-400 italic mt-1"><i class="fa-regular fa-note-sticky mr-1"></i>{{ $addr['details'] }}</p>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @if($addr['is_default'])
                        <span class="bg-black text-white text-[9px] font-bold tracking-widest uppercase px-2 py-1 rounded">Utama</span>
                    @endif
                    @if($addr['label'])
                        <span class="border border-gray-300 text-gray-600 text-[9px] font-bold tracking-widest uppercase px-2 py-1 rounded">{{ $addr['label'] }}</span>
                    @endif
                </div>
            </div>

            <div class="flex flex-col sm:items-end justify-between min-h-[100px] gap-4">
                <div class="flex items-center sm:justify-end gap-3 sm:gap-4 text-sm font-semibold">
                    <button type="button" class="btn-edit-alamat text-gray-500 hover:text-black transition-colors" data-alamat="{{ json_encode($addr) }}">Ubah</button>
                    
                    @if(!$addr['is_default'])
                        <button type="button" class="btn-hapus-alamat text-red-500 hover:text-red-700 transition-colors" data-id="{{ $addr['id'] }}">Hapus</button>
                    @endif
                </div>

                @if(!$addr['is_default'])
                    <button type="button" class="w-full sm:w-auto border border-gray-300 text-gray-700 hover:border-black hover:text-black text-[10px] font-bold tracking-widest uppercase px-4 py-2 rounded transition-colors">
                        Atur sbg Utama
                    </button>
                @endif
            </div>

        </div>
        @empty
            <div class="text-center py-12 border border-gray-200 rounded-lg bg-gray-50">
                <i class="fa-solid fa-map-location-dot text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 text-sm font-medium">Kamu belum memiliki alamat tersimpan.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- ========================================== --}}
{{-- MODAL: TAMBAH / UBAH ALAMAT --}}
{{-- ========================================== --}}
<div id="addressModal" class="modal-overlay fixed inset-0 z-[120] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="modal-content w-full max-w-[650px] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        
        <div class="px-6 sm:px-8 py-5 flex items-center justify-between sticky top-0 bg-white z-10 border-b border-gray-100">
            <h3 id="modalTitle" class="text-lg font-extrabold text-gray-900">Alamat Pengiriman</h3>
            <button type="button" id="btnCloseModal" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-black transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="p-6 sm:px-8 overflow-y-auto hide-scrollbar flex-1 relative" id="modalScrollBody">
            <form id="addressForm">
                <input type="hidden" id="addrId">

                <div class="mb-8">
                    <label class="input-label mb-3 block">Simpan Sebagai</label>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="setLabel('Rumah')" id="btnLabelRumah" class="label-pill">Rumah</button>
                        <button type="button" onclick="setLabel('Kantor')" id="btnLabelKantor" class="label-pill">Kantor</button>
                    </div>
                    <input type="hidden" id="addrLabel" value="">
                </div>

                <div class="mb-8">
                    <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                        <i class="fa-regular fa-address-book text-gray-400"></i> Informasi Kontak
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="input-group">
                            <label class="input-label">Nama Lengkap</label>
                            <input type="text" id="addrName" class="form-input-box" placeholder="Contoh: Mahza Aiko" oninput="clearError('addrName')">
                            <p id="err-addrName" class="hidden text-xs text-red-500 font-medium mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>Nama tidak boleh kosong.</p>
                        </div>
                        <div class="input-group">
                            <label class="input-label">Nomor Telepon</label>
                            <input type="number" id="addrPhone" class="form-input-box" placeholder="Contoh: 08123456789" oninput="clearError('addrPhone')">
                            <p id="err-addrPhone" class="hidden text-xs text-red-500 font-medium mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>Nomor telepon wajib diisi.</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                        <i class="fa-solid fa-location-dot text-gray-400"></i> Detail Alamat
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                        {{-- REGION CASCADING DROPDOWN (LOCAL DATA) --}}
                        <div class="input-group md:col-span-2 relative">
                            <label class="input-label">Provinsi, Kota, Kecamatan, Kelurahan</label>
                            <div id="regionTrigger" class="form-input-box cursor-pointer flex justify-between items-center" onclick="toggleRegionDropdown()">
                                <span id="regionDisplayText" class="text-gray-400 truncate pr-4">Pilih Wilayah</span>
                                <i class="fa-solid fa-chevron-down text-gray-400 text-xs"></i>
                            </div>
                            <input type="hidden" id="addrRegion" value="">
                            <p id="err-addrRegion" class="hidden text-xs text-red-500 font-medium mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>Silakan lengkapi wilayah.</p>

                            {{-- Popover --}}
                            <div id="regionDropdown" class="region-dropdown">
                                {{-- Search Bar --}}
                                <div class="region-search-wrapper relative">
                                    <i class="fa-solid fa-magnifying-glass absolute left-7 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                    <input type="text" id="regionSearchInput" class="region-search-input" placeholder="Cari wilayah..." oninput="handleSearch(this.value)">
                                </div>
                                {{-- Tabs --}}
                                <div class="flex border-b border-gray-100 bg-gray-50/50">
                                    <button type="button" class="region-tab active" id="tab-0" onclick="changeStep(0)">Provinsi</button>
                                    <button type="button" class="region-tab" id="tab-1" onclick="changeStep(1)" disabled>Kota</button>
                                    <button type="button" class="region-tab" id="tab-2" onclick="changeStep(2)" disabled>Kecamatan</button>
                                    <button type="button" class="region-tab" id="tab-3" onclick="changeStep(3)" disabled>Kelurahan</button>
                                </div>
                                {{-- List --}}
                                <div class="p-1 max-h-48 overflow-y-auto" id="regionListContainer">
                                    {{-- Rendered by JS --}}
                                </div>
                            </div>
                        </div>

                        {{-- Input Kode Pos Terpisah --}}
                        <div class="input-group">
                            <label class="input-label">Kode Pos</label>
                            <input type="number" id="addrPostal" class="form-input-box" placeholder="Cth: 16680" oninput="clearError('addrPostal')">
                            <p id="err-addrPostal" class="hidden text-xs text-red-500 font-medium mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>Wajib diisi.</p>
                        </div>
                    </div>

                    <div class="input-group mb-5">
                        <label class="input-label">Nama Jalan, Gedung, No. Rumah</label>
                        <input type="text" id="addrStreet" class="form-input-box" placeholder="Contoh: Jl. Sudirman No. 12, Gedung A" oninput="clearError('addrStreet')">
                        <p id="err-addrStreet" class="hidden text-xs text-red-500 font-medium mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>Jalan/Gedung wajib diisi.</p>
                    </div>

                    <div class="input-group">
                        <label class="input-label">Detail Lainnya (Opsional)</label>
                        <input type="text" id="addrDetails" class="form-input-box" placeholder="Contoh: Patokan dekat minimarket, pagar hitam">
                    </div>
                </div>
            </form>
        </div>

        <div class="px-6 sm:px-8 py-5 border-t border-gray-100 bg-white flex items-center justify-end gap-3 sticky bottom-0 rounded-b-2xl">
            <button type="button" id="btnBatal" class="text-sm font-bold text-gray-500 hover:text-black px-4 py-2 transition-colors">Batal</button>
            <button type="button" id="btnSubmitAddress" class="bg-black text-white h-12 px-8 text-[11px] font-bold tracking-widest uppercase rounded-lg hover:bg-gray-800 transition-colors flex items-center justify-center gap-2">
                Simpan Alamat
            </button>
        </div>
    </div>
</div>

@endsection

@push('akun-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const modal = document.getElementById('addressModal');
        const btnTambah = document.getElementById('btnTambahAlamat');
        const btnClose = document.getElementById('btnCloseModal');
        const btnBatal = document.getElementById('btnBatal');
        const btnSubmit = document.getElementById('btnSubmitAddress');
        const form = document.getElementById('addressForm');
        
        const regionDropdown = document.getElementById('regionDropdown');
        const regionTrigger = document.getElementById('regionTrigger');
        const listContainer = document.getElementById('regionListContainer');
        const searchInput = document.getElementById('regionSearchInput');
        const modalScrollBody = document.getElementById('modalScrollBody');

        if(btnTambah) btnTambah.addEventListener('click', () => openModal('add'));
        if(btnClose) btnClose.addEventListener('click', closeModal);
        if(btnBatal) btnBatal.addEventListener('click', closeModal);
        if(btnSubmit) btnSubmit.addEventListener('click', submitAddress);

        document.querySelectorAll('.btn-edit-alamat').forEach(btn => {
            btn.addEventListener('click', function() {
                try {
                    const data = JSON.parse(this.getAttribute('data-alamat'));
                    openModal('edit', data);
                } catch(e) { console.error(e); }
            });
        });

        document.querySelectorAll('.btn-hapus-alamat').forEach(btn => {
            btn.addEventListener('click', function() {
                deleteAddress(this.getAttribute('data-id'));
            });
        });

        window.addEventListener('click', function(event) {
            if (event.target == modal) closeModal();
        });

        function getScrollbarWidth() { 
            return window.innerWidth - document.documentElement.clientWidth; 
        }

        function openModal(mode, data = null) {
            const scrollbarWidth = getScrollbarWidth();
            document.body.style.overflow = 'hidden'; 
            document.body.style.paddingRight = `${scrollbarWidth}px`;
            
            form.reset();
            document.getElementById('addrId').value = '';
            setLabel('');
            closeRegionDropdown();
            
            currentStep = 0; currentListData = [];
            regionState = { prov: {id:'', name:''}, kota: {id:'', name:''}, kec: {id:'', name:''}, kel: {id:'', name:''} };
            updateTabsUI();
            
            ['addrName', 'addrPhone', 'addrRegion', 'addrPostal', 'addrStreet'].forEach(id => clearError(id));
            
            if (mode === 'edit' && data) {
                document.getElementById('modalTitle').innerText = 'Ubah Alamat Pengiriman';
                document.getElementById('addrId').value = data.id;
                document.getElementById('addrName').value = data.name;
                document.getElementById('addrPhone').value = data.phone;
                document.getElementById('addrPostal').value = data.postal || '';
                
                document.getElementById('addrRegion').value = data.region;
                document.getElementById('regionDisplayText').innerText = data.region;
                document.getElementById('regionDisplayText').classList.replace('text-gray-400', 'text-gray-900');

                document.getElementById('addrStreet').value = data.street;
                document.getElementById('addrDetails').value = data.details || '';
                if(data.label) setLabel(data.label);
            } else {
                document.getElementById('modalTitle').innerText = 'Tambah Alamat Baru';
                document.getElementById('regionDisplayText').innerText = 'Pilih Wilayah';
                document.getElementById('regionDisplayText').classList.replace('text-gray-900', 'text-gray-400');
            }

            modal.classList.add('active');
            modalScrollBody.scrollTop = 0;
        }

        function closeModal() {
            modal.classList.remove('active');
            setTimeout(() => { 
                document.body.style.overflow = ''; 
                document.body.style.paddingRight = ''; 
            }, 300);
        }

        // ==========================================
        // DATA DUMMY LOKAL (PENGGANTI API)
        // ==========================================
        const dummyLokasi = {
            provinsi: [
                { id: '11', name: 'JAWA BARAT' },
                { id: '12', name: 'DKI JAKARTA' },
                { id: '13', name: 'JAWA TENGAH' }
            ],
            kota: {
                '11': [{ id: '111', name: 'KOTA BOGOR' }, { id: '112', name: 'KABUPATEN BOGOR' }, { id: '113', name: 'KOTA BANDUNG' }],
                '12': [{ id: '121', name: 'KOTA JAKARTA SELATAN' }, { id: '122', name: 'KOTA JAKARTA PUSAT' }],
                '13': [{ id: '131', name: 'KOTA SEMARANG' }]
            },
            kecamatan: {
                '111': [{ id: '1111', name: 'BOGOR TENGAH' }, { id: '1112', name: 'BOGOR SELATAN' }],
                '112': [{ id: '1121', name: 'CIAWI' }, { id: '1122', name: 'DRAMAGA' }, { id: '1123', name: 'CIBINONG' }],
                '121': [{ id: '1211', name: 'TEBET' }, { id: '1212', name: 'PASAR MINGGU' }]
            },
            kelurahan: {
                '1121': [{ id: '11211', name: 'BANJAR WARU' }, { id: '11212', name: 'BENDUNGAN' }],
                '1122': [{ id: '11221', name: 'BABAKAN' }, { id: '11222', name: 'CIHERANG' }],
                '1111': [{ id: '11111', name: 'TEGALLEGA' }, { id: '11112', name: 'BABAKAN PASAR' }]
            }
        };

        let currentStep = 0; 
        let regionState = { prov: {id:'', name:''}, kota: {id:'', name:''}, kec: {id:'', name:''}, kel: {id:'', name:''} };
        let currentListData = []; 

        window.toggleRegionDropdown = function() {
            if (regionDropdown.classList.contains('show')) closeRegionDropdown();
            else openRegionDropdown();
        };

        function openRegionDropdown() {
            regionDropdown.classList.add('show');
            regionTrigger.classList.add('active-dropdown');
            if(currentListData.length === 0) loadStepData(currentStep); 
            setTimeout(() => searchInput.focus(), 100);
        }

        function closeRegionDropdown() {
            regionDropdown.classList.remove('show');
            regionTrigger.classList.remove('active-dropdown');
            searchInput.value = '';
        }

        // Simulasi Loading API menggunakan Data Dummy Lokal
        function loadStepData(step) {
            listContainer.innerHTML = '<div class="p-6 flex justify-center"><i class="fa-solid fa-circle-notch fa-spin text-gray-400 text-2xl"></i></div>';
            
            setTimeout(() => {
                let data = [];
                if(step === 0) data = dummyLokasi.provinsi;
                if(step === 1 && regionState.prov.id) data = dummyLokasi.kota[regionState.prov.id] || [];
                if(step === 2 && regionState.kota.id) data = dummyLokasi.kecamatan[regionState.kota.id] || [];
                if(step === 3 && regionState.kec.id) data = dummyLokasi.kelurahan[regionState.kec.id] || [];
                
                currentListData = data;
                renderList(currentListData);
            }, 300); // Simulasi jeda internet 300ms
        }

        function renderList(data) {
            listContainer.innerHTML = '';
            if (data.length === 0) {
                listContainer.innerHTML = `<p class="p-3 text-sm text-gray-400 text-center">Data tidak tersedia.</p>`;
                return;
            }

            data.forEach(item => {
                const div = document.createElement('div');
                div.className = 'region-list-item';
                div.innerText = item.name;
                div.onclick = () => handleSelectRegion(item);
                listContainer.appendChild(div);
            });
        }

        window.handleSearch = function(keyword) {
            const lowerKeyword = keyword.toLowerCase();
            const filtered = currentListData.filter(item => item.name.toLowerCase().includes(lowerKeyword));
            renderList(filtered);
        };

        function handleSelectRegion(item) {
            searchInput.value = ''; 

            if (currentStep === 0) {
                regionState.prov = item;
                regionState.kota = {id:'', name:''}; regionState.kec = {id:'', name:''}; regionState.kel = {id:'', name:''};
                currentStep = 1;
                loadStepData(currentStep);
            } 
            else if (currentStep === 1) {
                regionState.kota = item;
                regionState.kec = {id:'', name:''}; regionState.kel = {id:'', name:''};
                currentStep = 2;
                loadStepData(currentStep);
            } 
            else if (currentStep === 2) {
                regionState.kec = item;
                regionState.kel = {id:'', name:''};
                currentStep = 3;
                loadStepData(currentStep);
            } 
            else if (currentStep === 3) {
                regionState.kel = item;
                finishRegionSelection();
                return;
            }
            updateTabsUI();
        }

        window.changeStep = function(stepIndex) {
            if (stepIndex === 1 && !regionState.prov.id) return;
            if (stepIndex === 2 && !regionState.kota.id) return;
            if (stepIndex === 3 && !regionState.kec.id) return;
            
            currentStep = stepIndex;
            searchInput.value = '';
            updateTabsUI();
            loadStepData(currentStep);
        };

        function updateTabsUI() {
            document.getElementById('tab-1').disabled = !regionState.prov.id;
            document.getElementById('tab-2').disabled = !regionState.kota.id;
            document.getElementById('tab-3').disabled = !regionState.kec.id;

            for(let i=0; i<=3; i++) {
                const btn = document.getElementById('tab-'+i);
                if(i === currentStep) btn.classList.add('active');
                else btn.classList.remove('active');
            }
        }

        function finishRegionSelection() {
            const fullString = `${regionState.kel.name}, ${regionState.kec.name}, ${regionState.kota.name}, ${regionState.prov.name}`;
            
            const displayTxt = document.getElementById('regionDisplayText');
            displayTxt.innerText = fullString;
            displayTxt.classList.replace('text-gray-400', 'text-gray-900');
            
            document.getElementById('addrRegion').value = fullString;
            clearError('addrRegion');
            closeRegionDropdown();
        }

        form.addEventListener('click', (e) => {
            if(!regionTrigger.contains(e.target) && !regionDropdown.contains(e.target)) {
                closeRegionDropdown();
            }
        });

        window.setLabel = function(label) {
            document.getElementById('addrLabel').value = label;
            document.getElementById('btnLabelRumah').classList.toggle('active', label === 'Rumah');
            document.getElementById('btnLabelKantor').classList.toggle('active', label === 'Kantor');
        };

        window.clearError = function(inputId) {
            if (inputId === 'addrRegion') {
                document.getElementById('regionTrigger').classList.remove('error-border');
                document.getElementById('err-addrRegion').classList.add('hidden');
                return;
            }
            const input = document.getElementById(inputId);
            const errMsg = document.getElementById('err-' + inputId);
            if(input && errMsg) { 
                input.classList.remove('error-border'); 
                errMsg.classList.add('hidden'); 
            }
        };

        function submitAddress() {
            let hasError = false;
            const inputs = [
                { id: 'addrName', val: document.getElementById('addrName').value.trim() },
                { id: 'addrPhone', val: document.getElementById('addrPhone').value.trim() },
                { id: 'addrRegion', val: document.getElementById('addrRegion').value },
                { id: 'addrPostal', val: document.getElementById('addrPostal').value.trim() },
                { id: 'addrStreet', val: document.getElementById('addrStreet').value.trim() }
            ];

            inputs.forEach(item => {
                if (!item.val) {
                    if(item.id === 'addrRegion') {
                        document.getElementById('regionTrigger').classList.add('error-border');
                    } else {
                        document.getElementById(item.id).classList.add('error-border');
                    }
                    document.getElementById('err-' + item.id).classList.remove('hidden');
                    hasError = true;
                }
            });

            if(hasError) return;

            const originalText = btnSubmit.innerHTML;
            btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> MENYIMPAN...';
            btnSubmit.classList.replace('bg-black', 'bg-gray-400');
            btnSubmit.disabled = true;

            setTimeout(() => { 
                closeModal(); 
                location.reload(); 
            }, 800);
        }

        function deleteAddress(id) {
            if(confirm('Yakin ingin menghapus alamat ini?')) {
                const card = document.getElementById('address-item-' + id);
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                card.style.opacity = '0'; 
                card.style.transform = 'scale(0.95)';
                setTimeout(() => { 
                    card.remove(); 
                    if(document.querySelectorAll('.address-card').length === 0) location.reload(); 
                }, 300);
            }
        }
    });
</script>
@endpush