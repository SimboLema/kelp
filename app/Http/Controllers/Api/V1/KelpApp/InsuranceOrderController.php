<?php

namespace App\Http\Controllers\Api\V1\KelpApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\InsuranceOrder;
use App\Models\IpfPlan;
use App\Services\SuretechService;
use App\Services\IpfService;

class InsuranceOrderController extends Controller
{
    protected SuretechService $suretech;
    protected IpfService $ipf;

    public function __construct(SuretechService $suretech, IpfService $ipf)
    {
        $this->suretech = $suretech;
        $this->ipf = $ipf;
    }

    // ---- Catalog passthroughs (all live on Suretech already) ----

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

    // ---- Orders ----

    public function myOrders()
    {
        $orders = InsuranceOrder::query()->where('user_id', Auth::id())->latest()->get();
        return response()->json(['success' => true, 'data' => $orders]);
    }

    public function show($id)
    {
        $order = InsuranceOrder::query()->where('user_id', Auth::id())
            ->with('ipfPlan.transactions')
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
            'customer.postal_address'        => 'required|string',
            'customer.fax'                   => 'nullable|string',

            'motor_category'       => 'required|in:1,2',
            'registration_number'  => 'required|string',
            'chassis_number'       => 'nullable|string',
            'motor_usage_id'       => 'required|integer',
            'owner_category_id'    => 'required|integer',
            'motor_type_id'        => 'required|integer',
            'sitting_capacity'     => 'nullable|integer',

            'payment_mode' => 'required|in:cash,ipf',

            'insurer_name'    => 'nullable|string|max:255',
            'insurance_name'  => 'nullable|string|max:255',
            'product_name'    => 'nullable|string|max:255',
            'coverage_name'   => 'nullable|string|max:255',
            'addon_ids'       => 'nullable|array',
        ]);

        // 1. Verify vehicle against TIRA (never trust client-cached data)
        try {
            $vehicle = $this->suretech->verifyMotor([
                'motor_category' => $request->motor_category,
                'motor_registration_number' => $request->registration_number,
                'motor_chassis_number' => $request->chassis_number,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => 'Vehicle verification failed: ' . $e->getMessage()], 502);
        }

        // 2. Calculate premium
        // NOTE: Suretech's /api/kelp/premium-calculate currently has a bug on the
        // motor path (motorPremiumCalculation() is typed to accept a Request but
        // is called with scalars, throwing a TypeError that gets swallowed into a
        // generic "Unable to calculate premium." message). Since Suretech can't be
        // modified here, that failure will surface as a 502 below until it's fixed
        // on that side — this is not something Kelp can work around.
        try {
            $premiumResult = $this->suretech->calculatePremium([
                'coverage_id'            => $request->coverage_id,
                'sum_insured'            => $request->sum_insured,
                'cover_note_duration_id' => $request->cover_note_duration_id,
                'motor_usage_id'         => $request->motor_usage_id,
                'sitting_capacity'       => $request->sitting_capacity,
                'addon_ids'              => $request->addon_ids ?? [],
            ]);
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
            'coverage_id'         => $request->coverage_id,
            'insurer_name'        => $request->insurer_name,
            'insurance_name'      => $request->insurance_name,
            'product_name'        => $request->product_name,
            'coverage_name'       => $request->coverage_name,
            'sum_insured'         => $request->sum_insured,
            'premium'             => $totalPremium,
            'premium_breakdown'   => json_encode($premiumResult),
            'customer_details'    => json_encode($request->input('customer')),
            'motor_details'       => json_encode($vehicle),
            'cover_note_start_date' => $request->cover_note_start_date,
            'cover_note_end_date'   => $request->cover_note_end_date,
            'payment_mode'        => $request->payment_mode,
            'registration_number' => $vehicle['registration_number'],
            'status'              => 'Pending',
            'transmission_status' => 'Pending',
        ]);

        // 4. Create the IPF plan (Kelp-owned, entirely) if applicable
        $ipfPlan = null;
        if ($request->payment_mode === 'ipf') {
            try {
                $ipfPlan = $this->ipf->createPlan($order, $totalPremium);
            } catch (\RuntimeException $e) {
                // Order stays saved even if IPF setup fails — don't lose the order over this.
                $order->update(['status' => 'IPF setup failed: ' . $e->getMessage()]);
            }
        }

        // 5. Transmit to Suretech using ONLY the fields its live endpoint accepts.
        // Everything else (KYC, motor detail, IPF summary) is packed into
        // `description` as a readable block, since that's the only free-text
        // field available to carry extra context to whoever reviews the order
        // on the Suretech side.
        $descriptionParts = [
            "Order Reference: {$order->reference_no}",
            "Payment Mode: " . strtoupper($request->payment_mode),
        ];

        if ($ipfPlan) {
            $descriptionParts[] = sprintf(
                "IPF Plan — Total: %s, Down payment (%.2f%%): %s, Financed: %s, Daily installment: %s",
                number_format($totalPremium, 2),
                $ipfPlan->down_payment_percent,
                number_format($ipfPlan->down_payment_amount, 2),
                number_format($ipfPlan->financed_amount, 2),
                number_format($ipfPlan->daily_installment, 2)
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

        $descriptionParts[] = "--- Vehicle ---";
        $descriptionParts[] = "Reg No: {$vehicle['registration_number']}, Chassis: {$vehicle['chassis_number']}";
        $descriptionParts[] = "{$vehicle['make']} {$vehicle['model']} ({$vehicle['year_of_manufacture']}), {$vehicle['color']}, {$vehicle['body_type']}";
        $descriptionParts[] = "Engine: {$vehicle['engine_number']} ({$vehicle['engine_capacity']}cc, {$vehicle['fuel_used']})";

        $fullDescription = implode("\n", $descriptionParts);

        try {
            $this->suretech->submitOrder([
                'reference_no'   => $order->reference_no,
                'customer_name'  => Auth::user()->name ?? null,
                'customer_phone' => Auth::user()->phone ?? null,
                'customer_email' => Auth::user()->email ?? null,
                'insurance'      => $request->insurance_name,
                'product'        => $request->product_name,
                'coverage'       => $request->coverage_name,
                'description'    => $fullDescription,
                'created_at'     => $order->created_at,
            ]);
            $order->update(['transmission_status' => 'Sent']);
        } catch (\RuntimeException $e) {
            $order->update(['transmission_status' => 'Failed']);
            // Order and IPF plan stay saved locally regardless — Kelp never loses
            // an order over a downstream transmission failure.
        }

        return response()->json([
            'success' => true,
            'message' => 'Insurance order submitted successfully.'
                . ($request->payment_mode === 'ipf' ? ' IPF plan created.' : ''),
            'data' => [
                'order'    => $order,
                'ipf_plan' => $ipfPlan,
            ],
        ], 201);
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

    // ---- IPF payments ----

    public function recordIpfPayment(Request $request, $orderId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'note'   => 'nullable|string|max:255',
        ]);

        $order = InsuranceOrder::query()->where('user_id', Auth::id())->findOrFail($orderId);
        $plan = IpfPlan::query()->where('insurance_order_id', $order->id)->firstOrFail();

        try {
            $transaction = $this->ipf->recordPayment($plan, $request->amount, $request->note);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'transaction' => $transaction,
                'plan' => $plan->fresh(),
            ],
        ]);
    }

    public function ipfPlan($orderId)
    {
        $order = InsuranceOrder::query()->where('user_id', Auth::id())->findOrFail($orderId);
        $plan = IpfPlan::with('transactions')->where('insurance_order_id', $order->id)->firstOrFail();

        return response()->json(['success' => true, 'data' => $plan]);
    }
}
