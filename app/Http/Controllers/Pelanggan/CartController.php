<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function index()
    {
        $cartItems = CartItem::where('user_id', auth()->id())
            ->with('product')
            ->get()
            ->map(function ($item) {
                return [
                    'id'        => $item->id,
                    'name'      => $item->product->name,
                    'image'     => $item->product->main_image
                        ? asset('storage/' . $item->product->main_image)
                        : null,
                    'size'      => $item->size,
                    'color'     => $item->color,
                    'price'     => $item->product->price,
                    'qty'       => $item->quantity,
                    'checked'   => true,
                ];
            });

        $userVouchers = auth()->user()
            ->userVouchers()
            ->with('voucher')
            ->where('is_used', false)
            ->whereHas(
                'voucher',
                fn($q) => $q->where('is_active', true)
                    ->where(fn($q2) => $q2->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            )
            ->get();

        return view('pelanggan.keranjang', compact('cartItems', 'userVouchers'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'color'      => 'nullable|string|max:50',
            'size'       => 'required|string|max:10',
            'quantity'   => 'required|integer|min:1|max:99',
        ]);

        $userId = auth()->id();

        $existing = CartItem::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->where('color', $request->color)
            ->where('size', $request->size)
            ->first();

        if ($existing) {
            $newQty = min(99, $existing->quantity + $request->quantity);
            $existing->update(['quantity' => $newQty]);
        } else {
            CartItem::create([
                'user_id'    => $userId,
                'product_id' => $request->product_id,
                'color'      => $request->color,
                'size'       => $request->size,
                'quantity'   => $request->quantity,
            ]);
        }

        $cartCount = CartItem::where('user_id', $userId)->count();

        return response()->json([
            'success'    => true,
            'cart_count' => $cartCount,
            'message'    => 'Produk berhasil ditambahkan ke keranjang.',
        ]);
    }


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


    public function destroy($id)
    {
        CartItem::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        return response()->json(['success' => true]);
    }
}
