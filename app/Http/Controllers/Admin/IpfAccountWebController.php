<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IpfAccount;
use App\Models\IpfInstallment;
use App\Models\IpfPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class IpfAccountWebController extends Controller
{
    public function index(Request $request): View
    {
        $query = IpfAccount::with([
            'user:id,name,phone_number,email',
            'plan:id,name,duration_days',
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
                        ->orWhere('phone_number', 'like', "%{$search}%"))
                  ->orWhereHas('order', fn ($o) => $o->where('reference_no', 'like', "%{$search}%")
                        ->orWhere('registration_number', 'like', "%{$search}%"));
            });
        }

        $accounts = $query->paginate(20)->withQueryString();
        $plans = IpfPlan::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.ipf.accounts.index', compact('accounts', 'plans'));
    }

    public function show($id): View
    {
        $account = IpfAccount::with([
            'user:id,name,phone_number,email',
            'plan',
            'order',
            'installments' => fn ($q) => $q->orderBy('installment_number'),
            'payments' => fn ($q) => $q->latest(),
        ])
        ->findOrFail($id);

        return view('admin.ipf.accounts.show', compact('account'));
    }

    public function markOverdue($id): RedirectResponse
    {
        $account = IpfAccount::findOrFail($id);

        $count = $account->installments()
            ->where('status', 'pending')
            ->where('due_date', '<', now()->toDateString())
            ->update(['status' => 'overdue']);

        return redirect()
            ->route('admin.ipf.accounts.show', $account->id)
            ->with('success', "{$count} installment(s) marked overdue.");
    }

    public function report(): View
    {
        $summary = [
            'total_accounts'       => IpfAccount::count(),
            'pending_accounts'     => IpfAccount::query()->where('status', 'pending')->count(),
            'active_accounts'      => IpfAccount::query()->where('status', 'active')->count(),
            'completed_accounts'   => IpfAccount::query()->where('status', 'completed')->count(),
            'defaulted_accounts'   => IpfAccount::query()->where('status', 'defaulted')->count(),
            'total_financed'       => (float) IpfAccount::sum('financed_amount'),
            'total_collected'      => (float) IpfAccount::sum('total_paid'),
            'total_outstanding'    => (float) IpfAccount::sum('remaining_amount'),
            'overdue_installments' => IpfInstallment::query()->where('status', 'overdue')->count(),
            'overdue_amount'       => (float) IpfInstallment::query()->where('status', 'overdue')->sum('remaining_amount'),
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

        return view('admin.ipf.report', compact('summary', 'byPlan'));
    }
}
