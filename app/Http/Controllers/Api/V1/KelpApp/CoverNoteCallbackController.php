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
        $expectedSecret = (string) env('KELP_SECRET', '');
        $providedSecret = (string) $request->header('X-Suretech-Secret', '');

        if ($expectedSecret === '' || ! hash_equals($expectedSecret, $providedSecret)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = validator($this->normalisedPayload($request), [
            'kelp_reference_no' => 'required|string',
            'cover_note_reference' => 'nullable|string',
            'cover_note_pdf_url' => 'required|url',
        ])->validate();

        $order = InsuranceOrder::where('reference_no', $validated['kelp_reference_no'])->first();

        if (!$order) {
            Log::warning('Cover note callback received for unknown Kelp reference.', $validated);
            return response()->json(['success' => false, 'message' => 'Order not found for that reference.'], 404);
        }

        $responsePayload = is_array($order->response_payload)
            ? $order->response_payload
            : [];
        $responsePayload['cover_note_callback'] = $request->all();

        $order->update([
            'cover_note_reference' => $validated['cover_note_reference'] ?? null,
            'cover_note_pdf_url' => $validated['cover_note_pdf_url'],
            'external_reference' => $validated['cover_note_reference'] ?? $order->external_reference,
            'response_payload' => $responsePayload,
            'status' => 'Approved',
            'transmission_status' => 'Sent',
            'last_error' => null,
        ]);

        return response()->json(['success' => true, 'data' => $order]);
    }

    private function normalisedPayload(Request $request): array
    {
        $quotationId = $request->input('quotation_id');

        return [
            'kelp_reference_no' => $request->input('kelp_reference_no')
                ?? $request->input('reference_no')
                ?? $request->input('kelp_reference'),
            'cover_note_reference' => $request->input('cover_note_reference')
                ?? $request->input('cover_note_number')
                ?? $request->input('cover_note_no')
                ?? $quotationId,
            'cover_note_pdf_url' => $request->input('cover_note_pdf_url')
                ?? $request->input('cover_note_url')
                ?? $request->input('download_url')
                ?? $request->input('url')
                ?? $this->coverNoteUrlFromQuotation($quotationId),
        ];
    }

    private function coverNoteUrlFromQuotation(mixed $quotationId): ?string
    {
        $quotationId = trim((string) $quotationId);

        if ($quotationId === '' || ! ctype_digit($quotationId)) {
            return null;
        }

        $baseUrl = rtrim((string) config('services.suretech.base_url'), '/');

        if ($baseUrl === '') {
            $baseUrl = 'https://suretech.co.tz';
        }

        return "{$baseUrl}/dash/quotation/{$quotationId}/download/cover-note-public";
    }
}
