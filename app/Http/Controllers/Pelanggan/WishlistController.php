<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
   public function index()
    {
        $wishlists = auth()->user()->wishlists()->with('product')->get()
            ->each(function ($item) {
                $colors = is_array($item->product->colors)
                    ? $item->product->colors
                    : (json_decode($item->product->colors, true) ?? []);

                $item->product->colors_hex = collect($colors)->map(fn($c) => [
                    'name' => $c,
                    'hex'  => $this->colorToHex($c),
                ])->toArray();
            });

        return view('pelanggan.profil-wishlist', compact('wishlists'));
    }

    private function colorToHex(?string $color): string
    {
        $map = [
            'black'       => '#111111',
            'hitam'       => '#111111',
            'white'       => '#ffffff',
            'putih'       => '#ffffff',
            'navy'        => '#1e3a5f',
            'grey'        => '#9ca3af',
            'gray'        => '#9ca3af',
            'abu'         => '#9ca3af',
            'olive'       => '#6b7c3f',
            'green'       => '#16a34a',
            'hijau'       => '#16a34a',
            'blue'        => '#2563eb',
            'biru'        => '#2563eb',
            'red'         => '#dc2626',
            'merah'       => '#dc2626',
            'brown'       => '#92400e',
            'coklat'      => '#92400e',
            'cream'       => '#fef3c7',
            'indigo'      => '#4338ca',
            'stone'       => '#78716c',
            'stone grey'  => '#78716c',
            'petrol'      => '#1b4f5a',
            'pink fusia'  => '#e91e8c',
            'fusia'       => '#e91e8c',
            'sage'        => '#8a9e7b',
            'maroon'      => '#6b1f2a',
            'army'        => '#4a5240',
        ];

        if (!$color) return '#9ca3af';

        $key = strtolower(trim($color));
        return $map[$key] ?? '#9ca3af';
    }

    public function store(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        Wishlist::firstOrCreate([
            'user_id'    => auth()->id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json(['status' => 'added']);
    }

    public function destroy($productId)
    {
        Wishlist::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->delete();

        return response()->json(['status' => 'removed']);
    }

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
