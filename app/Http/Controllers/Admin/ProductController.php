<?php

namespace App\Http\Controllers\Admin;
use App\Helpers\ExportHelper;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // Daftar warna yang dikenali sistem (auto-detect dari nama produk)
    private array $knownColors = [
        'Black', 'Stone Grey', 'Indigo', 'Petrol', 'Pink Fusia', 'Sage', 'Olive',
        'White', 'Navy', 'Brown', 'Cream', 'Maroon', 'Army',
    ];

    // Ukuran valid
    private array $validSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

    public function index(Request $request)
    {
        $query = Product::with([
            'stocks',
            'galleries',
            'reviews' => fn($q) => $q->where('status', 'approved'),
        ]);

      if ($search = $request->search) {
    $query->where(function ($q) use ($search) {
        $q->where('name', 'like', "%{$search}%")
          ->orWhere('sku', 'like', "%{$search}%")
          ->orWhere('price', 'like', "%{$search}%")
          ->orWhere('type', 'like', "%{$search}%")
          ->orWhere('description', 'like', "%{$search}%")
          ->orWhere('colors', 'like', "%{$search}%")
          ->orWhere('sizes', 'like', "%{$search}%")
          ->orWhere(function ($q) use ($search) {
              $q->whereIn('id', function ($sub) use ($search) {
                  $sub->select('product_id')
                      ->from('product_stocks')
                      ->groupBy('product_id')
                      ->havingRaw('SUM(quantity) LIKE ?', ["%{$search}%"]);
              });
          })
          ->orWhere(function ($q) use ($search) {
              $q->whereIn('id', function ($sub) use ($search) {
                  $sub->select('product_id')
                      ->from('product_reviews')
                      ->where('status', 'approved')
                      ->groupBy('product_id')
                      ->havingRaw('ROUND(AVG(rating), 1) LIKE ?', ["%{$search}%"]);
              });
          });
    });
}

        if ($category = $request->category) {
            $query->where('category_id', $category);
        }

        if ($type = $request->type) {
            $query->where('type', $type);
        }

        if ($sort = $request->sort) {
            match ($sort) {
                'price-asc'  => $query->orderBy('price', 'asc'),
                'price-desc' => $query->orderBy('price', 'desc'),
                'stock-asc'  => $query->withSum('stocks', 'quantity')->orderBy('stocks_sum_quantity', 'asc'),
                'stock-desc' => $query->withSum('stocks', 'quantity')->orderBy('stocks_sum_quantity', 'desc'),
                default      => $query->latest(),
            };
        } else {
            $query->latest();
        }

        $products = $query->paginate(10);

        return view('admin.products', compact('products'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'price' => preg_replace('/\D/', '', $request->price)
        ]);

        $request->validate([
            'name'        => 'required|string|max:255',
            'sku'         => 'required|string',
            'category_id' => 'required|integer',
            'price'       => 'required|numeric|min:0',
            'type'        => 'required|in:pendek,panjang',
            'description' => 'nullable|string',
            'sizes'       => 'nullable|string',
            'stock_per_size' => 'nullable|array',
            'stock_per_size.*' => 'nullable|integer|min:0',
            'slot_image.*' => 'nullable|image|max:3072',
            'thumbnail_slot' => 'nullable|integer|min:0|max:3',
        ]);

        // --- Auto-detect warna dari nama produk ---
        $colors = $this->detectColors($request->name);

        // --- Parse sizes ---
        $sizes = $request->sizes ? array_filter(explode(',', $request->sizes)) : [];

        // --- Handle foto: 4 slot ---
        $uploadedSlots = $this->handleSlotImages($request, 'slot_image');

        // Tentukan foto utama
        $thumbnailSlot = (int) $request->input('thumbnail_slot', 0);
        $mainImage = $uploadedSlots[$thumbnailSlot] ?? (count($uploadedSlots) ? reset($uploadedSlots) : null);

        // Foto lainnya masuk gallery
        $galleryImages = [];
        foreach ($uploadedSlots as $idx => $path) {
            if ($path && $idx !== $thumbnailSlot) {
                $galleryImages[] = $path;
            }
        }

        // Size chart (slot terpisah jika ada)
        $sizeChartImage = null;
        if ($request->hasFile('size_chart_image')) {
            $sizeChartImage = $request->file('size_chart_image')->store('products/size-charts', 'public');
        }

        $product = Product::create([
            'name'             => $request->name,
            'slug'             => Str::slug($request->name),
            'sku'              => $request->sku,
            'category_id'      => $request->category_id,
            'price'            => $request->price,
            'type'             => $request->type,
            'description'      => $request->description,
            'colors'           => $colors,
            'sizes'            => array_values($sizes),
            'is_active'        => true,
            'main_image'       => $mainImage,
            'size_chart_image' => $sizeChartImage,
        ]);

        // --- Simpan foto gallery ---
        foreach ($galleryImages as $path) {
            ProductGallery::create([
                'product_id' => $product->id,
                'image'      => $path,
            ]);
        }

        // --- Simpan stok per ukuran ---
        $this->syncStockPerSize($product, $sizes, $request->input('stock_per_size', []));

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $request->merge([
            'price' => preg_replace('/\D/', '', $request->price)
        ]);

        $request->validate([
            'name'        => 'required|string|max:255',
            'sku'         => 'required|string',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'sizes'       => 'nullable|string',
            'stock_per_size' => 'nullable|array',
            'stock_per_size.*' => 'nullable|integer|min:0',
            'slot_image.*' => 'nullable|image|max:3072',
            'thumbnail_slot' => 'nullable|integer|min:0|max:3',
        ]);

        // --- Auto-detect warna dari nama produk ---
        $colors = $this->detectColors($request->name);

        // --- Parse sizes ---
        $sizes = $request->sizes ? array_filter(explode(',', $request->sizes)) : [];

        $data = [
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'sku'         => $request->sku,
            'category_id' => $request->category_id,
            'price'       => $request->price,
            'type'        => $request->type,
            'description' => $request->description,
            'colors'      => $colors,
            'sizes'       => array_values($sizes),
        ];

        // --- Handle slot foto ---
        $uploadedSlots = $this->handleSlotImages($request, 'slot_image');

        if (!empty($uploadedSlots)) {
            $thumbnailSlot = (int) $request->input('thumbnail_slot', array_key_first($uploadedSlots));
            $newMain = $uploadedSlots[$thumbnailSlot] ?? reset($uploadedSlots);

            if ($newMain) {
                // Hapus foto utama lama
                if ($product->main_image) {
                    Storage::disk('public')->delete($product->main_image);
                }
                $data['main_image'] = $newMain;
            }

            // Tambahkan foto lain ke gallery
            foreach ($uploadedSlots as $idx => $path) {
                if ($path && $idx !== $thumbnailSlot) {
                    ProductGallery::create([
                        'product_id' => $product->id,
                        'image'      => $path,
                    ]);
                }
            }
        } elseif ($request->filled('thumbnail_gallery_id')) {
            // Admin memilih foto dari gallery yang sudah ada sebagai thumbnail baru
            $galleryItem = ProductGallery::where('product_id', $product->id)
                ->where('id', $request->thumbnail_gallery_id)
                ->first();

            if ($galleryItem) {
                // Tukar: main_image lama masuk gallery, gallery item jadi main
                if ($product->main_image) {
                    ProductGallery::create([
                        'product_id' => $product->id,
                        'image'      => $product->main_image,
                    ]);
                }
                $data['main_image'] = $galleryItem->image;
                $galleryItem->delete();
            }
        }

        if ($request->hasFile('size_chart_image')) {
            $data['size_chart_image'] = $request->file('size_chart_image')
                ->store('products/size-charts', 'public');
        }

        $product->update($data);

        // --- Sync stok per ukuran ---
        $this->syncStockPerSize($product, $sizes, $request->input('stock_per_size', []));

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy(Product $product)
    {
        if ($product->main_image) {
            Storage::disk('public')->delete($product->main_image);
        }
        foreach ($product->galleries as $gallery) {
            Storage::disk('public')->delete($gallery->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function toggleStatus(Request $request, Product $product)
    {
        $product->update(['is_active' => $request->is_active]);
        return response()->json(['success' => true]);
    }

   public function exportExcel(Request $request)
{
    $products = Product::with(['stocks', 'reviews'])->get();
 
    $columns = ['ID', 'Nama', 'SKU', 'Kategori', 'Tipe', 'Harga', 'Total Stok', 'Warna', 'Status'];
 
    $rows = $products->map(fn($p) => [
        $p->id,
        $p->name,
        $p->sku,
        $p->category_id == 1 ? 'Men' : 'Women',
        ucfirst($p->type),
        $p->price,
        $p->stocks->sum('quantity'),
        implode(', ', $p->colors ?? []),
        $p->is_active ? 'Aktif' : 'Nonaktif',
    ]);
 
    return ExportHelper::excel('Tanken_Products', 'Laporan Produk', $columns, $rows);
}
    // =============================================
    // PRIVATE HELPERS
    // =============================================

    /**
     * Auto-detect warna dari nama produk.
     * Format yang dikenali: "Nama Produk - Warna"
     */
    private function detectColors(string $name): array
    {
        if (!str_contains($name, ' - ')) {
            return [];
        }

        // Ambil semua bagian setelah " - " (support multi-dash: "Nama - Sub - Warna")
        $parts = explode(' - ', $name);
        $colorPart = trim(end($parts));

        if (in_array($colorPart, $this->knownColors, true)) {
            return [$colorPart];
        }

        // Case-insensitive fallback
        foreach ($this->knownColors as $known) {
            if (strtolower($colorPart) === strtolower($known)) {
                return [$known];
            }
        }

        // Warna tidak dikenal tapi tetap disimpan (bisa warna custom)
        return [ucwords(strtolower($colorPart))];

    }

    /**
     * Upload slot images (4 slot), return array [slot_index => storage_path]
     */
    private function handleSlotImages(Request $request, string $fieldName): array
    {
        $uploaded = [];
        if (!$request->hasFile($fieldName)) {
            return $uploaded;
        }

        foreach ($request->file($fieldName) as $idx => $file) {
            if ($file && $file->isValid()) {
                $uploaded[$idx] = $file->store('products', 'public');
            }
        }

        return $uploaded;
    }

    /**
     * Sync stok per ukuran ke tabel product_stocks.
     * Hanya update ukuran yang dipilih, hapus ukuran yang tidak lagi dipilih.
     */
    private function syncStockPerSize(Product $product, array $sizes, array $stockPerSize): void
    {
        // Hapus stok untuk size yang tidak lagi ada
        $product->stocks()->whereNotIn('size', $sizes)->delete();

        foreach ($sizes as $size) {
            if (!in_array($size, $this->validSizes, true)) {
                continue;
            }

            $qty = isset($stockPerSize[$size]) ? (int) $stockPerSize[$size] : 0;

            $product->stocks()->updateOrCreate(
                ['size' => $size, 'color' => null],
                ['quantity' => $qty]
            );
        }
    }
}