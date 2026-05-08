<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review; 

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $total     = $reviews->count();
        $approved  = $reviews->where('status', 'approved')->count();
        $pending   = $reviews->where('status', 'pending')->count();
        $avgRating = $total > 0 ? number_format($reviews->avg('rating'), 1) : '0.0';

        return view('admin.reviews', compact('reviews', 'total', 'approved', 'pending', 'avgRating'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,pending,rejected'
        ]);

        $review = Review::findOrFail($id); // ✅ ganti ProductReview → Review
        $review->update(['status' => $request->status]);

        return response()->json(['message' => 'Status review berhasil diubah']);
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id); // ✅ ganti ProductReview → Review
        $review->delete();

        return response()->json(['message' => 'Review berhasil dihapus']);
    }
}