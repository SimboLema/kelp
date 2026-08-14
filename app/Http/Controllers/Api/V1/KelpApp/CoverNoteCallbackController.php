<?php

namespace App\Http\Controllers\Api\V1\KelpApp;

use App\Http\Controllers\Controller;
use App\Models\InsuranceOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CoverNoteCallbackController extends Controller
{
    public function store(Request $request)
    {
        if ($request->header('X-Suretech-Secret') !== env('KELP_SECRET')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'kelp_reference_no' => 'required|string',
            'cover_note_reference' => 'nullable|string',
            'cover_note_pdf_url' => 'required|url',
        ]);

        $order = InsuranceOrder::where('reference_no', $validated['kelp_reference_no'])->first();

        if (!$order) {
            Log::warning('Cover note callback received for unknown Kelp reference.', $validated);
            return response()->json(['success' => false, 'message' => 'Order not found for that reference.'], 404);
        }

        $order->update([
            'cover_note_reference' => $validated['cover_note_reference'] ?? null,
            'cover_note_pdf_url' => $validated['cover_note_pdf_url'],
            'status' => 'Cover Note Issued',
        ]);

        return response()->json(['success' => true, 'data' => $order]);
    }
}
