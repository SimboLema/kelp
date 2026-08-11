<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IpfPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IpfPlanWebController extends Controller
{
    public function index(): View
    {
        $plans = IpfPlan::withCount('accounts')->latest()->paginate(15);

        return view('admin.ipf.plans.index', compact('plans'));
    }

    public function create(): View
    {
        $plan = new IpfPlan();

        return view('admin.ipf.plans.create', compact('plan'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePlan($request);

        IpfPlan::create($validated);

        return redirect()
            ->route('admin.ipf.plans.index')
            ->with('success', 'IPF plan created.');
    }

    public function edit(IpfPlan $plan): View
    {
        return view('admin.ipf.plans.edit', compact('plan'));
    }

    public function update(Request $request, IpfPlan $plan): RedirectResponse
    {
        $validated = $this->validatePlan($request);

        $plan->update($validated);

        return redirect()
            ->route('admin.ipf.plans.index')
            ->with('success', 'IPF plan updated.');
    }

    public function destroy(IpfPlan $plan): RedirectResponse
    {
        if ($plan->accounts()->exists()) {
            return redirect()
                ->route('admin.ipf.plans.index')
                ->with('error', 'Cannot delete a plan that already has IPF accounts against it. Deactivate it instead.');
        }

        $plan->delete();

        return redirect()
            ->route('admin.ipf.plans.index')
            ->with('success', 'IPF plan deleted.');
    }

    private function validatePlan(Request $request): array
    {
        return $request->validate([
            'name'                 => 'required|string|max:255',
            'description'          => 'nullable|string',
            'duration_days'        => 'required|integer|min:1',
            'down_payment_percent' => 'required|numeric|min:0|max:100',
            'daily_rate_percent'   => 'nullable|required_if:calculation_method,remaining_balance_percentage|numeric|min:0|max:100',
            'calculation_method'   => 'required|in:fixed,remaining_balance_percentage',
            'is_active'            => 'boolean',
        ]);
    }
}