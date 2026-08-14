<?php

namespace App\Http\Controllers\Api\V1\KelpApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PointsTransaction;
use App\Services\PointsService;

class PointsController extends Controller
{
            public function balance(Request $request, PointsService $points)
        {
            return response()->json(['success' => true, 'data' => [
                'balance' => $points->balance($request->user()),
                'referral_code' => $request->user()->referral_code,
            ]]);
        }

        public function history(Request $request)
        {
            $transactions = PointsTransaction::where('user_id', $request->user()->id)->latest()->paginate(20);
            return response()->json(['success' => true, 'data' => $transactions]);
        }

        public function redeem(Request $request, PointsService $points)
        {
            $request->validate([
                'points' => 'required|integer|min:1',
                'method' => 'required|in:cash,voucher',
                'payout_details' => 'nullable|string',
            ]);

            try {
                $redemption = $points->requestRedemption($request->user(), $request->points, $request->method, $request->payout_details);
            } catch (\RuntimeException $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return response()->json(['success' => true, 'data' => $redemption], 201);
        }
}
