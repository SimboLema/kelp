<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\ReviewImage;

use Illuminate\Support\Facades\Storage;

class UserCategoriesController extends Controller{
    public function show($id){
        $category = Category::with('businesses')->findOrFail($id);

        return view('user.categories.show', compact('category'));
    }

    public function details($id)
    {
        $business = Business::with([
            'category',
            'reviews' => function ($query) {
                $query->where('status', 'approved')
                      ->with('images')
                      ->latest();
            }
        ])->findOrFail($id);
    
        return view('user.categories.details', compact('business'));
    }



    public function storeReview(Request $request, $businessId)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5',

            // images validation
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $status = $request->hasFile('images') ? 'pending' : 'approved';

        // Create review
        $review = Review::create([
            'business_id' => $businessId,
            'user_name' => $validated['user_name'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'status'      => $status,
        ]);

        // Upload images
        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {

                $path = $image->store('review-images', 'public');

                ReviewImage::create([
                    'review_id' => $review->id,
                    'image' => $path,
                ]);
            }
        }

        $message = $status === 'approved'
        ? 'Thanks for your review, ' . $validated['user_name'] . '!'
        : 'Thanks! Your review has been submitted and is awaiting approval because it contains images.';


        return redirect()
            ->route('details.show', $businessId)
            ->with('success', 'Thanks for your review, ' . $validated['user_name'] . '!');
    }
}
