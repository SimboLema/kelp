<?php

namespace App\Http\Controllers\Api\V1\KelpApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\InsuranceOrder;
use App\Models\IpfAccount;
use App\Models\IpfPlan;
use App\Services\SuretechService;
use App\Services\IpfService;
use Illuminate\Support\Carbon;

class InsuranceOrderController extends Controller
{
    protected SuretechService $suretech;
    protected IpfService $ipf;

    public function __construct(SuretechService $suretech, IpfService $ipf)
    {
        $this->suretech = $suretech;
        $this->ipf = $ipf;
    }



    public function insurers()
    {
        try {
            $insurers = $this->suretech->getInsurers();
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }
        return response()->json(['success' => true, 'data' => $insurers]);
    }

    public function insurances(Request $request)
    {
        try {
            $insurances = $this->suretech->getInsuranceTypes($request->query('insurer_id'));
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }
        return response()->json(['success' => true, 'data' => $insurances]);
    }

    public function products($insuranceId)
    {
        try {
            $products = $this->suretech->getProducts((int) $insuranceId);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }
        return response()->json(['success' => true, 'data' => $products]);
    }

    public function coverages($productId)
    {
        try {
            $coverages = $this->suretech->getCoverages((int) $productId);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }
        return response()->json(['success' => true, 'data' => $coverages]);
    }

    public function motorCategories()
    {
        try {
            $motorCategories = $this->suretech->getMotorCategories();
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }
        return response()->json(['success' => true, 'data' => $motorCategories]);
    }

    public function coverNoteDurations()
    {
        try {
            $durations = $this->suretech->getCoverNoteDurations();
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }
        return response()->json(['success' => true, 'data' => $durations]);
    }

    public function countries()
    {
        try {
            $countries = $this->suretech->getCountries();
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }
        return response()->json(['success' => true, 'data' => $countries]);
    }

    public function regions()
    {
        try {
            $regions = $this->suretech->getRegions();
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }
        return response()->json(['success' => true, 'data' => $regions]);
    }

    public function districts($regionId)
    {
        try {
            $districts = $this->suretech->getDistricts((int) $regionId);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }
        return response()->json(['success' => true, 'data' => $districts]);
    }

    public function policyHolderTypes()
    {
        try {
            $types = $this->suretech->getPolicyHolderTypes();
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }
        return response()->json(['success' => true, 'data' => $types]);
    }

    public function policyHolderIdTypes()
    {
        try {
            $types = $this->suretech->getPolicyHolderIdTypes();
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }
        return response()->json(['success' => true, 'data' => $types]);
    }

    public function motorUsage(){
        try{
            $motorUsage = $this->suretech->getMotorUsage();
        }
        catch(\RuntimeException $e){
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }
        return response()->json(['success' => true, 'data' => $motorUsage]);
    }

    public function ownerCategory(){
        try{
            $ownerCategory= $this->suretech->getOwnerCategory();
        }
        catch(\RuntimeException $e){
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }
        return response()->json(['success' => true, 'data' => $ownerCategory]);
    }



    public function ipfPlans()
    {
        $plans = IpfPlan::active()->orderBy('duration_days')->get();
        return response()->json(['success' => true, 'data' => $plans]);
    }



    public function myOrders()
    {
        $orders = InsuranceOrder::query()->where('user_id', Auth::id())->latest()->get();
        return response()->json(['success' => true, 'data' => $orders]);
    }

    public function show($id)
    {
        $order = InsuranceOrder::query()->where('user_id', Auth::id())
            ->with(['ipfAccount.installments', 'ipfAccount.payments'])
            ->findOrFail($id);

        return response()->json(['success' => true, 'data' => $order]);
    }

