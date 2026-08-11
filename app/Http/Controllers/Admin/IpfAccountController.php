<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IpfAccount;
use App\Models\IpfInstallment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IpfAccountController extends Controller
{
    // GET /admin/ipf/accounts — every customer currently under IPF, with
    // remaining balance + progress, filterable and searchable.
    public function index(Request $request)
    {
        $query = IpfAccount::with([
                'user:id,name,phone,email',
                'plan:id,name,duration_days,calculation_method',
                'order:id,reference_no,registration_number',
            ])
            ->latest();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($planId = $request->query('ipf_plan_id')) {
            $query->where('ipf_plan_id', $planId);
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%"))
                  ->orWhereHas('order', fn ($o) => $o->where('reference_no', 'like', "%{$search}%")
                        ->orWhere('registration_number', 'like', "%{$search}%"));
            });
        }

        $accounts = $query->paginate($request->integer('per_page', 20));

        return response()->json(['success' => true, 'data' => $accounts]);
    }

    // GET /admin/ipf/accounts/{id} — full detail: installment breakdown + payment history.
    public function show($id)
    {
        $account = IpfAccount::with([
                'user:id,name,phone,email',
                'plan',
                'order',
                'installments' => fn ($q) => $q->orderBy('installment_number'),
                'payments' => fn ($q) => $q->latest(),
            ])
            ->findOrFail($id);

        return response()->json(['success' => true, 'data' => $account]);
    }

    // POST /admin/ipf/accounts/{id}/mark-overdue — manual trigger for one account.
    // For the whole portfolio, run `php artisan ipf:mark-overdue` on a schedule instead.
    public function markOverdue($id)
    {
        $account = IpfAccount::findOrFail($id);

        $count = $account->installments()
            ->where('status', 'pending')
            ->where('due_date', '<', now()->toDateString())
            ->update(['status' => 'overdue']);

        return response()->json(['success' => true, 'message' => "{$count} installment(s) marked overdue."]);
    }

    // GET /admin/ipf/report — portfolio-level numbers for follow-up.
    public function report()
    {
        $summary = [
            'total_accounts'      => IpfAccount::count(),
            'pending_accounts'    => IpfAccount::query()->where('status', 'pending')->count(),
            'active_accounts'     => IpfAccount::query()->where('status', 'active')->count(),
            'completed_accounts'  => IpfAccount::query()->where('status', 'completed')->count(),
            'defaulted_accounts'  => IpfAccount::query()->where('status', 'defaulted')->count(),
            'total_financed'      => (float) IpfAccount::sum('financed_amount'),
            'total_collected'     => (float) IpfAccount::sum('total_paid'),
            'total_outstanding'   => (float) IpfAccount::sum('remaining_amount'),
            'overdue_installments' => IpfInstallment::query()->where('status', 'overdue')->count(),
            'overdue_amount'      => (float) IpfInstallment::query()->where('status', 'overdue')->sum('remaining_amount'),
        ];

        $byPlan = IpfAccount::select(
                'ipf_plan_id',
                DB::raw('count(*) as accounts_count'),
                DB::raw('sum(financed_amount) as total_financed'),
                DB::raw('sum(total_paid) as total_collected'),
                DB::raw('sum(remaining_amount) as total_outstanding')
            )
            ->with('plan:id,name')
            ->groupBy('ipf_plan_id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => $summary,
                'by_plan' => $byPlan,
            ],
        ]);
    }
}