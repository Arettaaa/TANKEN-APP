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
        $query = Product::with('stocks', 'category');

        if ($search = $request->search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $products = $query->latest()->paginate(20)->withQueryString();
        return view('admin.stock.index', compact('products'));
    }

    public function update(Request $request, ProductStock $stock)
    {
        $request->validate(['quantity' => 'required|integer|min:0']);
        $stock->update(['quantity' => $request->quantity]);
        return response()->json(['success' => true, 'quantity' => $stock->quantity]);
    }
}