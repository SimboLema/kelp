<?php

namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;
use App\Models\IpfSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IpfSettingsController extends Controller
{
    /**
     * Returns the currently active settings (most recently created row).
     */
    public function current()
    {
        try {
            $settings = IpfSetting::current();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'No IPF settings have been configured yet.',
            ], 404);
        }

        return response()->json(['success' => true, 'data' => $settings]);
    }

    
    public function history()
    {
        $history = IpfSetting::with('updatedBy:id,name')->latest()->paginate(20);
        return response()->json(['success' => true, 'data' => $history]);
    }

    
    public function update(Request $request)
    {
        $validated = $request->validate([
            'down_payment_percent' => 'required|numeric|min:0|max:100',
            'daily_rate_percent'   => 'required|numeric|min:0|max:100',
            'penalty_percent'      => 'required|numeric|min:0|max:100',
        ]);

        $settings = IpfSetting::create([
            ...$validated,
            'updated_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'IPF settings updated. This takes effect for new orders only — existing plans are unaffected.',
            'data' => $settings,
        ], 201);
    }
}
