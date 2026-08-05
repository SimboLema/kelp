<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Business;

class AgentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $base = Business::query();

        $total = (clone $base)->count();
        $approved = (clone $base)->where('status', 'approved')->count();
        $pending = (clone $base)->where('status', 'pending')->count();
        $rejected = (clone $base)->where('status', 'rejected')->count();

        $recent = (clone $base)
            ->with('category:id,name')
            ->latest()
            ->take(5)
            ->get(['id', 'name', 'category_id', 'status', 'created_at']);

        return response()->json([
            'stats' => [
                'total' => $total,
                'approved' => $approved,
                'pending' => $pending,
                'rejected' => $rejected,
            ],
            'recent_submissions' => $recent->map(fn ($b) => [
                'id' => $b->id,
                'name' => $b->name,
                'category' => $b->category->name ?? 'Uncategorized',
                'status' => $b->status,
                'created_at' => $b->created_at->toIso8601String(),
            ]),
        ]);
    }
}
