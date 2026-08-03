<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SuretechService
{
    protected string $baseUrl;
    protected string $secret;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('SURETECH_URL'), '/');
        $this->secret  = env('SURETECH_SECRET');
    }

    protected function client()
    {
        return Http::timeout(20)
            ->withHeaders([
                'X-Kelp-Secret' => $this->secret,
                'Accept' => 'application/json',
            ]);
    }

    /**
     * @throws \RuntimeException
     */
    public function calculatePremium(array $payload): array
    {
        try {
            $response = $this->client()->post($this->baseUrl . '/api/kelp/premium-calculate', $payload);
            $data = $response->json();

            if ($response->failed() || ($data['error_code'] ?? 1) !== 0) {
                Log::error('Suretech premium calculation failed.', ['payload' => $payload, 'response' => $data]);
                throw new \RuntimeException($data['message'] ?? 'Unable to calculate premium at this time.');
            }

            return $data['data'];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Suretech premium endpoint unreachable.', ['message' => $e->getMessage()]);
            throw new \RuntimeException('Premium service is unreachable. Please try again shortly.');
        }
    }

    /**
     * @throws \RuntimeException
     */
    public function verifyMotor(array $payload): array
    {
        try {
            $response = $this->client()->post($this->baseUrl . '/api/kelp/motor-verify', $payload);
            $data = $response->json();

            if ($response->failed() || ($data['error_code'] ?? 1) !== 0) {
                Log::error('Suretech motor verification failed.', ['payload' => $payload, 'response' => $data]);
                throw new \RuntimeException($data['message'] ?? 'Unable to verify vehicle details.');
            }

            return $data['data'];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Suretech verification endpoint unreachable.', ['message' => $e->getMessage()]);
            throw new \RuntimeException('Verification service is unreachable. Please try again shortly.');
        }
    }
}
