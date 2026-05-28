@extends('layouts.main')

@section('title', 'FAQ — TANKEN')

@push('styles')
<style>
    /* Hero Style */
    .faq-hero {
        background-color: #0a0a0a;
    }
    
    /* Accordion Style */
    .faq-item {
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .faq-item:hover {
        border-color: #d1d5db;
    }
    .faq-btn {
        outline: none;
    }
    .faq-title {
        font-family: 'Inter', sans-serif;
        transition: color 0.2s;
    }
    .faq-icon {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .faq-content {
        transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>
@endpush

@section('content')

{{-- ===== 1. HERO ===== --}}
<section class="faq-hero px-6 lg:px-10 py-12 md:py-16 border-b border-gray-800">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-4 mb-4">
            {{-- Kotak Icon Tanya (Sudut Tajam) --}}
            <div class="w-12 h-12 md:w-14 md:h-14 rounded-none bg-gray-600/30 border border-gray-500/30 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.8" class="w-6 h-6 md:w-7 md:h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                </svg>
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight" style="font-family:'Inter',sans-serif;">
                Frequently Asked Questions
            </h1>
        </div>
        <p class="text-base text-gray-400">Temukan jawaban untuk pertanyaan umum seputar belanja di TANKEN.</p>
    </div>
</section>

{{-- ===== 2. MAIN CONTENT (FAQ BOXES) ===== --}}
<div class="bg-white py-12 md:py-16">
    <div class="max-w-4xl mx-auto px-6 lg:px-10">

        {{-- TOMBOL BACK --}}
        <div class="mb-8">
            <a href="{{ route('help') }}" class="inline-flex items-center gap-1.5 text-gray-500 hover:text-black transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 group-hover:-translate-x-0.5 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span class="text-xs font-bold uppercase tracking-widest">Back</span>
            </a>
        </div>

        {{-- KATEGORI 1 --}}
        <h2 class="text-xl md:text-2xl font-extrabold text-gray-900 mb-6" style="font-family:'Inter',sans-serif;">Akun & Cara Memesan</h2>
        
        <div class="faq-item bg-white border border-gray-200 rounded-none mb-4 overflow-hidden">
            <button class="faq-btn w-full flex justify-between items-center p-6 text-left">
                <span class="faq-title font-bold text-gray-900 text-[15px]">Bagaimana cara membuat akun baru di TANKEN?</span>
                <svg class="faq-icon w-5 h-5 text-gray-500 flex-shrink-0 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div class="faq-content max-h-0">
                <div class="px-6 pb-6 text-sm text-gray-600 leading-relaxed">
                    Klik tombol <strong>Daftar/Register</strong> di pojok kanan atas, isi formulir data diri dengan lengkap, lalu klik Daftar. Jika email yang kamu gunakan sudah pernah terdaftar, sistem akan otomatis memberi tahu kamu untuk langsung masuk.
                </div>
            </div>
        </div>

        <div class="faq-item bg-white border border-gray-200 rounded-none mb-4 overflow-hidden">
            <button class="faq-btn w-full flex justify-between items-center p-6 text-left">
                <span class="faq-title font-bold text-gray-900 text-[15px]">Saya masuk dengan Google, mengapa nomor HP saya kosong saat checkout?</span>
                <svg class="faq-icon w-5 h-5 text-gray-500 flex-shrink-0 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div class="faq-content max-h-0">
                <div class="px-6 pb-6 text-sm text-gray-600 leading-relaxed">
                    Fitur masuk instan dengan Google hanya mengambil data nama dan email demi keamanan. Kamu cukup mengisi nomor HP dan alamat lengkap secara manual di formulir pengiriman saat pertama kali melakukan checkout.
                </div>
            </div>
        </div>

        <div class="faq-item bg-white border border-gray-200 rounded-none mb-12 overflow-hidden">
            <button class="faq-btn w-full flex justify-between items-center p-6 text-left">
                <span class="faq-title font-bold text-gray-900 text-[15px]">Bagaimana cara berbelanja di TANKEN?</span>
                <svg class="faq-icon w-5 h-5 text-gray-500 flex-shrink-0 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div class="faq-content max-h-0">
                <div class="px-6 pb-6 text-sm text-gray-600 leading-relaxed">
                    Pilih produk dan ukuran > Masukkan ke keranjang belanja > Masuk ke halaman Checkout > Isi alamat pengiriman > Pilih metode pembayaran & kurir > Gunakan voucher jika ada > Lakukan pembayaran dan upload bukti transfer > Tunggu pesanan dikonfirmasi oleh admin.
                </div>
            </div>
        </div>


        {{-- KATEGORI 2 --}}
        <h2 class="text-xl md:text-2xl font-extrabold text-gray-900 mb-6" style="font-family:'Inter',sans-serif;">Pembayaran</h2>

        <div class="faq-item bg-white border border-gray-200 rounded-none mb-4 overflow-hidden">
            <button class="faq-btn w-full flex justify-between items-center p-6 text-left">
                <span class="faq-title font-bold text-gray-900 text-[15px]">Metode pembayaran apa saja yang didukung?</span>
                <svg class="faq-icon w-5 h-5 text-gray-500 flex-shrink-0 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div class="faq-content max-h-0">
                <div class="px-6 pb-6 text-sm text-gray-600 leading-relaxed">
                    Kami mendukung dua metode pembayaran aman: <strong>Transfer Bank</strong> (BCA, Mandiri, BRI) dan <strong>QRIS</strong> (bisa di-scan menggunakan aplikasi dompet digital atau mobile banking apa saja).
                </div>
            </div>
        </div>

        <div class="faq-item bg-white border border-gray-200 rounded-none mb-4 overflow-hidden">
            <button class="faq-btn w-full flex justify-between items-center p-6 text-left">
                <span class="faq-title font-bold text-gray-900 text-[15px]">Mengapa ada nominal "Angka Unik" pada total tagihan transfer?</span>
                <svg class="faq-icon w-5 h-5 text-gray-500 flex-shrink-0 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div class="faq-content max-h-0">
                <div class="px-6 pb-6 text-sm text-gray-600 leading-relaxed">
                    Sistem kami secara otomatis menambahkan 3 digit angka acak (misal: Rp159.<strong>123</strong>) di akhir total tagihan. Angka ini berfungsi sebagai <strong>kode pengenal otomatis</strong> transaksi kamu. Mohon pastikan transfer <strong>tepat sesuai nominal hingga digit terakhir</strong> agar verifikasi berjalan instan.
                </div>
            </div>
        </div>

        <div class="faq-item bg-white border border-gray-200 rounded-none mb-12 overflow-hidden">
            <button class="faq-btn w-full flex justify-between items-center p-6 text-left">
                <span class="faq-title font-bold text-gray-900 text-[15px]">Berapa lama proses verifikasi pembayaran saya?</span>
                <svg class="faq-icon w-5 h-5 text-gray-500 flex-shrink-0 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div class="faq-content max-h-0">
                <div class="px-6 pb-6 text-sm text-gray-600 leading-relaxed">
                    Tim admin TANKEN akan memeriksa dan memvalidasi bukti pembayaranmu dalam waktu maksimal <strong>1x24 jam</strong> setelah diunggah. Status pesanan akan otomatis berubah begitu pembayaran dinyatakan valid.
                </div>
            </div>
        </div>


        {{-- KATEGORI 3 --}}
        <h2 class="text-xl md:text-2xl font-extrabold text-gray-900 mb-6" style="font-family:'Inter',sans-serif;">Pembatalan & Retur</h2>

        <div class="faq-item bg-white border border-gray-200 rounded-none mb-4 overflow-hidden">
            <button class="faq-btn w-full flex justify-between items-center p-6 text-left">
                <span class="faq-title font-bold text-gray-900 text-[15px]">Apakah saya bisa membatalkan pesanan yang sudah dibuat?</span>
                <svg class="faq-icon w-5 h-5 text-gray-500 flex-shrink-0 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div class="faq-content max-h-0">
                <div class="px-6 pb-6 text-sm text-gray-600 leading-relaxed">
                    Kamu <strong>bisa</strong> membatalkan pesanan secara mandiri selama statusnya masih <strong>"Menunggu Verifikasi"</strong> (tombol "Batalkan" berwarna merah). Jika pesanan sudah dikonfirmasi, pesanan tidak dapat dibatalkan lagi.
                </div>
            </div>
        </div>

        <div class="faq-item bg-white border border-gray-200 rounded-none mb-12 overflow-hidden">
            <button class="faq-btn w-full flex justify-between items-center p-6 text-left">
                <span class="faq-title font-bold text-gray-900 text-[15px]">Bagaimana jika pakaian yang diterima rusak atau cacat?</span>
                <svg class="faq-icon w-5 h-5 text-gray-500 flex-shrink-0 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div class="faq-content max-h-0">
                <div class="px-6 pb-6 text-sm text-gray-600 leading-relaxed">
                    Kamu bisa mengajukan retur dengan syarat status pesanan sudah <strong>"Selesai/Diterima"</strong>. Wajib menyertakan <strong>video unboxing utuh tanpa terpotong (no-cut)</strong> dan memastikan tag baju belum dilepas. Silakan isi form retur di web atau hubungi WhatsApp Admin.
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ===== 3. BOTTOM CTA (Kotak Sudut Tajam) ===== --}}
<div class="bg-white pb-16">
    <div class="max-w-4xl mx-auto px-6 lg:px-10">
        <div class="bg-[#0f0f0f] rounded-none p-10 md:p-14 flex flex-col items-center text-center">
            
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.2" class="w-14 h-14 mb-5 opacity-90">
                <circle cx="12" cy="12" r="10" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3m.01 4v.01" />
            </svg>

            <h2 class="text-2xl md:text-3xl font-extrabold text-white mb-4" style="font-family:'Inter',sans-serif;">Masih Butuh Bantuan?</h2>
            
            <p class="text-gray-400 text-sm md:text-base mb-8 max-w-lg leading-relaxed">
                Tidak dapat menemukan jawaban yang Anda cari? Tim layanan pelanggan kami selalu siap membantu Anda.
            </p>
            
            {{-- Tombol WhatsApp --}}
            <a href="https://wa.me/6285121235200?text=Halo%20admin%20tanken%2C%20saya%20ingin%20bertanya%20terkait..." 
               target="_blank" 
               class="bg-white text-black text-sm font-bold tracking-wide px-8 py-3.5 rounded-none hover:bg-gray-200 transition-colors">
                Hubungi via WhatsApp
            </a>
            
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.faq-btn').forEach(button => {
        button.addEventListener('click', () => {
            const content = button.nextElementSibling;
            const icon = button.querySelector('.faq-icon');
            
            document.querySelectorAll('.faq-content').forEach(otherContent => {
                if (otherContent !== content) {
                    otherContent.style.maxHeight = null;
                    otherContent.previousElementSibling.querySelector('.faq-icon').style.transform = 'rotate(0deg)';
                }
            });

            if (content.style.maxHeight) {
                content.style.maxHeight = null;
                icon.style.transform = 'rotate(0deg)';
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
                icon.style.transform = 'rotate(180deg)';
            }
        });
    });
</script>
@endpush