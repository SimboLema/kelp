<?php

namespace App\Http\Controllers\Api\V1\KelpApp;

use App\Http\Controllers\Controller;
use App\Services\KelpPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RuntimeException;

class PaymentController extends Controller
{
    public function __construct(
        private readonly KelpPaymentService $paymentService,
    ) {
    }

    public function initiate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => [
                'required',
                'string',
                'regex:/^255[0-9]{9}$/',
            ],
            'amount' => [
                'required',
                'numeric',
                'min:1',
            ],
            'order_id' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        $orderId = $validated['order_id']
            ?? 'KELP-' . now()->format('YmdHis') . '-' . Str::upper(
                Str::random(6),
            );

        try {
            $result = $this->paymentService->requestUssdPayment([
                'phone' => $validated['phone'],
                'amount' => $validated['amount'],
                'orderId' => $orderId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment request sent successfully.',
                'data' => [
                    'order_id' => $orderId,
                    'phone' => $validated['phone'],
                    'amount' => (float) $validated['amount'],
                    'gateway_response' => $result,
                ],
            ]);
        } catch (RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 502);
        }
    }
}