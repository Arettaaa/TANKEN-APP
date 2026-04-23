<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductReview; // <-- Ini yang ditambahkan untuk memanggil Model

class ReviewController extends Controller
{
    public function index()
    {
        // Menggunakan Eloquent Model (jauh lebih clean!)
        $reviews = ProductReview::orderBy('created_at', 'desc')->get();

        // Hitung statistik
        $total = $reviews->count();
        $approved = $reviews->where('status', 'approved')->count();
        $pending = $reviews->where('status', 'pending')->count();
        $avgRating = $total > 0 ? number_format($reviews->avg('rating'), 1) : '0.0';

        return view('admin.reviews', compact('reviews', 'total', 'approved', 'pending', 'avgRating'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi input status
        $request->validate([
            'status' => 'required|in:approved,pending,rejected'
        ]);

        // Cari review berdasarkan ID, lalu update statusnya
        $review = ProductReview::findOrFail($id);
        $review->update([
            'status' => $request->status
        ]);

        return response()->json(['message' => 'Status review berhasil diubah']);
    }

    public function destroy($id)
    {
        // Cari review berdasarkan ID, lalu hapus
        $review = ProductReview::findOrFail($id);
        $review->delete();

        return response()->json(['message' => 'Review berhasil dihapus']);
    }
}