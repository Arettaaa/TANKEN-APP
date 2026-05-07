@extends('layouts.akun-pelanggan')

@section('title', 'Beri Ulasan — TANKEN')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@push('akun-styles')
    .star-btn { cursor: pointer; transition: color 0.15s; }
    .star-btn:hover ~ .star-btn { color: #d1d5db !important; }
@endpush

@section('akun-content')
<div>
    {{-- Header --}}
    <p class="text-[10px] sm:text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Pesanan Saya</p>
    <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 mb-1">Beri Ulasan</h2>
    <p class="text-sm text-gray-500 mb-8">
        Pesanan <span class="font-bold text-gray-700">{{ $order->order_number }}</span>
    </p>

    <form action="{{ route('pelanggan.ulasan.store') }}" method="POST">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">

        <div class="flex flex-col gap-5">
            @foreach($order->items as $item)
            @php $alreadyReviewed = in_array($item->product_id, $reviewedProductIds); @endphp

            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                {{-- Info Produk --}}
                <div class="flex items-center gap-4 p-5 border-b border-gray-100">
                    <div class="w-14 h-14 rounded-md bg-gray-100 border border-gray-200 flex-shrink-0 overflow-hidden">
                        @if($item->product && $item->product->main_image)
                            <img src="{{ asset('storage/' . $item->product->main_image) }}"
                                 alt="{{ $item->product_name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fa-solid fa-shirt text-gray-300 text-xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm text-gray-900 truncate">{{ $item->product_name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Ukuran: {{ $item->size ?? '-' }} &nbsp;·&nbsp; Qty: {{ $item->quantity }}
                        </p>
                    </div>
                    @if($alreadyReviewed)
                        <span class="text-[10px] font-bold tracking-widest uppercase bg-emerald-50 text-emerald-700 border border-emerald-100 px-2.5 py-1 rounded-full flex-shrink-0">
                            Sudah Diulas
                        </span>
                    @endif
                </div>

                {{-- Form Ulasan --}}
                @if(!$alreadyReviewed)
                <div class="p-5">
                    <input type="hidden" name="reviews[{{ $loop->index }}][product_id]" value="{{ $item->product_id }}">

                    {{-- Rating Bintang --}}
                    <div class="mb-4">
                        <label class="text-[10px] font-bold tracking-widest uppercase text-gray-400 block mb-2">Rating</label>
                        <div class="flex items-center gap-1.5 flex-row-reverse justify-end" id="stars-{{ $loop->index }}">
                            @for($s = 5; $s >= 1; $s--)
                            <label class="cursor-pointer">
                                <input type="radio"
                                       name="reviews[{{ $loop->index }}][rating]"
                                       value="{{ $s }}"
                                       class="hidden peer"
                                       {{ $s === 5 ? 'required' : '' }}>
                                <i class="fa-solid fa-star text-2xl text-gray-200
                                   peer-checked:text-yellow-400
                                   transition-colors
                                   hover:text-yellow-400
                                   [&~label_i]:hover:text-yellow-400"
                                   data-index="{{ $loop->index }}"
                                   data-star="{{ $s }}"></i>
                            </label>
                            @endfor
                        </div>
                        @error('reviews.'.$loop->index.'.rating')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Komentar --}}
                    <div>
                        <label class="text-[10px] font-bold tracking-widest uppercase text-gray-400 block mb-2">
                            Komentar <span class="font-normal text-gray-300">(opsional)</span>
                        </label>
                        <textarea
                            name="reviews[{{ $loop->index }}][comment]"
                            rows="3"
                            maxlength="500"
                            placeholder="Ceritakan pengalamanmu dengan produk ini..."
                            class="w-full border border-gray-200 rounded-md px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-gray-900 resize-none transition-colors placeholder:text-gray-300"></textarea>
                        <p class="text-[10px] text-gray-300 text-right mt-1">Maks. 500 karakter</p>
                    </div>
                </div>
                @else
                <div class="px-5 py-4 bg-gray-50/50">
                    @php
                        $existingReview = \App\Models\Review::where('user_id', auth()->id())
                            ->where('product_id', $item->product_id)
                            ->first();
                    @endphp
                    @if($existingReview)
                    <div class="flex items-center gap-1 mb-1">
                        @for($s = 1; $s <= 5; $s++)
                            <i class="fa-solid fa-star text-sm {{ $s <= $existingReview->rating ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                        @endfor
                    </div>
                    @if($existingReview->comment)
                        <p class="text-sm text-gray-500 italic">"{{ $existingReview->comment }}"</p>
                    @else
                        <p class="text-xs text-gray-400">Tidak ada komentar.</p>
                    @endif
                    <p class="text-[10px] text-gray-300 mt-1.5">
                        {{ $existingReview->is_approved ? 'Ditampilkan di halaman produk' : 'Sedang menunggu persetujuan admin' }}
                    </p>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Tombol Submit --}}
        @if(count($reviewedProductIds) < $order->items->count())
        <div class="flex flex-col sm:flex-row gap-3 mt-6">
            <a href="{{ route('pelanggan.profil-order') }}"
               class="w-full sm:w-auto flex-1 text-center border border-gray-200 text-gray-600 text-xs font-bold tracking-widest uppercase py-3.5 rounded-md hover:bg-gray-50 transition-colors">
                Kembali
            </a>
            <button type="submit"
               class="w-full sm:w-auto flex-1 bg-black text-white text-xs font-bold tracking-widest uppercase py-3.5 rounded-md hover:bg-gray-800 transition-colors">
                Kirim Ulasan
            </button>
        </div>
        @else
        <div class="mt-6 text-center">
            <a href="{{ route('pelanggan.profil-order') }}"
               class="inline-flex items-center gap-2 border border-gray-200 text-gray-600 text-xs font-bold tracking-widest uppercase px-6 py-3.5 rounded-md hover:bg-gray-50 transition-colors">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                Kembali ke Pesanan
            </a>
        </div>
        @endif
    </form>
</div>
@endsection

@push('akun-scripts')
<script>
// Star rating interactive highlight
document.querySelectorAll('[id^="stars-"]').forEach(container => {
    const labels = [...container.querySelectorAll('label')].reverse(); // reverse karena flex-row-reverse
    labels.forEach((label, i) => {
        const icon = label.querySelector('i');
        const input = label.querySelector('input');

        label.addEventListener('mouseenter', () => {
            labels.forEach((l, j) => {
                l.querySelector('i').classList.toggle('text-yellow-400', j <= i);
                l.querySelector('i').classList.toggle('text-gray-200', j > i);
            });
        });

        container.addEventListener('mouseleave', () => {
            const checked = container.querySelector('input:checked');
            const checkedVal = checked ? parseInt(checked.value) - 1 : -1;
            labels.forEach((l, j) => {
                l.querySelector('i').classList.toggle('text-yellow-400', j <= checkedVal);
                l.querySelector('i').classList.toggle('text-gray-200', j > checkedVal);
            });
        });

        input.addEventListener('change', () => {
            const val = parseInt(input.value) - 1;
            labels.forEach((l, j) => {
                l.querySelector('i').classList.toggle('text-yellow-400', j <= val);
                l.querySelector('i').classList.toggle('text-gray-200', j > val);
            });
        });
    });
});
</script>
@endpush