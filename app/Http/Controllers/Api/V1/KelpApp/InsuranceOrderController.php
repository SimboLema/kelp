<?php

namespace App\Http\Controllers\Api\V1\KelpApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\InsuranceOrder;
use App\Services\SuretechService;

class InsuranceOrderController extends Controller
{
    protected SuretechService $suretech;

    public function __construct(SuretechService $suretech)
    {
        $this->suretech = $suretech;
    }


    public function insurers()
    {
        try {
            $insurers = $this->suretech->getInsurers();
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }

        return response()->json(['success' => true, 'data' => $insurers]);
    }


    public function insurances(Request $request)
    {
        try {
            $insurances = $this->suretech->getInsuranceTypes($request->query('insurer_id'));
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }

        return response()->json(['success' => true, 'data' => $insurances]);
    }


    public function products($insuranceId)
    {
        try {
            $products = $this->suretech->getProducts((int) $insuranceId);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }

        return response()->json(['success' => true, 'data' => $products]);
    }

    
    public function coverages($productId)
    {
        try {
            $coverages = $this->suretech->getCoverages((int) $productId);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }

        return response()->json(['success' => true, 'data' => $coverages]);
    }

    public function myOrders()
    {
        $orders = InsuranceOrder::where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function show($id)
    {
        $order = InsuranceOrder::where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'insurer_id'              => 'nullable|integer',
            'insurance_id'            => 'nullable|integer',
            'product_id'              => 'nullable|integer',
            'coverage_id'             => 'nullable|integer',
            'insurer_name'            => 'nullable|string|max:255',
            'insurance_name'          => 'nullable|string|max:255',
            'product_name'            => 'nullable|string|max:255',
            'coverage_name'           => 'nullable|string|max:255',
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
            try {
                $result = $this->suretech->calculatePremium([
                    'coverage_id'            => $request->coverage_id, // Suretech's own ID, passed straight through
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

        $order = InsuranceOrder::create([
            'reference_no'         => InsuranceOrder::generateReference(),
            'user_id'              => Auth::id(),
            'insurer_id'           => $request->insurer_id,
            'insurance_id'         => $request->insurance_id,
            'product_id'           => $request->product_id,
            'coverage_id'          => $request->coverage_id,
            // Denormalized labels — since insurer_id/insurance_id/product_id/coverage_id
            // now reference Suretech's own IDs (no local table to join against),
            // store the human-readable names at creation time so order history
            // and lists stay fast without re-fetching from Suretech.
            'insurer_name'         => $request->insurer_name,
            'insurance_name'       => $request->insurance_name,
            'product_name'         => $request->product_name,
            'coverage_name'        => $request->coverage_name,
            'sum_insured'          => $request->sum_insured,
            'premium'              => $premium,
            'premium_breakdown'    => $premiumBreakdown ? json_encode($premiumBreakdown) : null,
            'description'          => $request->description,
            'status'               => 'Pending',
            'transmission_status'  => 'Pending',
        ]);

        // Transmit to Suretech's IncomingInsuranceOrderController (POST /api/kelp/orders)
        // Suretech stores insurance/product/coverage as plain strings, not foreign keys,
        // so the *_name fields below are exactly what it expects.
        try {
            $this->suretech->submitOrder([
                'reference_no'   => $order->reference_no,
                'customer_name'  => Auth::user()->name ?? null,
                'customer_phone' => Auth::user()->phone ?? null,
                'customer_email' => Auth::user()->email ?? null,
                'insurance'      => $request->insurance_name,
                'product'        => $request->product_name,
                'coverage'       => $request->coverage_name,
                'description'    => $request->description,
                'created_at'     => $order->created_at,
            ]);
            $order->update(['transmission_status' => 'Sent']);
        } catch (\RuntimeException $e) {
            $order->update(['transmission_status' => 'Failed']);
            // Order stays saved locally even if transmission fails — don't block the response on it.
        }

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
            'coverage_id'            => 'required|integer',
            'sum_insured'            => 'required|numeric|min:1',
            'cover_note_duration_id' => 'required|integer',
            'motor_usage_id'         => 'nullable|integer',
            'sitting_capacity'       => 'nullable|integer',
            'addon_ids'              => 'nullable|array',
        ]);

        try {
            $result = $this->suretech->calculatePremium([
                'coverage_id'            => $request->coverage_id,
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
