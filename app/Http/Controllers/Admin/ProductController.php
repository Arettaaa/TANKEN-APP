<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductStock;
use App\Models\ActivityLog;
use App\Models\ProductGallery; 
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $query = Product::with(['category', 'stocks', 'galleries', 'reviews']);

        // Search (sudah ada)
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // ✅ TAMBAH INI: Filter category
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        // ✅ TAMBAH INI: Filter type (pendek/panjang)
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // ✅ TAMBAH INI: Sort
        match ($request->sort) {
            'price-asc'   => $query->orderBy('price', 'asc'),
            'price-desc'  => $query->orderBy('price', 'desc'),
            'stock-asc'  => $query->withSum('stocks', 'quantity')->orderBy('stocks_sum_quantity', 'asc'),
            'stock-desc' => $query->withSum('stocks', 'quantity')->orderBy('stocks_sum_quantity', 'desc'),
            default       => $query->latest(),
        };

        $products = $query->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.products', compact('products', 'categories'));
    }

    public function exportExcel(Request $request)
    {
        $products = Product::with('stocks')
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->get();

        $filename = "Tanken_Products_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Nama', 'SKU', 'Tipe', 'Harga', 'Stok', 'Status']);
            foreach ($products as $i => $p) {
                fputcsv($file, [
                    $i + 1,
                    $p->name,
                    $p->sku,
                    $p->type,
                    $p->price,
                    $p->stocks->sum('quantity'),
                    $p->is_active ? 'Aktif' : 'Nonaktif',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // ===== JALAN NINJA: CEGAT & UBAH DATA SEBELUM VALIDASI =====
        if ($request->has('colors') && is_string($request->colors)) {
            $cleanColors = str_replace(['[', ']', '"'], '', $request->colors);
            $request->merge(['colors' => array_filter(array_map('trim', explode(',', $cleanColors)))]);
        }
        if ($request->has('sizes') && is_string($request->sizes)) {
            $cleanSizes = str_replace(['[', ']', '"'], '', $request->sizes);
            $request->merge(['sizes' => array_filter(array_map('trim', explode(',', $cleanSizes)))]);
        }
        // ============================================================

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'category_id'      => 'required|exists:categories,id',
            'type'             => 'required|in:panjang,pendek',
            'price'            => 'required|integer|min:0',
            'sku'              => 'required|string|unique:products,sku',
            'description'      => 'nullable|string',
            'main_image'       => 'nullable|image|max:2048',
            'size_chart_image' => 'nullable|image|max:2048',
            'colors'           => 'nullable|array',
            'sizes'            => 'nullable|array',
            'initial_stock'    => 'nullable|integer|min:0',
            'additional_images.*' => 'nullable|image|max:2048', // TAMBAHAN: Validasi foto multiple
        ]);

        // Upload Foto Utama
        if ($request->hasFile('main_image')) {
            $validated['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        // Upload Size Chart
        if ($request->hasFile('size_chart_image')) {
            $validated['size_chart_image'] = $request->file('size_chart_image')->store('products/size_charts', 'public');
        }

        $validated['slug'] = Str::slug($validated['name']);
        $colors = $request->colors ?? ['Default'];
        $sizes = $request->sizes ?? ['All Size'];
        $initialStock = $request->initial_stock ?? 20;

        $validated['colors'] = $colors;
        $validated['sizes'] = $sizes;

        $product = Product::create($validated);

        // TAMBAHAN: Logic untuk menyimpan multiple images ke tabel product_galleries
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $path = $image->store('products/galleries', 'public');
                ProductGallery::create([
                    'product_id' => $product->id,
                    'image'      => $path
                ]);
            }
        }

        $stockData = [];
        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                if (!empty($color) && !empty($size)) {
                    $stockData[] = [
                        'product_id' => $product->id,
                        'color'      => $color,
                        'size'       => $size,
                        'quantity'   => $initialStock,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        if (!empty($stockData)) {
            ProductStock::insert($stockData);
        }

        ActivityLog::log('Produk ditambahkan', $product->name, 'success');

        return redirect()->route('admin.products.index')->with('success', "Produk beserta variasi stok awal berhasil ditambahkan.");
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
        // ===== JALAN NINJA (Sama seperti store) =====
        if ($request->has('colors') && is_string($request->colors)) {
            $cleanColors = str_replace(['[', ']', '"'], '', $request->colors);
            $request->merge(['colors' => array_filter(array_map('trim', explode(',', $cleanColors)))]);
        }
        if ($request->has('sizes') && is_string($request->sizes)) {
            $cleanSizes = str_replace(['[', ']', '"'], '', $request->sizes);
            $request->merge(['sizes' => array_filter(array_map('trim', explode(',', $cleanSizes)))]);
        }

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'category_id'      => 'required|exists:categories,id',
            'type'             => 'required|in:panjang,pendek',
            'price'            => 'required|integer|min:0',
            'sku'              => 'required|string|unique:products,sku,' . $product->id,
            'description'      => 'nullable|string',
            'main_image'       => 'nullable|image|max:2048',
            'size_chart_image' => 'nullable|image|max:2048',
            'colors'           => 'nullable|array',
            'sizes'            => 'nullable|array',
            'additional_images.*' => 'nullable|image|max:2048', // TAMBAHAN: Validasi foto multiple
        ]);

        // Replace Foto Utama jika ada yang baru
        if ($request->hasFile('main_image')) {
            if ($product->main_image) Storage::disk('public')->delete($product->main_image);
            $validated['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        // Replace Size Chart jika ada yang baru
        if ($request->hasFile('size_chart_image')) {
            if ($product->size_chart_image) Storage::disk('public')->delete($product->size_chart_image);
            $validated['size_chart_image'] = $request->file('size_chart_image')->store('products/size_charts', 'public');
        }

        $colors = $request->colors ?? [];
        $sizes = $request->sizes ?? [];
        $validated['colors'] = $colors;
        $validated['sizes'] = $sizes;

        // 1. Update data produk di tabel products
        $product->update($validated);

        // TAMBAHAN: Logic untuk menyimpan tambahan multiple images saat proses Edit
        // (Sistemnya menambahkan foto baru ke galeri yang sudah ada)
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $path = $image->store('products/galleries', 'public');
                ProductGallery::create([
                    'product_id' => $product->id,
                    'image'      => $path
                ]);
            }
        }

        // 2. LOGIKA UPDATE VARIASI STOK
        // Hapus variasi yang warnanya/ukurannya sudah tidak dicentang lagi
        if (!empty($colors) && !empty($sizes)) {
            ProductStock::where('product_id', $product->id)
                ->where(function ($query) use ($colors, $sizes) {
                    $query->whereNotIn('color', $colors)
                        ->orWhereNotIn('size', $sizes);
                })->delete();
        } else {
            ProductStock::where('product_id', $product->id)->delete();
        }

        // Tambahkan variasi baru yang baru saja dicentang (dengan stok default 0)
        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                if (!empty($color) && !empty($size)) {
                    // firstOrCreate: Kalau datanya sudah ada, biarkan. Kalau belum ada, buat baru dengan stok 0
                    ProductStock::firstOrCreate(
                        ['product_id' => $product->id, 'color' => $color, 'size' => $size],
                        ['quantity' => 0]
                    );
                }
            }
        }

        ActivityLog::log('Produk diupdate', $product->name, 'info');

        return redirect()->route('admin.products.index')->with('success', "Produk beserta variasinya berhasil diperbarui.");
    }

    public function destroy(Product $product)
    {
        $name = $product->name;

        // TAMBAHAN: Hapus file fisik galeri dari storage sebelum datanya dihapus dari DB
        if ($product->galleries) {
            foreach ($product->galleries as $gallery) {
                Storage::disk('public')->delete($gallery->image);
            }
        }

        // Ini otomatis akan menghapus stoknya juga karena di migration pakai ->onDelete('cascade')
        $product->delete();
        ActivityLog::log('Produk dihapus', $name, 'danger');
        return redirect()->route('admin.products.index')->with('success', "Produk berhasil dihapus.");
    }
}
