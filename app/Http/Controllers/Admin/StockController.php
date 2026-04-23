<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'stocks');

        if ($search = $request->search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        $allProducts = Product::with('stocks')->get();
        $totalProducts = $allProducts->count();
        $lowStockItems = 0;
        $outOfStock = 0;
        $totalUnits = 0;

        foreach ($allProducts as $prod) {
            $stock = $prod->total_stock; // Total semua variasi
            $totalUnits += $stock;
            
            if ($stock <= 0) {
                $outOfStock++;
            } elseif ($stock < 20) { 
                $lowStockItems++;
            }
        }

        return view('admin.stock', compact(
            'products', 'totalProducts', 'lowStockItems', 'outOfStock', 'totalUnits'
        ));
    }

    public function update(Request $request, $id)
    {
        // Validasi: kita akan menerima array data stok dari modal
        $request->validate([
            'variations' => 'required|array',
            'variations.*.id' => 'required|exists:product_stocks,id',
            'variations.*.quantity' => 'required|integer|min:0',
        ]);

        // Looping untuk update setiap variasi yang dikirim dari modal
        foreach ($request->variations as $varData) {
            ProductStock::where('id', $varData['id'])
                        ->where('product_id', $id) // Keamanan ekstra
                        ->update(['quantity' => $varData['quantity']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Stok variasi berhasil diperbarui'
        ]);
    }
}