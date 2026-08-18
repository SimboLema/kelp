<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InsuranceOrder;

class InsuranceOrderController extends Controller
{
    /**
     * Display all insurance orders.
     */
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

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Payment mode filter
        if ($request->filled('payment_mode')) {
            $query->where('payment_mode', $request->payment_mode);
        }

        // Transmission status filter
        if ($request->filled('transmission_status')) {
            $query->where(
                'transmission_status',
                $request->transmission_status
            );
        }

        // Date filters
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

        // Statistics
        $totalOrders = InsuranceOrder::count();

        $totalPremium = InsuranceOrder::sum('premium');

        $pendingOrders = InsuranceOrder::where(
            'status',
            'pending'
        )->count();

        $completedOrders = InsuranceOrder::where(
            'status',
            'completed'
        )->count();

        $ipfOrders = InsuranceOrder::where(
            'payment_mode',
            'ipf'
        )->count();

        return view('admin.insurance_orders.index', compact(
            'orders',
            'totalOrders',
            'totalPremium',
            'pendingOrders',
            'completedOrders',
            'ipfOrders'
        ));
    }


    /**
     * Display a single insurance order.
     */
    public function show($id)
    {
        $order = InsuranceOrder::with([
            'user',
            'insurer',
            'insurance',
            'product',
            'coverage',
            'ipfAccount',
        ])->findOrFail($id);

        return view(
            'admin.insurance_orders.show',
            compact('order')
        );
    }
}
