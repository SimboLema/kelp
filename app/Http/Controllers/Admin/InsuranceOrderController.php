<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InsuranceOrder;
use Illuminate\Http\Request;

class InsuranceOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = InsuranceOrder::with([
            'user',
            'insurer',
            'insurance',
            'product',
            'coverage',
            'ipfAccount',
        ]);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                    ->orWhere('registration_number', 'like', "%{$search}%")
                    ->orWhere('cover_note_reference', 'like', "%{$search}%")
                    ->orWhere('external_reference', 'like', "%{$search}%")
                    ->orWhere('insurer_name', 'like', "%{$search}%")
                    ->orWhere('product_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone_number', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment mode
        if ($request->filled('payment_mode')) {
            $query->where('payment_mode', $request->payment_mode);
        }

        // Filter by transmission status
        if ($request->filled('transmission_status')) {
            $query->where(
                'transmission_status',
                $request->transmission_status
            );
        }

        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Dashboard statistics
        $totalOrders = InsuranceOrder::count();

        $totalPremium = InsuranceOrder::sum('premium');

        $pendingOrders = InsuranceOrder::where('status', 'pending')->count();

        $completedOrders = InsuranceOrder::where('status', 'completed')->count();

        $ipfOrders = InsuranceOrder::where('payment_mode', 'ipf')->count();

        return view('admin.insurance_orders.index', compact(
            'orders',
            'totalOrders',
            'totalPremium',
            'pendingOrders',
            'completedOrders',
            'ipfOrders'
        ));
    }
}
