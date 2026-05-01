@extends('layouts.main')

@section('title', 'Returns & Exchanges — TANKEN')

@push('styles')
<style>
    .returns-hero { background-color: #0a0a0a; }
    .shipping-icon-wrap {
        width: 52px;
        height: 52px;
        background: #1e1e1e;
        border: 1px solid #333;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Policy highlight card */
    .policy-card { background: #111; border-radius: 12px; }
    .policy-stat {
        border-right: 1px solid #2a2a2a;
    }
    .policy-stat:last-child { border-right: none; }

    /* Step number */
    .step-number {
        width: 32px;
        height: 32px;
        background: #111;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        flex-shrink: 0;
        font-family: 'Inter', sans-serif;
    }
    .step-card {
        border: 1px solid #e8e8e8;
        transition: border-color 0.2s ease;
    }
    .step-card:hover { border-color: #bbb; }

    /* Eligibility grid */
    .eligibility-card { border: 1px solid #e8e8e8; border-radius: 8px; }

    /* Check/X icons */
    .icon-check { color: #22c55e; }
    .icon-x { color: #ef4444; }

    /* Refund dark card */
    .refund-dark { background: #0a0a0a; border-radius: 12px; }
    .refund-item { border-bottom: 1px solid #1e1e1e; }
    .refund-item:last-child { border-bottom: none; }

    /* CTA */
    .cta-dark { background: #111; border-radius: 12px; }
</style>
@endpush

@section('content')

{{-- ===== HERO ===== --}}
<section class="returns-hero px-6 lg:px-10 py-10 md:py-14">
    <div class="max-w-7xl mx-auto">

        <a href="{{ route('help') }}"
           class="inline-flex items-center gap-2 text-xs text-white/40 hover:text-white/70 transition-colors mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2" width="13" height="13">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Kembali ke Pusat Bantuan
        </a>

        <div class="flex items-center gap-4">
            <div class="shipping-icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="white" stroke-width="1.6" width="22" height="22">
                    <polyline points="1 4 1 10 7 10"/>
                    <polyline points="23 20 23 14 17 14"/>
                    <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4-4.64 4.36A9 9 0 0 1 3.51 15"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight"
                    style="font-family:'Inter',sans-serif;">
                    Returns & Exchanges
                </h1>
                <p class="text-sm text-white/50 mt-1">
                    Kami ingin kamu puas dengan setiap pesanan. Nikmati kemudahan pengajuan retur dengan ongkir yang ditanggung sepenuhnya oleh Tanken.
                </p>
            </div>
        </div>

    </div>
</section>

{{-- ===== MAIN CONTENT ===== --}}
<div class="bg-white py-12 md:py-16">
    <div class="max-w-3xl mx-auto px-6 lg:px-10">

        {{-- ===== 1. KEBIJAKAN RETUR ===== --}}
        <section class="mb-14 reveal">
            <div class="policy-card p-7">
                <h2 class="text-base font-bold text-white mb-1.5" style="font-family:'Inter',sans-serif;">
                    Kebijakan Retur 7 Hari
                </h2>
                <p class="text-sm text-gray-400 leading-relaxed mb-7">
                    Pesanan tidak sesuai, cacat, atau salah ukuran? Ajukan pengembalian maksimal 7 hari
                    setelah barang diterima atau sebelum kamu mengklik tombol "Pesanan Selesai".
                </p>
                <div class="grid grid-cols-3">
                    <div class="policy-stat pr-6">
                        <p class="text-3xl font-extrabold text-white mb-1" style="font-family:'Inter',sans-serif;">7</p>
                        <p class="text-xs text-gray-500">Hari Batas Retur</p>
                    </div>
                    <div class="policy-stat px-6">
                        <p class="text-3xl font-extrabold text-white mb-1" style="font-family:'Inter',sans-serif;">GRATIS</p>
                        <p class="text-xs text-gray-500">Ongkir Retur</p>
                    </div>
                    <div class="pl-6">
                        <p class="text-3xl font-extrabold text-white mb-1" style="font-family:'Inter',sans-serif;">WAJIB</p>
                        <p class="text-xs text-gray-500">Video Unboxing</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== 2. CARA MENGAJUKAN RETUR ===== --}}
        <section class="mb-14 reveal">
            <h2 class="text-lg font-bold text-gray-900 mb-6" style="font-family:'Inter',sans-serif;">
                Cara Mengajukan Retur
            </h2>

            <div class="flex flex-col gap-3">
                @php
                $steps = [
                    [
                        'title' => 'Ajukan Retur',
                        'desc'  => 'Masuk ke menu "Pesanan Saya" dan klik "Ajukan Pengembalian" sebelum menyelesaikan pesanan.',
                    ],
                    [
                        'title' => 'Konfirmasi Tim',
                        'desc'  => 'Tim internal Tanken akan segera menghubungi kamu secara langsung untuk memandu proses retur selanjutnya.',
                    ],
                    [
                        'title' => 'Kemas & Kirim',
                        'desc'  => 'Kemas barang dengan aman menggunakan kemasan aslinya, tempelkan resi, dan serahkan ke cabang kurir terdekat.',
                    ],
                    [
                        'title' => 'Terima Refund',
                        'desc'  => 'Dana akan dikembalikan ke metode pembayaran awal kamu setelah paket tiba di gudang dan lolos pengecekan.',
                    ],
                ];
                @endphp

                @foreach($steps as $i => $step)
                <div class="step-card rounded-lg px-5 py-4 flex items-start gap-4">
                    <div class="step-number">{{ $i + 1 }}</div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 mb-0.5">{{ $step['title'] }}</p>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- ===== 3. SYARAT BARANG ===== --}}
        <section class="mb-14 reveal">
            <h2 class="text-lg font-bold text-gray-900 mb-6" style="font-family:'Inter',sans-serif;">
                Syarat Barang yang Bisa Diretur
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Eligible --}}
                <div class="eligibility-card p-5">
                    <p class="text-sm font-bold text-gray-900 mb-4">✓ Bisa Diretur</p>
                    <ul class="flex flex-col gap-3">
                        @php
                        $eligible = [
                            'Barang belum dipakai, tag masih terpasang',
                            'Masih dalam kemasan original',
                            'Tidak ada kerusakan atau perubahan pada produk',
                            'Dibeli dalam 30 hari terakhir',
                        ];
                        @endphp
                        @foreach($eligible as $item)
                        <li class="flex items-start gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2.5" width="15" height="15"
                                 class="icon-check mt-0.5 flex-shrink-0">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span class="text-sm text-gray-600 leading-snug">{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Not Eligible --}}
                <div class="eligibility-card p-5">
                    <p class="text-sm font-bold text-gray-900 mb-4">✕ Tidak Bisa Diretur</p>
                    <ul class="flex flex-col gap-3">
                        @php
                        $notEligible = [
                            'Barang tanpa tag original',
                            'Sudah dipakai atau dicuci',
                            'Pembelian lebih dari 30 hari lalu',
                            'Produk sale atau clearance',
                            'Pakaian dalam dan produk intim (alasan higienitas)',
                        ];
                        @endphp
                        @foreach($notEligible as $item)
                        <li class="flex items-start gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2.5" width="15" height="15"
                                 class="icon-x mt-0.5 flex-shrink-0">
                                <line x1="18" y1="6" x2="6" y2="18"/>
                                <line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                            <span class="text-sm text-gray-600 leading-snug">{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </section>

        {{-- ===== 4. PENUKARAN (EXCHANGES) ===== --}}
        <section class="mb-14 reveal">
            <h2 class="text-lg font-bold text-gray-900 mb-6" style="font-family:'Inter',sans-serif;">
                Penukaran Produk
            </h2>

            <div class="border border-gray-100 rounded-lg px-6 py-6 flex flex-col gap-5">

                <div>
                    <p class="text-sm text-gray-500 leading-relaxed mb-4">
                        Mau ganti ukuran atau warna? Gampang banget! Kami pastikan proses penukaran kamu berjalan cepat dan mulus.
                    </p>
                    <p class="text-sm font-semibold text-gray-900 mb-1">Cara Penukaran</p>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Kembalikan produk lama mengikuti alur retur seperti biasa, lalu buat pesanan baru untuk item
                        yang kamu inginkan. Cara ini memastikan ukuran atau warna pilihanmu langsung diproses secepat mungkin.
                    </p>
                </div>

                <hr class="border-gray-100">

                <div>
                    <p class="text-sm font-semibold text-gray-900 mb-1">Penukaran Ekspres</p>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Mau penukaran lebih cepat? Hubungi tim support kami, dan kami bisa mengirimkan pengganti
                        sebelum barang returmu tiba. Produk original wajib dikembalikan dalam 14 hari.
                    </p>
                </div>

            </div>
        </section>

        {{-- ===== 5. INFORMASI REFUND ===== --}}
        <section class="mb-14 reveal">
            <h2 class="text-lg font-bold text-gray-900 mb-6" style="font-family:'Inter',sans-serif;">
                Informasi Refund
            </h2>

            <div class="refund-dark px-6 py-2">

                <div class="refund-item py-5 flex items-start gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.6" width="18" height="18"
                         class="text-gray-500 mt-0.5 flex-shrink-0">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-white mb-1">Waktu Pemrosesan</p>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            Refund diproses dalam 5–7 hari kerja setelah barang retur diterima. Kamu akan mendapat konfirmasi email saat refund berhasil diproses.
                        </p>
                    </div>
                </div>

                <div class="refund-item py-5 flex items-start gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.6" width="18" height="18"
                         class="text-gray-500 mt-0.5 flex-shrink-0">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                        <line x1="1" y1="10" x2="23" y2="10"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-white mb-1">Metode Pengembalian Dana</p>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            Dana dikembalikan ke metode pembayaran awal kamu. Mohon tunggu 3–5 hari kerja setelah proses selesai agar dana masuk ke akunmu.
                        </p>
                    </div>
                </div>

                <div class="py-5 flex items-start gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.6" width="18" height="18"
                         class="text-gray-500 mt-0.5 flex-shrink-0">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-white mb-1">Opsi Kredit Toko</p>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            Pilih kredit toko dan dapatkan bonus 10% ekstra! Cukup beritahu kami saat mengajukan retur dan kami akan langsung memprosesnya.
                        </p>
                    </div>
                </div>

            </div>
        </section>

        {{-- ===== 6. RETUR INTERNASIONAL ===== --}}
        <section class="mb-14 reveal">
            <h2 class="text-lg font-bold text-gray-900 mb-6" style="font-family:'Inter',sans-serif;">
                Retur Internasional
            </h2>

            <div class="border border-gray-100 rounded-lg px-6 py-5">
                <p class="text-sm text-gray-500 leading-relaxed mb-3">
                    Pelanggan internasional menanggung biaya pengiriman retur. Kami sangat menyarankan penggunaan
                    jasa pengiriman yang dapat dilacak karena kami tidak bertanggung jawab atas paket yang hilang dalam perjalanan.
                </p>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Bea cukai dan pajak yang dibayarkan pada pesanan original bersifat tidak dapat dikembalikan.
                    Silakan hubungi kantor bea cukai setempat untuk informasi lebih lanjut mengenai pengembalian bea.
                </p>
            </div>
        </section>

        {{-- ===== 7. CTA ===== --}}
        <section class="reveal">
            <div class="cta-dark px-8 py-10 text-center">
                <h2 class="text-xl font-bold text-white mb-2" style="font-family:'Inter',sans-serif;">
                    Butuh Bantuan Retur?
                </h2>
                <p class="text-sm text-gray-400 mb-7">Tim customer service kami siap membantu kamu</p>
                <div class="flex items-center justify-center gap-3 flex-wrap">
                    <a href="mailto:support@tanken.com"
                       class="inline-flex items-center gap-2 border border-white text-white text-xs font-bold tracking-widest uppercase px-6 py-3 rounded-full hover:bg-white hover:text-black transition-colors">
                        Hubungi Support
                    </a>
                    <a href="{{ route('pelanggan.profil-order') }}"
                       class="inline-flex items-center gap-2 bg-white text-black text-xs font-bold tracking-widest uppercase px-6 py-3 rounded-full hover:bg-gray-200 transition-colors">
                        Lihat Pesanan Saya
                    </a>
                </div>
            </div>
        </section>

    </div>
</div>

@endsection

@push('scripts')
<script>
    const revealEls = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => entry.target.classList.add('visible'), i * 70);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    revealEls.forEach(el => observer.observe(el));
</script>
@endpush