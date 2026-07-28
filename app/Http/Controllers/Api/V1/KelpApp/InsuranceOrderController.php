<?php

namespace App\Http\Controllers\Api\V1\KelpApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Models\KMJ\Insurance;
use App\Models\Models\KMJ\Insurer;
use App\Models\Models\KMJ\Product;
use App\Models\Models\KMJ\Coverage;
use App\Models\Models\KMJ\InsuranceOrder;

class InsuranceOrderController extends Controller
{
    /**
     * Get insurers
     */
    public function insurers()
    {
        return response()->json([
            'success' => true,
            'data' => Insurer::where('status', true)
                ->orderBy('name')
                ->get()
        ]);
    }

    /**
     * Get insurance types
     */
    public function insurances()
    {
        return response()->json([
            'success' => true,
            'data' => Insurance::orderBy('name')->get()
        ]);
    }

    /**
     * Get products by insurance
     */
    public function products($insuranceId)
    {
        $products = Product::where('insurance_id', $insuranceId)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get coverages
     */
    public function coverages($productId)
    {
        $coverages = Coverage::where('product_id', $productId)
            ->orderBy('risk_name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $coverages
        ]);
    }

    /**
     * Store insurance order
     */
    public function store(Request $request)
    {
        $request->validate([
            'insurer_id'   => 'nullable|exists:insurers,id',
            'insurance_id' => 'nullable|exists:insurances,id',
            'product_id'   => 'nullable|exists:products,id',
            'coverage_id'  => 'nullable|exists:coverages,id',
            'description'  => 'nullable|string|max:5000',
        ]);

        if (!$request->product_id && empty($request->description)) {
            return response()->json([
                'success' => false,
                'message' => 'Please select a product or provide a description.'
            ], 422);
        }

        $order = InsuranceOrder::create([
            'reference_no' => InsuranceOrder::generateReference(),
            'user_id' => Auth::id(),
            'insurer_id' => $request->insurer_id,
            'insurance_id' => $request->insurance_id,
            'product_id' => $request->product_id,
            'coverage_id' => $request->coverage_id,
            'description' => $request->description,
            'status' => 'Pending',
            'transmission_status' => 'Pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Insurance order submitted successfully.',
            'data' => $order
        ], 201);
    }

    /**
     * User orders
     */
    public function myOrders()
    {
        $orders = InsuranceOrder::with([
            'insurer',
            'insurance',
            'product',
            'coverage'
        ])
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Single order
     */
    public function show($id)
    {
        $order = InsuranceOrder::with([
            'insurer',
            'insurance',
            'product',
            'coverage'
        ])
        ->where('user_id', Auth::id())
        ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }
}