    public function store(Request $request)
{
    $request->validate([
        'coverage_id'             => 'required|integer',
        'sum_insured'             => 'required|numeric|min:1',
        'cover_note_duration_id'  => 'required|integer',
        'cover_note_start_date'   => 'required|date',
        'cover_note_end_date'     => 'required|date|after:cover_note_start_date',

        'customer.dob'                   => 'required|date',
        'customer.policy_holder_type_id' => 'required|integer',
        'customer.id_number'             => 'required|string',
        'customer.id_type_id'            => 'required|integer',
        'customer.gender'                => 'required|in:M,F',
        'customer.country_id'            => 'required|integer',
        'customer.region_id'             => 'required|integer',
        'customer.district_id'           => 'required|integer',
        'customer.street'                => 'nullable|string',
        'customer.postal_address'        => 'nullable|string',
        'customer.fax'                   => 'nullable|string',

        'motor_category'       => 'nullable|in:1,2',
        'registration_number'  => 'nullable|string',
        'chassis_number'       => 'nullable|string',
        'motor_usage_id'       => 'nullable|integer',
        'owner_category_id'    => 'nullable|integer',
        'motor_type_id'        => 'nullable|integer',
        'sitting_capacity'     => 'nullable|integer',

        'payment_mode' => 'required|in:cash,ipf',
        'ipf_plan_id'  => 'required_if:payment_mode,ipf|integer|exists:ipf_plans,id',

        'insurer_id'      => 'required|integer',
        'insurance_id'    => 'nullable|integer',
        'product_id'      => 'nullable|integer',
        'insurer_name'    => 'nullable|string|max:255',
        'insurance_name'  => 'nullable|string|max:255',
        'product_name'    => 'nullable|string|max:255',
        'coverage_name'   => 'nullable|string|max:255',
        'addon_ids'       => 'nullable|array',
    ]);

    $isMotorInsurance = $this->isMotorInsurance($request);
    $vehicle = null;

    // 1. Verify vehicle against TIRA only for motor insurance.
    if ($isMotorInsurance) {
        $request->validate([
            'motor_category'       => 'required|in:1,2',
            'registration_number'  => 'required|string',
            'motor_usage_id'       => 'required|integer',
            'owner_category_id'    => 'required|integer',
            'motor_type_id'        => 'required|integer',
        ]);

        try {
            $vehicle = $this->suretech->verifyMotor([
                'motor_category' => $request->motor_category,
                'motor_registration_number' => $request->registration_number,
                'motor_chassis_number' => $request->chassis_number,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => 'Vehicle verification failed: ' . $e->getMessage()], 502);
        }
    }

    // 2. Calculate premium
    // NOTE: Suretech's /api/kelp/premium-calculate currently has a bug on the
    // motor path (motorPremiumCalculation() is typed to accept a Request but
    // is called with scalars, throwing a TypeError that gets swallowed into a
    // generic "Unable to calculate premium." message). Since Suretech can't be
    // modified here, that failure will surface as a 502 below until it's fixed
    // on that side — this is not something Kelp can work around.
    try {
        $premiumPayload = [
            'coverage_id'            => $request->coverage_id,
            'sum_insured'            => $request->sum_insured,
            'cover_note_duration_id' => $request->cover_note_duration_id,
            'addon_ids'              => $request->addon_ids ?? [],
        ];

        if ($isMotorInsurance) {
            $premiumPayload['motor_usage_id'] = $request->motor_usage_id;
            $premiumPayload['sitting_capacity'] = $request->sitting_capacity;
        }

        $premiumResult = $this->suretech->calculatePremium($premiumPayload);
        $totalPremium = $premiumResult['total_premium_including_tax'];
    } catch (\RuntimeException $e) {
        return response()->json(['success' => false, 'message' => 'Premium calculation failed: ' . $e->getMessage()], 502);
    }

    // 3. Save the full order locally — Kelp is the source of truth for
    // customer KYC and motor detail, since Suretech's live endpoint can't
    // accept or store them.
    $order = InsuranceOrder::create([
        'reference_no'        => InsuranceOrder::generateReference(),
        'user_id'             => Auth::id(),
        'insurer_id'          => $request->insurer_id,
        'insurance_id'        => $request->insurance_id,
        'product_id'          => $request->product_id,
        'coverage_id'         => $request->coverage_id,
        'insurer_name'        => $request->insurer_name,
        'insurance_name'      => $request->insurance_name,
        'product_name'        => $request->product_name,
        'coverage_name'       => $request->coverage_name,
        'sum_insured'         => $request->sum_insured,
        'premium'             => $totalPremium,
        'premium_breakdown'   => json_encode($premiumResult),
        'customer_details'    => json_encode($request->input('customer')),
        'motor_details'       => $vehicle ? json_encode($vehicle) : null,
        'cover_note_start_date' => $request->cover_note_start_date,
        'cover_note_end_date'   => $request->cover_note_end_date,
        'payment_mode'        => $request->payment_mode,
        'registration_number' => $vehicle['registration_number'] ?? null,
        'status'              => 'Pending',
        'transmission_status' => 'Pending',
    ]);

    // 4. Open the IPF account (Kelp-owned, entirely) if applicable, using
    // whichever plan the customer picked.
    $ipfAccount = null;
    if ($request->payment_mode === 'ipf') {
        $plan = IpfPlan::active()->find($request->ipf_plan_id);

        if (! $plan) {
            $order->update(['status' => 'IPF setup failed: selected plan is not available.']);
        } else {
            try {
                $ipfAccount = $this->ipf->createPlan($order, $plan, $totalPremium);
            } catch (\RuntimeException $e) {
                // Order stays saved even if IPF setup fails — don't lose the order over this.
                $order->update(['status' => 'IPF setup failed: ' . $e->getMessage()]);
            }
        }
    }

    // 5. Transmit to Suretech using the nested structure expected by its
    // IncomingInsuranceOrderController.
    $descriptionParts = [
        "Order Reference: {$order->reference_no}",
        "Payment Mode: " . strtoupper($request->payment_mode),
    ];

    if ($ipfAccount) {
        $descriptionParts[] = sprintf(
            "IPF Plan — Total: %s, Down payment (%.2f%%): %s, Financed: %s, Duration: %d day(s)",
            number_format($totalPremium, 2),
            $ipfAccount->down_payment_percent,
            number_format($ipfAccount->down_payment_amount, 2),
            number_format($ipfAccount->financed_amount, 2),
            $ipfAccount->plan->duration_days ?? 0
        );
    }

    $descriptionParts[] = "Cover Note Period: {$request->cover_note_start_date} to {$request->cover_note_end_date}";
    $descriptionParts[] = "Sum Insured: " . number_format($request->sum_insured, 2);
    $descriptionParts[] = "Total Premium: " . number_format($totalPremium, 2);

    $descriptionParts[] = "--- Customer ---";
    $descriptionParts[] = "DOB: {$request->input('customer.dob')}";
    $descriptionParts[] = "ID Type/Number: {$request->input('customer.id_type_id')} / {$request->input('customer.id_number')}";
    $descriptionParts[] = "Gender: {$request->input('customer.gender')}";
    $descriptionParts[] = "District/Region/Country IDs: {$request->input('customer.district_id')} / {$request->input('customer.region_id')} / {$request->input('customer.country_id')}";
    $descriptionParts[] = "Street: {$request->input('customer.street')}";
    $descriptionParts[] = "Postal Address: {$request->input('customer.postal_address')}";

    if ($vehicle) {
        $descriptionParts[] = "--- Vehicle ---";
        $descriptionParts[] = "Reg No: {$vehicle['registration_number']}, Chassis: {$vehicle['chassis_number']}";
        $descriptionParts[] = "{$vehicle['make']} {$vehicle['model']} ({$vehicle['year_of_manufacture']}), {$vehicle['color']}, {$vehicle['body_type']}";
        $descriptionParts[] = "Engine: {$vehicle['engine_number']} ({$vehicle['engine_capacity']}cc, {$vehicle['fuel_used']})";
    }

    $fullDescription = implode("\n", $descriptionParts);

    $user = Auth::user();
    $suretechCoverNoteStartDate = Carbon::parse(
        $request->cover_note_start_date
    )->startOfDay()->format('Y-m-d H:i:s');
    $suretechCoverNoteEndDate = Carbon::parse(
        $request->cover_note_end_date
    )->startOfDay()->format('Y-m-d H:i:s');

    $submitPayload = [
        'reference_no' => $order->reference_no,
        'insurer_id'   => $request->insurer_id,
        'customer' => [
            'name'                  => $user?->name,
            'dob'                   => $request->input('customer.dob'),
            'policy_holder_type_id' => $request->input('customer.policy_holder_type_id'),
            'id_number'             => $request->input('customer.id_number'),
            'id_type_id'            => $request->input('customer.id_type_id'),
            'gender'                => $request->input('customer.gender'),
            'country_id'            => $request->input('customer.country_id'),
            'region_id'             => $request->input('customer.region_id'),
            'district_id'           => $request->input('customer.district_id'),
            'phone'                 => $user?->phone_number,
            'email'                 => $user?->email,
            'street'                => $request->input('customer.street'),
            'postal_address'        => $request->input('customer.postal_address'),
            'fax'                   => $request->input('customer.fax'),
        ],
        'coverage_id'           => $request->coverage_id,
        'sum_insured'           => $request->sum_insured,
        'cover_note_start_date' => $suretechCoverNoteStartDate,
        'cover_note_end_date'   => $suretechCoverNoteEndDate,
        'payment_mode'          => $request->payment_mode,
        'ipf_plan_id'           => $request->payment_mode === 'ipf'
            ? $request->ipf_plan_id
            : null,
        'ipf' => $request->payment_mode === 'ipf' && $ipfAccount ? [
            'down_payment'    => $ipfAccount->down_payment_amount,
            'financed_amount' => $ipfAccount->financed_amount,
        ] : null,
        'insurance'             => $request->insurance_name,
        'product'               => $request->product_name,
        'coverage'              => $request->coverage_name,
        'description'           => $fullDescription,
        'created_at'            => $order->created_at,
    ];

    if ($vehicle) {
        $submitPayload['motor'] = [
            'registration_number' => $vehicle['registration_number'],
            'chassis_number'      => $vehicle['chassis_number'],
            'make'                => $vehicle['make'],
            'model'               => $vehicle['model'],
            'model_number'        => $vehicle['model_number'],
            'body_type'           => $vehicle['body_type'],
            'color'               => $vehicle['color'],
            'engine_number'       => $vehicle['engine_number'],
            'engine_capacity'     => $vehicle['engine_capacity'],
            'fuel_used'           => $vehicle['fuel_used'],
            'number_of_axles'     => $vehicle['number_of_axles'],
            'axle_distance'       => $vehicle['axle_distance'],
            'sitting_capacity'    => $vehicle['sitting_capacity'],
            'year_of_manufacture' => $vehicle['year_of_manufacture'],
            'tare_weight'         => $vehicle['tare_weight'],
            'gross_weight'        => $vehicle['gross_weight'],
            // Using the TIRA-verified text values here (not Kelp's local
            // motor_usage_id/owner_category_id), since Suretech resolves
            // these by NAME against its own motor_usages/owner_categories
            // lookup tables, not by Kelp's internal IDs.
            'motor_usage'         => $vehicle['motor_usage'],
            'owner_category'      => $vehicle['owner_category'],
            'motor_category_id'   => $request->motor_category,
            'motor_type_id'       => $request->motor_type_id,
        ];
    }

    $transmissionSucceeded = $this->transmitOrderToSuretech($order, $submitPayload);
    $order->refresh();

    return response()->json([
        'success' => true,
        'message' => $transmissionSucceeded
            ? 'Insurance order submitted successfully.'
                . ($request->payment_mode === 'ipf' ? ' IPF account created.' : '')
            : $this->transmissionFailedMessage(),
        'data' => [
            'order'      => $order,
            'ipf_account' => $ipfAccount,
            'requires_resend' => ! $transmissionSucceeded,
        ],
    ], 201);
}

