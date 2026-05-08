<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['stocks', 'galleries', 'reviews' => fn($q) => $q->where('status', 'approved')]);

        if ($search = $request->search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($category = $request->category) {
            $query->where('category_id', $category);
        }

        if ($type = $request->type) {
            $query->where('type', $type);
        }

        if ($sort = $request->sort) {
            match($sort) {
                'price-asc'   => $query->orderBy('price', 'asc'),
                'price-desc'  => $query->orderBy('price', 'desc'),
                'stock-asc'   => $query->orderBy('stock', 'asc'),
                'stock-desc'  => $query->orderBy('stock', 'desc'),
                default       => $query->latest(),
            };
        } else {
            $query->latest();
        }

        $products = $query->paginate(10);

        return view('admin.products', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'sku'           => 'required|string|unique:products,sku',
            'category_id'   => 'required|integer',
            'price'         => 'required|numeric|min:0',
            'type'          => 'required|in:pendek,panjang',
            'initial_stock' => 'required|integer|min:0',
            'main_image'    => 'nullable|image|max:2048',
        ]);

        $sizes  = $request->sizes  ? explode(',', $request->sizes)  : [];
        $colors = $request->colors ? explode(',', $request->colors) : [];

        $data = [
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'sku'         => $request->sku,
            'category_id' => $request->category_id,
            'price'       => $request->price,
            'type'        => $request->type,
            'description' => $request->description,
            'stock'       => $request->initial_stock,
            'sizes'       => $sizes,
            'colors'      => $colors,
            'is_active'   => true,
        ];

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        if ($request->hasFile('size_chart_image')) {
            $data['size_chart_image'] = $request->file('size_chart_image')->store('products/size-charts', 'public');
        }

        $product = Product::create($data);

        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $img) {
                $path = $img->store('products/gallery', 'public');
                ProductGallery::create([
                    'product_id' => $product->id,
                    'image'      => $path,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'sku'        => 'required|string|unique:products,sku,' . $product->id,
            'price'      => 'required|numeric|min:0',
            'main_image' => 'nullable|image|max:2048',
        ]);

        $sizes  = $request->sizes  ? explode(',', $request->sizes)  : [];
        $colors = $request->colors ? explode(',', $request->colors) : [];

        $data = [
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'sku'         => $request->sku,
            'category_id' => $request->category_id,
            'price'       => $request->price,
            'type'        => $request->type,
            'description' => $request->description,
            'sizes'       => $sizes,
            'colors'      => $colors,
        ];

        if ($request->hasFile('main_image')) {
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        if ($request->hasFile('size_chart_image')) {
            $data['size_chart_image'] = $request->file('size_chart_image')->store('products/size-charts', 'public');
        }

        $product->update($data);

        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $img) {
                $path = $img->store('products/gallery', 'public');
                ProductGallery::create([
                    'product_id' => $product->id,
                    'image'      => $path,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy(Product $product)
    {
        if ($product->main_image) {
            Storage::disk('public')->delete($product->main_image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate(['stock' => 'required|integer|min:0']);
        $product->update(['stock' => $request->stock]);
        return response()->json(['success' => true]);
    }

    public function exportExcel(Request $request)
    {
        $products = Product::with(['stocks', 'reviews'])->get();
        $filename = "Tanken_Products_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Nama', 'SKU', 'Kategori', 'Tipe', 'Harga', 'Stok', 'Status'];

        $callback = function() use ($products, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($products as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->name,
                    $p->sku,
                    $p->category_id == 1 ? 'Men' : 'Women',
                    ucfirst($p->type),
                    $p->price,
                    $p->stock,
                    $p->is_active ? 'Aktif' : 'Nonaktif',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function toggleStatus(Request $request, Product $product)
    {
        $product->update(['is_active' => $request->is_active]);
        return response()->json(['success' => true]);
    }
}