<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // ─────────────────────────────────────────────────────────
    // GET /keranjang — tampilkan halaman keranjang
    // ─────────────────────────────────────────────────────────
    public function index()
    {
        $cartItems = CartItem::where('user_id', auth()->id())
            ->with('product')
            ->get()
            ->map(function ($item) {
                return [
                    'id'        => $item->id,
                    'name'      => $item->product->name,
                    'image' => $item->product->main_image
                        ? asset('storage/' . $item->product->main_image)
                        : null,
                    'size'      => $item->size,
                    'color'     => $item->color,
                    'color_hex' => $this->colorToHex($item->color),
                    'price'     => $item->product->price,
                    'qty'       => $item->quantity,
                    'checked'   => true,
                ];
            });

        return view('pelanggan.keranjang', compact('cartItems'));
    }

    // POST /keranjang — tambah item ke keranjang
    // Kalau kombinasi (product + color + size) sudah ada → tambah qty
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'color'      => 'nullable|string|max:50',
            'size'       => 'required|string|max:10',
            'quantity'   => 'required|integer|min:1|max:99',
        ]);

        $userId = auth()->id();

        // Cari item yang sudah ada dengan kombinasi yang persis sama
        $existing = CartItem::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->where('color', $request->color)
            ->where('size', $request->size)
            ->first();

        if ($existing) {
            // Sudah ada → tambah jumlahnya, tapi jangan lewat 99
            $newQty = min(99, $existing->quantity + $request->quantity);
            $existing->update(['quantity' => $newQty]);
        } else {
            // Belum ada → buat baris baru
            CartItem::create([
                'user_id'    => $userId,
                'product_id' => $request->product_id,
                'color'      => $request->color,
                'size'       => $request->size,
                'quantity'   => $request->quantity,
            ]);
        }

        // Hitung total baris unik untuk badge navbar
        $cartCount = CartItem::where('user_id', $userId)->count();

        return response()->json([
            'success'    => true,
            'cart_count' => $cartCount,
            'message'    => 'Produk berhasil ditambahkan ke keranjang.',
        ]);
    }

    // ─────────────────────────────────────────────────────────
    // PATCH /keranjang/{id} — update qty item
    // ─────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $item = CartItem::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $item->update(['quantity' => $request->quantity]);

        return response()->json(['success' => true]);
    }

    // ─────────────────────────────────────────────────────────
    // DELETE /keranjang/{id} — hapus satu item
    // ─────────────────────────────────────────────────────────
    public function destroy($id)
    {
        CartItem::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        return response()->json(['success' => true]);
    }

    // ─────────────────────────────────────────────────────────
    // Helper: warna nama → hex (opsional, bisa dikembangkan)
    // ─────────────────────────────────────────────────────────
    private function colorToHex(?string $color): string
    {
        $map = [
            'black'  => '#111111',
            'hitam'  => '#111111',
            'white'  => '#ffffff',
            'putih'  => '#ffffff',
            'navy'   => '#1e3a5f',
            'grey'   => '#9ca3af',
            'gray'   => '#9ca3af',
            'abu'    => '#9ca3af',
            'olive'  => '#6b7c3f',
            'green'  => '#16a34a',
            'hijau'  => '#16a34a',
            'blue'   => '#2563eb',
            'biru'   => '#2563eb',
            'red'    => '#dc2626',
            'merah'  => '#dc2626',
            'brown'  => '#92400e',
            'coklat' => '#92400e',
            'cream'  => '#fef3c7',
            'indigo' => '#4338ca',
            'stone'  => '#78716c',
        ];

        if (!$color) return '#9ca3af';

        $key = strtolower(trim($color));
        return $map[$key] ?? '#9ca3af';
    }
}
