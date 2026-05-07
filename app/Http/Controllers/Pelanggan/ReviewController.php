<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Tampilkan form ulasan
    public function create(Request $request)
    {
        $orderId = $request->query('order_id');
        $order   = Order::with('items.product')
                        ->where('id', $orderId)
                        ->where('user_id', Auth::id())
                        ->where('status', 'delivered')
                        ->firstOrFail();

        // Cek produk mana yang belum diulas
        $reviewedProductIds = Review::where('user_id', Auth::id())
            ->whereIn('product_id', $order->items->pluck('product_id'))
            ->pluck('product_id')
            ->toArray();

        return view('pelanggan.ulasan', compact('order', 'reviewedProductIds'));
    }

    // Simpan ulasan
    public function store(Request $request)
    {
        $request->validate([
            'order_id'   => 'required|exists:orders,id',
            'reviews'    => 'required|array',
            'reviews.*.product_id' => 'required|exists:products,id',
            'reviews.*.rating'     => 'required|integer|min:1|max:5',
            'reviews.*.comment'    => 'nullable|string|max:500',
        ]);

        $order = Order::where('id', $request->order_id)
                      ->where('user_id', Auth::id())
                      ->where('status', 'delivered')
                      ->firstOrFail();

        foreach ($request->reviews as $reviewData) {
            // Cegah duplikat ulasan
            $exists = Review::where('user_id', Auth::id())
                            ->where('product_id', $reviewData['product_id'])
                            ->exists();
            if ($exists) continue;

            Review::create([
                'product_id'  => $reviewData['product_id'],
                'user_id'     => Auth::id(),
                'rating'      => $reviewData['rating'],
                'comment'     => $reviewData['comment'] ?? null,
                'is_approved' => false,
            ]);
        }

        return redirect()->route('pelanggan.profil-order')
                         ->with('success', 'Ulasan berhasil dikirim! Terima kasih.');
    }
}