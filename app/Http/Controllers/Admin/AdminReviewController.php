<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class AdminReviewController extends Controller
{
    public function index(){
        $pendingReviews = Review::query()->where('status', 'pending')
        ->with(['business', 'images'])
        ->latest()
        ->get();

        return view('admin.reviews.index', compact(
            'pendingReviews'
        ));
    }

    public function approve(Review $review)
    {
    $review->update([
        'status' => 'approved'
    ]);

    return back()->with('success', 'Review approved successfully.');
    }

    public function reject(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review rejected.');
    }
}
