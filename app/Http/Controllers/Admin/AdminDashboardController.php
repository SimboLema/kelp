<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\Review;

class AdminDashboardController extends Controller
{
    public function index(){

        

        return view('admin.dashboard');
    }
}
