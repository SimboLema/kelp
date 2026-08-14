<?php

namespace App\Http\Controllers\Api\V1\KelpApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ReferralController extends Controller
{
   
    public function apply(Request $request)
    {
        $request->validate(['referral_code' => 'required|string']);

        $user = $request->user();

        if ($user->referred_by_user_id) {
            return response()->json(['success' => false, 'message' => 'A referral code has already been applied to your account.'], 422);
        }

        $referrer = User::where('referral_code', strtoupper($request->referral_code))->first();

        if (!$referrer || $referrer->id === $user->id) {
            return response()->json(['success' => false, 'message' => 'Invalid referral code.'], 422);
        }

        $user->update(['referred_by_user_id' => $referrer->id]);

        return response()->json(['success' => true, 'message' => 'Referral code applied.']);
    }
}
