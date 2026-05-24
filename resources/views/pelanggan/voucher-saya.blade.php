@extends('layouts.akun-pelanggan')

@section('title', 'Voucher Saya — TANKEN')

@section('akun-content')
<div>
    <p class="text-[10px] sm:text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Promo</p>
    <h2 class="text-lg sm:text-xl font-extrabold text-gray-900 mb-6">Voucher Saya</h2>

    @if($userVouchers->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" width="28" height="28" class="text-gray-300">
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-gray-500">Kamu belum punya voucher</p>
            <p class="text-xs text-gray-400 mt-1">Voucher akan muncul di sini setelah diklaim atau diberikan</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($userVouchers as $uv)
            @php
                $v          = $uv->voucher;
                $isExpired  = $v->expires_at && now()->gt($v->expires_at);
                $isUsed     = $uv->is_used;
                $isInactive = !$v->is_active;

                if ($isUsed) {
                    $badgeClass = 'bg-gray-100 text-gray-400';
                    $badgeLabel = 'Sudah Dipakai';
                    $cardOpacity = 'opacity-60';
                } elseif ($isExpired || $isInactive) {
                    $badgeClass = 'bg-red-50 text-red-400';
                    $badgeLabel = $isExpired ? 'Expired' : 'Nonaktif';
                    $cardOpacity = 'opacity-60';
                } else {
                    $badgeClass = 'bg-green-50 text-green-600';
                    $badgeLabel = 'Aktif';
                    $cardOpacity = '';
                }

                $valStr = $v->type === 'fixed'
                    ? 'Rp ' . number_format($v->value, 0, ',', '.')
                    : $v->value . '%';
            @endphp

            <div class="relative border border-gray-200 rounded-xl overflow-hidden {{ $cardOpacity }}">
                {{-- Left accent bar --}}
                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $isUsed || $isExpired || $isInactive ? 'bg-gray-200' : 'bg-[#111]' }}"></div>

                <div class="pl-5 pr-4 py-5">
                    {{-- Header --}}
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div>
                            <p class="font-mono font-extrabold text-lg text-gray-900 tracking-wider leading-none">{{ $v->code }}</p>
                            @if($v->description)
                                <p class="text-xs text-gray-400 mt-1">{{ $v->description }}</p>
                            @endif
                        </div>
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full flex-shrink-0 {{ $badgeClass }}">
                            {{ $badgeLabel }}
                        </span>
                    </div>

                    {{-- Diskon value --}}
                    <div class="flex items-baseline gap-1 mb-3">
                        <span class="text-2xl font-extrabold text-gray-900">{{ $valStr }}</span>
                        <span class="text-xs text-gray-400 font-medium">
                            {{ $v->type === 'percentage' ? 'diskon' : 'potongan harga' }}
                        </span>
                    </div>

                    {{-- Divider titik-titik --}}
                    <div class="border-t border-dashed border-gray-200 my-3"></div>

                    {{-- Info bawah --}}
                    <div class="flex flex-wrap gap-x-4 gap-y-1.5 text-xs text-gray-500">
                        @if($v->min_purchase > 0)
                        <div class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="11" height="11"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z"/><path d="M12 8v4l3 3"/></svg>
                            Min. Rp {{ number_format($v->min_purchase, 0, ',', '.') }}
                        </div>
                        @endif

                        @if($v->expires_at)
                        <div class="flex items-center gap-1 {{ $isExpired ? 'text-red-400 font-semibold' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="11" height="11"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            {{ $isExpired ? 'Expired' : 'Berlaku hingga' }} {{ $v->expires_at->format('d M Y') }}
                        </div>
                        @else
                        <div class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="11" height="11"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z"/></svg>
                            Tanpa batas waktu
                        </div>
                        @endif

                        @if($isUsed && $uv->used_at)
                        <div class="flex items-center gap-1 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="11" height="11"><path d="M20 6L9 17l-5-5"/></svg>
                            Dipakai {{ $uv->used_at->format('d M Y') }}
                        </div>
                        @endif
                    </div>

                    {{-- Copy code button (hanya jika masih aktif) --}}
                    @if(!$isUsed && !$isExpired && $v->is_active)
                    <button onclick="copyCode('{{ $v->code }}', this)"
                        class="mt-4 w-full py-2 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-colors flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="13" height="13"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Salin Kode
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('akun-scripts')
<script>
function copyCode(code, btn) {
    navigator.clipboard.writeText(code).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="13" height="13"><path d="M20 6L9 17l-5-5"/></svg> Tersalin!`;
        btn.classList.add('text-green-600', 'border-green-200', 'bg-green-50');
        setTimeout(() => {
            btn.innerHTML = original;
            btn.classList.remove('text-green-600', 'border-green-200', 'bg-green-50');
        }, 2000);
    });
}
</script>
@endpush