    public function resend($id)
    {
        $order = InsuranceOrder::query()
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if ($order->transmission_status === 'Sent') {
            return response()->json([
                'success' => true,
                'message' => 'Order already sent to insurer.',
                'data' => ['order' => $order],
            ]);
        }

        $payload = $order->request_payload;

        if (! is_array($payload) || empty($payload)) {
            return response()->json([
                'success' => false,
                'message' => 'This paid order cannot be resent automatically. Please contact support.',
                'data' => ['order' => $order],
            ], 422);
        }

        $payload['reference_no'] = $order->reference_no;

        $transmissionSucceeded = $this->transmitOrderToSuretech($order, $payload);
        $order->refresh();

        return response()->json([
            'success' => $transmissionSucceeded,
            'message' => $transmissionSucceeded
                ? 'Order resent successfully.'
                : $this->transmissionFailedMessage(),
            'data' => [
                'order' => $order,
                'requires_resend' => ! $transmissionSucceeded,
            ],
        ]);
    }

    private function transmitOrderToSuretech(InsuranceOrder $order, array $payload): bool
    {
        if ($order->transmission_status === 'Sent') {
            return true;
        }

        $order->update([
            'description' => $payload['description'] ?? $order->description,
            'request_payload' => $payload,
            'transmission_status' => 'Pending',
            'last_error' => null,
        ]);

        try {
            $responsePayload = $this->suretech->submitOrder($payload);

            $order->update([
                'status' => 'Submitted',
                'transmission_status' => 'Sent',
                'response_payload' => $responsePayload,
                'sent_at' => now(),
                'last_error' => null,
            ]);

            app(\App\Services\PointsService::class)->awardForPurchase($order->fresh());

            return true;
        } catch (\RuntimeException $e) {
            $order->update([
                'status' => 'Processing',
                'transmission_status' => 'Failed',
                'retry_count' => ((int) $order->retry_count) + 1,
                'last_error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function transmissionFailedMessage(): string
    {
        return 'Payment received. Order saved, but sending to insurer failed. Please resend without making another payment.';
    }

    private function isMotorInsurance(Request $request): bool
    {
        if ((int) $request->input('insurance_id') === 1) {
            return true;
        }

        $insuranceText = strtolower(implode(' ', [
            (string) $request->input('insurance_name', ''),
            (string) $request->input('product_name', ''),
            (string) $request->input('coverage_name', ''),
        ]));

        return str_contains($insuranceText, 'motor') ||
            str_contains($insuranceText, 'vehicle');
    }

    public function verifyMotor(Request $request)
    {
        $request->validate([
            'motor_category' => 'required|in:1,2',
            'registration_number' => 'required|string',
            'chassis_number' => 'nullable|string',
        ]);

        try {
            $vehicle = $this->suretech->verifyMotor([
                'motor_category' => $request->motor_category,
                'motor_registration_number' => $request->registration_number,
                'motor_chassis_number' => $request->chassis_number,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }

        return response()->json(['success' => true, 'data' => $vehicle]);
    }

    public function calculatePremium(Request $request)
    {
        $request->validate([
            'coverage_id'            => 'required|integer',
            'sum_insured'            => 'required|numeric|min:1',
            'cover_note_duration_id' => 'required|integer',
            'motor_usage_id'         => 'nullable|integer',
            'sitting_capacity'       => 'nullable|integer',
            'addon_ids'              => 'nullable|array',
        ]);

        try {
            $result = $this->suretech->calculatePremium([
                'coverage_id'            => $request->coverage_id,
                'sum_insured'            => $request->sum_insured,
                'cover_note_duration_id' => $request->cover_note_duration_id,
                'motor_usage_id'         => $request->motor_usage_id,
                'sitting_capacity'       => $request->sitting_capacity,
                'addon_ids'              => $request->addon_ids ?? [],
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 502);
        }

        return response()->json(['success' => true, 'data' => $result]);
    }

    // ---- IPF: customer-facing account + payments ----

    public function myIpfAccounts()
    {
        $accounts = IpfAccount::with(['plan', 'order:id,reference_no,registration_number'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json(['success' => true, 'data' => $accounts]);
    }

    public function ipfAccount($orderId)
    {
        $order = InsuranceOrder::query()->where('user_id', Auth::id())->findOrFail($orderId);

        $account = IpfAccount::with([
                'plan',
                'installments' => fn ($q) => $q->orderBy('installment_number'),
                'payments' => fn ($q) => $q->latest(),
            ])
            ->where('insurance_order_id', $order->id)
            ->firstOrFail();

        return response()->json(['success' => true, 'data' => $account]);
    }

    public function recordIpfPayment(Request $request, $orderId)
    {
        $request->validate([
            'amount'         => 'required|numeric|min:0.01',
            'note'           => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|max:50',
        ]);

        $order = InsuranceOrder::query()->where('user_id', Auth::id())->findOrFail($orderId);
        $account = IpfAccount::query()->where('insurance_order_id', $order->id)->firstOrFail();

        try {
            $payment = $this->ipf->recordPayment($account, $request->amount, $request->note, $request->payment_method);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'payment' => $payment,
                'account' => $account->fresh(['installments', 'payments']),
            ],
        ]);
    }

}
