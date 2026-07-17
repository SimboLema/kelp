<?php

namespace App\Http\Controllers\Admin\BusinessOwner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Business;
use App\Models\BusinessImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;

class BusinessesController extends Controller
{
    public function index(){
        $businesses = Business::with(['owner','category'])
                    ->where('status','pending')
                    ->latest()
                    ->get();
        return view('admin.businessOwner.index',compact('businesses'));
    }


    public function ShowRegistrationForm()
    {
        $categories = Category::all();

        return view('admin.businessOwner.register', compact('categories'));
    }



public function register(Request $request)
{

    // 1. Validate the request
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'name' => 'required|string|max:255',
        'password' => 'required|string|min:6|confirmed',
        'description' => 'nullable|string',
        'phone' => 'required|string|max:20',
        'email' => 'nullable|email|unique:users,email',
        'website' => 'nullable|string',

        'address' => 'required|string',
        'city' => 'required|string',
        'country' => 'required|string',

        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',

        'logo' => 'nullable|image',
        'images.*' => 'image'
    ]);

    
    $user = User::create([
        'id' => Str::uuid(),
        'name' => $request->name,
        'email' => $request->email ?? 'owner' . Str::random(5) . '@kelp.local', // in case email optional
        'password' => Hash::make($request->password),
        'role' => 'business_owner',
    ]);


    $business = Business::create([
        'id' => Str::uuid(),
        'user_id' => $user->id,
        'category_id' => $request->category_id,

        'name' => $request->name,
        'description' => $request->description,
        'phone' => $request->phone,
        'email' => $request->email,
        'website' => $request->website,

        'address' => $request->address,
        'city' => $request->city,
        'country' => $request->country,

        'latitude' => $request->latitude,
        'longitude' => $request->longitude,

        'status' => 'pending'
    ]);

    // 4. Upload Logo
    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('business_logos', 'public');
        $business->update(['logo' => $logoPath]);
    }

    // 5. Upload Gallery Images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('business_images', 'public');
            BusinessImage::create([
                'id' => Str::uuid(),
                'business_id' => $business->id,
                'image_path' => $path
            ]);
        }
    }

    return response()->json([
        'message' => 'Business and user created successfully',
        'business' => $business,
        'user' => $user
    ], 201);
}

public function approve($id)
{
    $business = Business::findOrFail($id);

    $business->update([
        'status' => 'approved'
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Business approved successfully'
    ]);
}


public function reject($id)
{
    $business = Business::findOrFail($id);

    $business->update([
        'status' => 'rejected'
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Business rejected'
    ]);
}


public function view($id)
{
    $business = Business::with(['owner','category','images'])
                ->findOrFail($id);

    return response()->json([
        'success' => true,
        'business' => $business
    ]);
}
public function listByStatus(Request $request)
{
    $status = $request->query('status', 'pending');

    $businesses = Business::with('owner')
        ->where('status', $status)
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json(['businesses' => $businesses]);
}
}
