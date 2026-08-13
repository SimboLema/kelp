<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Review;
use App\Models\User;
use App\Models\InsuranceOrder;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalAgents = User::query()->where('role','agent')->count();

        $totalBusinesses = Business::count();

        $totalInsuranceOrders = InsuranceOrder::count();

        $totalReviews = Review::count();

        return view('admin.dashboard', compact(
            'totalAgents',
            'totalBusinesses',
            'totalInsuranceOrders',
            'totalReviews'
        ));
    }
}
