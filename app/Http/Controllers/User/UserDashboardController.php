<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class UserDashboardController extends Controller
{
    public function index(){

        $categories = Category::all();
        $recentReviews = \App\Models\Review::with('business')->where('status', 'approved')
        ->latest()
        ->take(10)
        ->get();
        return view('welcome',compact('categories','recentReviews'));
    }
}
