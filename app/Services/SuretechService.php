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
     * Shared GET helper for catalog/reference-data endpoints.
     *
     * @throws \RuntimeException
     */
    protected function get(string $endpoint, array $query = []): array
    {
        try {
            $response = $this->client()->get($this->baseUrl . $endpoint, $query);
            $data = $response->json();

            if ($response->failed() || ($data['error_code'] ?? 1) !== 0) {
                Log::error('Suretech request failed.', [
                    'endpoint' => $endpoint,
                    'query' => $query,
                    'response' => $data,
                ]);
                throw new \RuntimeException($data['message'] ?? 'Unable to fetch data from Suretech.');
            }

            return $data['data'];
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Suretech endpoint unreachable.', ['endpoint' => $endpoint, 'message' => $e->getMessage()]);
            throw new \RuntimeException('Suretech service is unreachable. Please try again shortly.');
        }
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

    /**
     * @throws \RuntimeException
     */
    public function getInsurers(): array
    {
        return $this->get('/api/kelp/insurers');
    }

    /**
     * @throws \RuntimeException
     */
    public function getInsuranceTypes(?int $insurerId = null): array
    {
        return $this->get('/api/kelp/insurances', array_filter(['insurer_id' => $insurerId]));
    }

    /**
     * @throws \RuntimeException
     */
    public function getProducts(int $insuranceId): array
    {
        return $this->get('/api/kelp/products', ['insurance_id' => $insuranceId]);
    }

    /**
     * @throws \RuntimeException
     */
    public function getCoverages(int $productId): array
    {
        return $this->get('/api/kelp/coverages', ['product_id' => $productId]);
    }

    /**
     * Push an order to Suretech's IncomingInsuranceOrderController.
     * NOTE: this endpoint uses a different response envelope
     * ('success' / 'data' / 'error') than the other kelp endpoints
     * ('error_code' / 'message' / 'data') — do not reuse get()/the
     * error_code check here.
     *
     * @throws \RuntimeException
     */
    public function submitOrder(array $payload): array
    {
        try {
            $response = $this->client()->post($this->baseUrl . '/api/kelp/orders', $payload);
            $data = $response->json();

            if ($response->failed() || !($data['success'] ?? false)) {
                Log::error('Suretech order submission failed.', ['payload' => $payload, 'response' => $data]);
                throw new \RuntimeException($data['error'] ?? $data['message'] ?? 'Unable to transmit order to Suretech.');
            }

            return $data['data'];
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Suretech order endpoint unreachable.', ['message' => $e->getMessage()]);
            throw new \RuntimeException('Order transmission service is unreachable. Please try again shortly.');
        }
    }
}
