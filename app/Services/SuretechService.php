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
     * Matches KelpCatalogController's envelope: {success, data} / {success, message}.
     *
     * @throws \RuntimeException
     */
    protected function get(string $endpoint, array $query = []): array
    {
        try {
            $response = $this->client()->get($this->baseUrl . $endpoint, $query);
            $data = $response->json();

            if ($response->failed() || !($data['success'] ?? false)) {
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
     * NOTE: DispatchMotorVerificationController on Suretech uses a different
     * envelope than the other kelp endpoints — {success, message, response, generated_xml}
     * instead of {error_code, message, data} — and returns raw TIRA XML rather
     * than parsed fields. This method matches that reality and parses the XML here.
     *
     * @throws \RuntimeException
     */
    public function verifyMotor(array $payload): array
    {
        try {
            $response = $this->client()->post($this->baseUrl . '/api/kelp/motor-verify', $payload);
            $data = $response->json();

            if ($response->failed() || !($data['success'] ?? false)) {
                Log::error('Suretech motor verification failed.', ['payload' => $payload, 'response' => $data]);
                throw new \RuntimeException($data['message'] ?? $data['error'] ?? 'Unable to verify vehicle details.');
            }

            return $this->parseTiraMotorXml($data);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Suretech verification endpoint unreachable.', ['message' => $e->getMessage()]);
            throw new \RuntimeException('Verification service is unreachable. Please try again shortly.');
        }
    }

    /**
     * Extracts the vehicle detail fields from the raw TIRA XML embedded in
     * Suretech's motor-verify response ($data['response']['response']).
     *
     * @throws \RuntimeException
     */
    protected function parseTiraMotorXml(array $data): array
    {
        $xmlString = $data['response']['response']
            ?? $data['response']
            ?? null;

        if (empty($xmlString) || !is_string($xmlString)) {
            Log::error('Suretech motor verification returned no XML payload.', ['response' => $data]);
            throw new \RuntimeException('Vehicle verification did not return any data.');
        }

        $xml = @simplexml_load_string($xmlString);

        if ($xml === false) {
            Log::error('Could not parse TIRA XML from Suretech response.', ['xml' => $xmlString]);
            throw new \RuntimeException('Unable to read vehicle verification response.');
        }

        $header = $xml->MotorVerificationRes->VerificationHdr ?? null;
        $detail = $xml->MotorVerificationRes->VerificationDtl ?? null;

        if (!$header || !$detail) {
            Log::error('Unexpected TIRA XML structure from Suretech.', ['xml' => $xmlString]);
            throw new \RuntimeException('Unexpected vehicle verification response format.');
        }

        $statusDesc = (string) $header->ResponseStatusDesc;

        if (strcasecmp($statusDesc, 'Successful') !== 0) {
            throw new \RuntimeException($statusDesc ?: 'Vehicle verification was not successful.');
        }

        return [
            'registration_number' => (string) $detail->RegistrationNumber,
            'chassis_number'      => (string) $detail->ChassisNumber,
            'make'                => (string) $detail->Make,
            'model'               => (string) $detail->Model,
            'model_number'        => (string) $detail->ModelNumber,
            'body_type'           => (string) $detail->BodyType,
            'color'               => (string) $detail->Color,
            'engine_number'       => (string) $detail->EngineNumber,
            'engine_capacity'     => (int) $detail->EngineCapacity,
            'fuel_used'           => (string) $detail->FuelUsed,
            'number_of_axles'     => (int) $detail->NumberOfAxles,
            'axle_distance'       => (int) $detail->AxleDistance,
            'sitting_capacity'    => (int) $detail->SittingCapacity,
            'year_of_manufacture' => (int) $detail->YearOfManufacture,
            'tare_weight'         => (int) $detail->TareWeight,
            'gross_weight'        => (int) $detail->GrossWeight,
            'motor_usage'         => (string) $detail->MotorUsage,
            'owner_category'      => (string) $detail->OwnerCategory,
        ];
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
     * @throws \RuntimeException
     */
    public function getMotorCategories(): array
    {
        return $this->get('/api/kelp/motor-categories');
    }

    /**
     * @throws \RuntimeException
     */
    public function getCoverNoteDurations(): array
    {
        return $this->get('/api/kelp/cover-note-durations');
    }

    public function getCountries(){
        return $this->get('/api/kelp/countries');
    }
        public function getRegions(): array
    {
        return $this->get('/api/kelp/regions');
    }

    public function getDistricts(int $regionId): array
    {
        return $this->get('/api/kelp/districts', ['region_id' => $regionId]);
    }

    public function getPolicyHolderTypes(): array
    {
        return $this->get('/api/kelp/policy-holder-types');
    }

    public function getPolicyHolderIdTypes(): array
    {
        return $this->get('/api/kelp/policy-holder-id-types');
    }

    public function getMotorUsage(){
        return $this->get('/api/kelp/motor-usage');
    }

    public function getOwnerCategory(){
        return $this->get('/api/kelp/owner-category');
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
