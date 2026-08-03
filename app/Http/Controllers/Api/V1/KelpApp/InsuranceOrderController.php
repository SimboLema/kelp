<?php

namespace App\Http\Controllers\Api\V1\KelpApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Models\Insurance;
use App\Models\Insurer;
use App\Models\Product;
use App\Models\Coverage;
use App\Models\InsuranceOrder;

use App\Services\SuretechService;

class InsuranceOrderController extends Controller
{
    protected SuretechService $suretech;

    public function __construct(SuretechService $suretech)
    {
        $this->suretech = $suretech;
    }
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

    public function store(Request $request)
    {
        $request->validate([
            'insurer_id'              => 'nullable|exists:insurers,id',
            'insurance_id'            => 'nullable|exists:insurances,id',
            'product_id'              => 'nullable|exists:products,id',
            'coverage_id'             => 'nullable|exists:coverages,id',
            'sum_insured'             => 'nullable|numeric|min:1',
            'cover_note_duration_id'  => 'nullable|integer',
            'motor_usage_id'          => 'nullable|integer',
            'sitting_capacity'        => 'nullable|integer',
            'addon_ids'               => 'nullable|array',
            'description'             => 'nullable|string|max:5000',
        ]);

        if (!$request->product_id && empty($request->description)) {
            return response()->json([
                'success' => false,
                'message' => 'Please select a product or provide a description.'
            ], 422);
        }

        $premium = null;
        $premiumBreakdown = null;

        if ($request->coverage_id && $request->sum_insured && $request->cover_note_duration_id) {
            $coverage = Coverage::query()->find($request->coverage_id);

            if ($coverage && $coverage->kmj_coverage_id) {
                try {
                    $result = $this->premiumService->calculate([
                        'coverage_id'            => $coverage->kmj_coverage_id,
                        'sum_insured'            => $request->sum_insured,
                        'cover_note_duration_id' => $request->cover_note_duration_id,
                        'motor_usage_id'         => $request->motor_usage_id,
                        'sitting_capacity'       => $request->sitting_capacity,
                        'addon_ids'              => $request->addon_ids ?? [],
                    ]);
                    $premium = $result['total_premium_including_tax'];
                    $premiumBreakdown = $result;
                } catch (\RuntimeException $e) {
                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage(),
                    ], 502);
                }
            }
        }

        $order = InsuranceOrder::create([
            'reference_no' => InsuranceOrder::generateReference(),
            'user_id' => Auth::id(),
            'insurer_id' => $request->insurer_id,
            'insurance_id' => $request->insurance_id,
            'product_id' => $request->product_id,
            'coverage_id' => $request->coverage_id,
            'sum_insured' => $request->sum_insured,
            'premium' => $premium,
            'premium_breakdown' => $premiumBreakdown ? json_encode($premiumBreakdown) : null,
            'description' => $request->description,
            'status' => 'Pending',
            'transmission_status' => 'Pending',
        ]);

        // ... existing Suretech transmission block, unchanged

        return response()->json([
            'success' => true,
            'message' => 'Insurance order submitted successfully.',
            'data' => $order
        ], 201);
    }

    public function verifyMotor(Request $request)
    {
        $request->validate([
            'motor_category' => 'required|in:1,2',
            'registration_number' => 'required|string',
            'chassis_number' => 'nullable|string',
        ]);

        try {
            $vehicle = $this->suretech->verifyMotor([
                'motor_category' => $request->motor_category,
                'motor_registration_number' => $request->registration_number,
                'motor_chassis_number' => $request->chassis_number,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }

        return response()->json(['success' => true, 'data' => $vehicle]);
    }

    public function calculatePremium(Request $request)
    {
        $request->validate([
            'coverage_id'            => 'required|exists:coverages,id',
            'sum_insured'            => 'required|numeric|min:1',
            'cover_note_duration_id' => 'required|integer',
            'motor_usage_id'         => 'nullable|integer',
            'sitting_capacity'       => 'nullable|integer',
            'addon_ids'              => 'nullable|array',
        ]);

        $coverage = Coverage::findOrFail($request->coverage_id);

        if (empty($coverage->kmj_coverage_id)) {
            return response()->json([
                'success' => false,
                'message' => 'This coverage is not linked to a Suretech rating record yet.',
            ], 422);
        }

        try {
            $result = $this->suretech->calculatePremium([
                'coverage_id'            => $coverage->kmj_coverage_id,
                'sum_insured'            => $request->sum_insured,
                'cover_note_duration_id' => $request->cover_note_duration_id,
                'motor_usage_id'         => $request->motor_usage_id,
                'sitting_capacity'       => $request->sitting_capacity,
                'addon_ids'              => $request->addon_ids ?? [],
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }

        return response()->json(['success' => true, 'data' => $result]);
    }



}
