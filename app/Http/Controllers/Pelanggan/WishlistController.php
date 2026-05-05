<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    // Halaman wishlist
    public function index()
    {
        $wishlists = auth()->user()->wishlists()->with('product')->get();
        return view('pelanggan.profil-wishlist', compact('wishlists'));
    }

    // Tambah ke wishlist (dari katalog/detail)
    public function store(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        Wishlist::firstOrCreate([
            'user_id'    => auth()->id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json(['status' => 'added']);
    }

    // Hapus dari wishlist
    public function destroy($productId)
    {
        Wishlist::where('user_id', auth()->id())
                ->where('product_id', $productId)
                ->delete();

        return response()->json(['status' => 'removed']);
    }

    // Toggle (klik ikon hati — add kalau belum ada, hapus kalau sudah)
    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $existing = Wishlist::where('user_id', auth()->id())
                            ->where('product_id', $request->product_id)
                            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed']);
        }

        Wishlist::create([
            'user_id'    => auth()->id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json(['status' => 'added']);
    }
}