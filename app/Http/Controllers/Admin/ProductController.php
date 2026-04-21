<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductStock;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $query = Product::with('category', 'stocks');

        if ($search = $request->search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.products', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'type'           => 'required|in:panjang,pendek',
            'price'          => 'required|integer|min:0',
            'sku'            => 'required|string|unique:products,sku',
            'main_image'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('main_image')) {
            $validated['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        $validated['slug'] = Str::slug($validated['name']);
        $product = Product::create($validated);

        ActivityLog::log('Produk ditambahkan', $product->name, 'success');

        return redirect()->route('admin.products.index')->with('success', "Produk berhasil ditambahkan.");
    }

    public function show(Product $product)
    {
        $product->load('category', 'stocks', 'reviews.user');
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku'  => 'required|string|unique:products,sku,' . $product->id,
        ]);

        $product->update($validated);
        ActivityLog::log('Produk diupdate', $product->name, 'info');

        return redirect()->route('admin.products.index')->with('success', "Produk berhasil diupdate.");
    }

    public function destroy(Product $product)
    {
        $name = $product->name;
        $product->delete();
        ActivityLog::log('Produk dihapus', $name, 'danger');
        return redirect()->route('admin.products.index')->with('success', "Produk berhasil dihapus.");
    }
}