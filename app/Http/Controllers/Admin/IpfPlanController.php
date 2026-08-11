<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IpfPlan;
use Illuminate\Http\Request;

class IpfPlanController extends Controller
{
    public function index()
    {
        $plans = IpfPlan::withCount('accounts')->latest()->paginate(20);

        return response()->json(['success' => true, 'data' => $plans]);
    }

    public function show(IpfPlan $plan)
    {
        return response()->json(['success' => true, 'data' => $plan]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                 => 'required|string|max:255',
            'description'          => 'nullable|string',
            'duration_days'        => 'required|integer|min:1',
            'down_payment_percent' => 'required|numeric|min:0|max:100',
            'daily_rate_percent'   => 'nullable|required_if:calculation_method,remaining_balance_percentage|numeric|min:0|max:100',
            'calculation_method'   => 'required|in:fixed,remaining_balance_percentage',
            'is_active'            => 'boolean',
        ]);

        $plan = IpfPlan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'IPF plan created successfully.',
            'data' => $plan,
        ], 201);
    }

    public function update(Request $request, IpfPlan $plan)
    {
        $validated = $request->validate([
            'name'                 => 'sometimes|string|max:255',
            'description'          => 'nullable|string',
            'duration_days'        => 'sometimes|integer|min:1',
            'down_payment_percent' => 'sometimes|numeric|min:0|max:100',
            'daily_rate_percent'   => 'nullable|numeric|min:0|max:100',
            'calculation_method'   => 'sometimes|in:fixed,remaining_balance_percentage',
            'is_active'            => 'sometimes|boolean',
        ]);

        $plan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'IPF plan updated successfully.',
            'data' => $plan,
        ]);
    }

    public function destroy(IpfPlan $plan)
    {
        if ($plan->accounts()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete a plan that already has IPF accounts against it. Deactivate it instead.',
            ], 422);
        }

        $plan->delete();

        return response()->json(['success' => true, 'message' => 'IPF plan deleted.']);
    }
}