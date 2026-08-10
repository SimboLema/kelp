<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IpfPlan;
use App\Models\IpfTransaction;
use App\Services\IpfService;
use Illuminate\Http\Request;

class AdminIpfPlanController extends Controller
{
    protected IpfService $ipf;

    public function __construct(IpfService $ipf)
    {
        $this->ipf = $ipf;
    }

    /**
     * Display all IPF plans.
     *
     * Filters:
     * ?status=active|completed|defaulted
     * ?overdue=1
     * ?search=...
     */
    public function index(Request $request)
    {
        $query = IpfPlan::with(
                'order:id,reference_no,registration_number,user_id'
            )
            ->withCount('transactions');

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Overdue Filter
        |--------------------------------------------------------------------------
        */
        if ($request->boolean('overdue')) {
            $query->where('status', 'active')
                ->where(function ($q) {
                    $q->whereNull('last_charged_date')
                        ->orWhere(
                            'last_charged_date',
                            '<',
                            now()->toDateString()
                        );
                });
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas('order', function ($q) use ($search) {

                $q->where(
                    'reference_no',
                    'like',
                    "%{$search}%"
                )->orWhere(
                    'registration_number',
                    'like',
                    "%{$search}%"
                );

            });
        }

        $plans = $query
            ->latest()
            ->paginate(20);

        return view(
            'admin.ipf.index',
            compact('plans')
        );
    }

    /**
     * IPF dashboard summary.
     */
    public function summary()
    {
        $today = now()->toDateString();

        $summary = [

            'active_count' => IpfPlan::query()->where(
                'status',
                'active'
            )->count(),

            'completed_count' => IpfPlan::query()->where(
                'status',
                'completed'
            )->count(),

            'defaulted_count' => IpfPlan::query()->where(
                'status',
                'defaulted'
            )->count(),

            'overdue_count' => IpfPlan::query()->where(
                    'status',
                    'active'
                )
                ->where(function ($q) use ($today) {

                    $q->whereNull('last_charged_date')
                        ->orWhere(
                            'last_charged_date',
                            '<',
                            $today
                        );

                })
                ->count(),

            'total_outstanding' => IpfPlan::query()->where(
                'status',
                'active'
            )->sum('outstanding_balance'),
        ];

        return view(
            'admin.ipf.summary',
            compact('summary')
        );
    }

    /**
     * Display one IPF plan.
     */
    public function show($id)
    {
        $plan = IpfPlan::with([

            'order:id,reference_no,registration_number,user_id,customer_details,motor_details',

            'transactions' => function ($q) {
                $q->latest('transaction_date');
            },

        ])->findOrFail($id);

        return view(
            'admin.ipf.show',
            compact('plan')
        );
    }

    /**
     * Admin records a payment.
     */
    public function recordPayment(
        Request $request,
        $id
    ) {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'note'   => 'required|string|max:255',
        ]);

        $plan = IpfPlan::findOrFail($id);

        try {

            $transaction = $this->ipf->recordPayment(
                $plan,
                $request->amount,
                $request->note
            );

        } catch (\RuntimeException $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }

        return redirect()
            ->to('/ipf/plans/' . $plan->id)
            ->with(
                'success',
                'Payment recorded successfully.'
            );
    }

    /**
     * Waive a penalty transaction.
     */
    public function waivePenalty(
        Request $request,
        $planId,
        $transactionId
    ) {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $plan = IpfPlan::findOrFail($planId);

        $penaltyTx = IpfTransaction::query()->where(
                'ipf_plan_id',
                $plan->id
            )
            ->where(
                'id',
                $transactionId
            )
            ->where(
                'type',
                'penalty'
            )
            ->firstOrFail();

        $newBalance = max(
            0,
            $plan->outstanding_balance -
            $penaltyTx->amount
        );

        $waiver = IpfTransaction::create([

            'ipf_plan_id' => $plan->id,

            'type' => 'installment',

            'amount' => $penaltyTx->amount,

            'balance_after' => $newBalance,

            'transaction_date' => now()->toDateString(),

            'note' =>
                "Penalty waived " .
                "(original tx #{$penaltyTx->id}): " .
                $request->reason,

        ]);

        $plan->update([

            'outstanding_balance' => $newBalance,

            'status' => $newBalance <= 0
                ? 'completed'
                : $plan->status,

        ]);

        return redirect()
            ->to('/ipf/plans/' . $plan->id)
            ->with(
                'success',
                'Penalty waived successfully.'
            );
    }

    /**
     * Mark an active plan as defaulted.
     */
    public function markDefaulted(
        Request $request,
        $id
    ) {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $plan = IpfPlan::findOrFail($id);

        if ($plan->status !== 'active') {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Only active plans can be marked defaulted.'
                );
        }

        $plan->update([
            'status' => 'defaulted'
        ]);

        IpfTransaction::create([

            'ipf_plan_id' => $plan->id,

            'type' => 'penalty',

            'amount' => 0,

            'balance_after' =>
                $plan->outstanding_balance,

            'transaction_date' =>
                now()->toDateString(),

            'note' =>
                "Marked defaulted: " .
                $request->reason,

        ]);

        return redirect()
            ->to('/ipf/plans/' . $plan->id)
            ->with(
                'success',
                'IPF plan marked as defaulted.'
            );
    }
}