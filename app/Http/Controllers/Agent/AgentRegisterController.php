<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Business;
use App\Models\BusinessImage;
use Illuminate\Support\Facades\DB;

class AgentRegisterController extends Controller
{
    public function registerBusiness(Request $request)
    {
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

        DB::beginTransaction();

        try {

            $user = User::create([
                'id' => Str::uuid(),
                'name' => $request->name,
                'email' => $request->email ?? 'owner'.Str::random(5).'@kelp.local',
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

                'status' => 'pending',
            ]);

            if ($request->hasFile('logo')) {

                $logo = $request->file('logo')
                    ->store('business_logos', 'public');

                $business->update([
                    'logo' => $logo
                ]);
            }

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

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Business registered successfully. Waiting for approval.',
                'business' => $business->load('images')
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }

   
}
