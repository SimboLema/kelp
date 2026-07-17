<?php

namespace App\Http\Controllers\BusinessOwner;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BusinessOwnerDashboardController extends Controller
{
    public function index(){
        $business = Business::query()->where('user_id', Auth::id())->firstOrFail();
        $totalReviews = $business->reviews()->count();
        $totalPosts = $business->images()->count();

        
        return view('businessOwner.dashboard', compact(
            'totalReviews',
            'totalPosts'
        ));
    }
}
