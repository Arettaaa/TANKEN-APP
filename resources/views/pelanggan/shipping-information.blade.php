@extends('layouts.main')

@section('title', 'Shipping Information — TANKEN')

@push('styles')
<style>
    .shipping-hero { background-color: #0a0a0a; }
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
    .shipping-option {
        border: 1px solid #e8e8e8;
        transition: border-color 0.2s ease;
    }
    .shipping-option:hover {
        border-color: #bbb;
    }
    .notes-banner {
        background: #0a0a0a;
    }
    .notes-banner li {
        position: relative;
        padding-left: 14px;
    }
    .notes-banner li::before {
        content: '–';
        position: absolute;
        left: 0;
        color: #666;
    }
</style>
@endpush

@section('content')

{{-- ===== HERO ===== --}}
<section class="shipping-hero px-6 lg:px-10 py-10 md:py-14">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center gap-4">
            <div class="shipping-icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="white" stroke-width="1.6" width="22" height="22">
                    <rect x="1" y="3" width="15" height="13"/>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 8"/>
                    <circle cx="5.5" cy="18.5" r="2.5"/>
                    <circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight"
                    style="font-family:'Inter',sans-serif;">
                    Shipping Information
                </h1>
                <p class="text-sm text-white/50 mt-1">Everything you need to know about our shipping and delivery</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== MAIN CONTENT ===== --}}
<div class="bg-white py-12 md:py-16">
    <div class="max-w-3xl mx-auto px-6 lg:px-10">

        {{-- TOMBOL BACK --}}
        <div class="mb-8">
            <a href="{{ route('help') }}" class="inline-flex items-center gap-1.5 text-gray-500 hover:text-black transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 group-hover:-translate-x-0.5 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span class="text-xs font-bold uppercase tracking-widest">Back</span>
            </a>
        </div>

        {{-- ===== 1. PENGIRIMAN DOMESTIK ===== --}}
        <section class="mb-14 reveal">
            <div class="flex items-center gap-3 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.6" width="20" height="20" class="text-gray-600">
                    <rect x="1" y="3" width="15" height="13"/>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 8"/>
                    <circle cx="5.5" cy="18.5" r="2.5"/>
                    <circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
                <h2 class="text-lg font-bold text-gray-900" style="font-family:'Inter',sans-serif;">
                    Pengiriman Domestik (Indonesia)
                </h2>
            </div>

            <div class="flex flex-col gap-3">
                <div class="shipping-option rounded-none px-6 py-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 mb-0.5">Layanan Reguler</p>
                            <p class="text-xs text-gray-400">Tersedia via JNE Reguler & SiCepat Reguler</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-[0.65rem] text-gray-400 mb-0.5">Estimasi</p>
                            <p class="text-sm font-semibold text-gray-900 whitespace-nowrap">2–4 Hari Kerja</p>
                        </div>
                    </div>
                </div>

                <div class="shipping-option rounded-none px-6 py-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 mb-0.5">Layanan Standard & Ekonomi</p>
                            <p class="text-xs text-gray-400">Tersedia via J&T Express</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-[0.65rem] text-gray-400 mb-0.5">Estimasi</p>
                            <p class="text-sm font-semibold text-gray-900 whitespace-nowrap">3–5 Hari Kerja</p>
                        </div>
                    </div>
                </div>

                <div class="mt-2 p-4 bg-gray-50 border border-gray-100 flex gap-3 items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" width="16" height="16" class="mt-0.5 text-gray-500 flex-shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Biaya ongkos kirim dihitung secara otomatis saat proses <strong>Checkout</strong> berdasarkan lokasi wilayah (Provinsi, Kota/Kabupaten, Kecamatan) dan berat produk yang dipesan.
                    </p>
                </div>
            </div>
        </section>

        {{-- ===== 2. PEMROSESAN PESANAN ===== --}}
        <section class="mb-14 reveal">
            <div class="flex items-center gap-3 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.6" width="20" height="20" class="text-gray-600">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
                <h2 class="text-lg font-bold text-gray-900" style="font-family:'Inter',sans-serif;">
                    Pemrosesan Pesanan
                </h2>
            </div>

            <div class="border border-gray-100 rounded-none px-6 py-6 flex flex-col gap-5">
                <div>
                    <p class="text-sm font-semibold text-gray-900 mb-1">Waktu Pemrosesan</p>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Pesanan yang masuk dan terkonfirmasi pembayarannya sebelum pukul <strong>15:00 WIB (Senin - Sabtu)</strong> akan diproses dan dikirim pada hari yang sama. Untuk pesanan yang masuk pada hari <strong>Minggu</strong>, akan diproses dan dikirim pada hari Senin.
                    </p>
                </div>
                <hr class="border-gray-100">
                <div>
                    <p class="text-sm font-semibold text-gray-900 mb-1">Pelacakan Pesanan (Resi)</p>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Untuk keamanan dan kenyamanan Anda, nomor resi pengiriman akan diperbarui di halaman <strong>Pesanan Saya</strong> dan dapat dimonitor secara langsung melalui sistem pelacakan kurir terkait.
                    </p>
                </div>
                <hr class="border-gray-100">
                <div>
                    <p class="text-sm font-semibold text-gray-900 mb-1">Kendala Pengiriman</p>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Jika pesanan Anda belum tiba melewati estimasi waktu, silakan hubungi Customer Service kami di
                        <span class="text-gray-700 font-medium">explore.tanken@gmail.com</span> atau WhatsApp. Kami akan membantu melacak paket Anda.
                    </p>
                </div>
            </div>
        </section>

        {{-- ===== 3. CATATAN PENTING (SESUAI ACTIVITY DIAGRAM) ===== --}}
        <section class="mb-14 reveal">
            <div class="notes-banner rounded-none px-6 py-6">
                <p class="text-sm font-bold text-white mb-4">Catatan Penting</p>
                <ul class="flex flex-col gap-3">
                    <li class="text-sm text-gray-400 leading-relaxed">
                        <strong>Keterlambatan/Pembatalan:</strong> Pesanan Anda dapat dibatalkan oleh pihak toko jika pesanan masuk saat tim kami sedang libur atau berada di luar kota, sehingga kami tidak bisa menangani pengiriman.
                    </li>
                    <li class="text-sm text-gray-400 leading-relaxed">
                        Kami menerapkan <strong>batas waktu maksimal 2 hari</strong> setelah pesanan masuk untuk memastikan pesanan Anda sudah berstatus terkirim (diproses kurir).
                    </li>
                    <li class="text-sm text-gray-400 leading-relaxed">
                        Lama waktu pengiriman sepenuhnya bergantung pada estimasi masing-masing layanan kurir yang Anda pilih saat <i>checkout</i>.
                    </li>
                </ul>
            </div>
        </section>

        {{-- ===== 4. CTA ===== --}}
        <section class="text-center reveal">
            <p class="text-sm text-gray-500 mb-5">Have more questions about shipping?</p>
            <a href="https://wa.me/6285121235200?text=Halo%20admin%20tanken%2C%20saya%20ingin%20bertanya%20terkait%20pengiriman..." 
               target="_blank" 
               class="inline-flex items-center gap-2 bg-black text-white text-xs font-bold tracking-widest uppercase px-8 py-3.5 rounded-none hover:bg-gray-800 transition-colors">
                Contact Support
            </a>
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