<?php

namespace App\Http\Controllers\BusinessOwner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\ReviewReply;
use App\Models\Review;

class BusinessOwnerReviewController extends Controller
{
    public function viewReviews(){

        $business = Business::query()->where('user_id', Auth::id())->firstOrFail();

        $reviews = $business->reviews()->with(['images','reply'])->latest()->paginate(10);

        return view('businessOwner.reviews.index', compact(
            'reviews'
        ));
    }

    public function reply(Request $request, Review $review)
    {
    $request->validate([
        'reply' => 'required|string|max:1000',
    ]);

    ReviewReply::updateOrCreate(
        ['review_id' => $review->id],
        ['reply' => $request->reply]
    );

    return back()->with('success', 'Reply posted successfully.');
    }
}
