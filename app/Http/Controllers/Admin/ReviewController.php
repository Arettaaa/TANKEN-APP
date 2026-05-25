<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review; 

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $total     = Review::count();
        $approved  = Review::where('status', 'approved')->count();
        $pending   = Review::where('status', 'pending')->count();
        $avgRating = Review::where('status', 'approved')->avg('rating');
        $avgRating = $avgRating ? number_format($avgRating, 1) : '0.0';

        $query = Review::with(['product', 'user']);

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                ->orWhere('rating', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhereHas('product', function ($pQ) use ($search) {
                    $pQ->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function ($uQ) use ($search) {
                    $uQ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        $reviews = $query->orderBy('created_at', 'desc')->get();

        return view('admin.reviews', compact('reviews', 'total', 'approved', 'pending', 'avgRating'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,pending,rejected'
        ]);

        $review = Review::findOrFail($id); 
        $review->update(['status' => $request->status]);

        return response()->json(['message' => 'Status review berhasil diubah']);
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id); 
        $review->delete();

        return response()->json(['message' => 'Review berhasil dihapus']);
    }
}