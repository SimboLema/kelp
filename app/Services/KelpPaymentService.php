<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class KelpPaymentService
{
    private string $baseUrl;
    private string $email;
    private string $password;
    private string $callbackToken;
    private ?string $callbackUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(
            (string) config('services.kelp_payment.base_url'),
            '/',
        );

        $this->email = (string) config(
            'services.kelp_payment.email',
        );

        $this->password = (string) config(
            'services.kelp_payment.password',
        );

        $this->callbackToken = (string) config(
            'services.kelp_payment.callback_token',
        );

        $this->callbackUrl = config(
            'services.kelp_payment.callback_url',
        );

        if ($this->baseUrl === '') {
            throw new RuntimeException(
                'KELP_URL is not configured.',
            );
        }

        if ($this->email === '') {
            throw new RuntimeException(
                'SURETECH_PAYMENT_EMAIL is not configured.',
            );
        }

        if ($this->password === '') {
            throw new RuntimeException(
                'SURETECH_PAYMENT_PASSWORD is not configured.',
            );
        }

        if ($this->callbackToken === '') {
            throw new RuntimeException(
                'PAYMENT_KELP is not configured.',
            );
        }
    }

    private function client(): PendingRequest
    {
        return Http::acceptJson()
            ->asJson()
            ->timeout(30);
    }

    public function requestToken(): string
    {
        $response = $this->client()->post(
            $this->baseUrl .
                '/api/pgway/services/v1/payment/token/request',
            [
                'email' => $this->email,
                'password' => $this->password,
            ],
        );

        if ($response->failed()) {
            throw new RuntimeException(
                $response->json('message') ??
                    'Unable to request payment token.',
            );
        }

        $token = $response->json('token');

        if (!is_string($token) || $token === '') {
            throw new RuntimeException(
                $response->json('message') ??
                    'Payment token is missing.',
            );
        }

        return $token;
    }

    public function requestUssdPayment(array $payload): array
    {
        $token = $this->requestToken();

        $response = $this->client()
            ->withToken($token)
            ->withHeaders([
                'X-Email' => $this->email,
            ])
            ->post(
                $this->baseUrl .
                    '/api/pgway/services/v1/payment/ussdpush/request',
                [
                    'phone' => $payload['phone'],
                    'amount' => $payload['amount'],
                    'orderId' => $payload['orderId'],
                    'callbackUrl' =>
                        $payload['callbackUrl'] ??
                        $this->callbackUrl,
                    'ValidationCallbackToken' =>
                        $this->callbackToken,
                ],
            );

        if ($response->failed()) {
            throw new RuntimeException(
                $response->json('message') ??
                    'Unable to initiate payment.',
            );
        }

        return $response->json();
    }
}