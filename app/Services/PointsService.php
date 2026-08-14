<?php

namespace App\Services;

use App\Models\InsuranceOrder;
use App\Models\PointsSetting;
use App\Models\PointsTransaction;
use App\Models\RedemptionRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PointsService
{
    public function balance(User $user): int
    {
        return (int) PointsTransaction::where('user_id', $user->id)->sum('points');
    }

    /**
     * Awards points for a completed insurance purchase, and — if this is the
     * user's first-ever order and they were referred by someone — also
     * awards the referrer their flat referral bonus.
     */
    public function awardForPurchase(InsuranceOrder $order): void
    {
        $settings = PointsSetting::current();
        $user = $order->user;

        $points = intdiv((int) $order->premium, $settings->amount_unit_tzs) * $settings->points_per_amount_unit;

        if ($points > 0) {
            $this->credit($user, $points, 'purchase', $order, "Purchase points for order {$order->reference_no}");
        }

        $isFirstOrder = InsuranceOrder::where('user_id', $user->id)
            ->where('transmission_status', 'Sent')
            ->count() === 1; // this order is the only successful one so far

        if ($isFirstOrder && $user->referred_by_user_id) {
            $referrer = User::find($user->referred_by_user_id);
            if ($referrer) {
                $this->credit(
                    $referrer,
                    $settings->referral_points,
                    'referral',
                    $order,
                    "Referral bonus: {$user->name} completed their first purchase."
                );
            }
        }
    }

    /**
     * @throws \RuntimeException
     */
    public function requestRedemption(User $user, int $points, string $method, ?string $payoutDetails): RedemptionRequest
    {
        $settings = PointsSetting::current();

        if ($points < $settings->min_redeemable_points) {
            throw new \RuntimeException("Minimum redeemable points is {$settings->min_redeemable_points}.");
        }

        if ($this->balance($user) < $points) {
            throw new \RuntimeException('Insufficient points balance.');
        }

        return DB::transaction(function () use ($user, $points, $method, $payoutDetails, $settings) {
            $amount = round($points * $settings->redemption_rate_tzs_per_point, 2);

            $request = RedemptionRequest::create([
                'user_id' => $user->id,
                'points_redeemed' => $points,
                'amount_tzs' => $amount,
                'method' => $method,
                'payout_details' => $payoutDetails,
                'status' => 'pending',
            ]);

            $this->debit($user, $points, 'redemption', $request, "Redemption request #{$request->id} ({$method}).");

            return $request;
        });
    }

    /**
     * Admin approves/marks paid — no points movement, just status.
     */
    public function markProcessed(RedemptionRequest $request, string $status, User $admin, ?string $note = null): void
    {
        $request->update([
            'status' => $status,
            'processed_by' => $admin->id,
            'processed_at' => now(),
            'admin_note' => $note,
        ]);
    }

    /**
     * Admin rejects — refunds the reserved points back to the user.
     */
    public function reject(RedemptionRequest $request, User $admin, string $reason): void
    {
        DB::transaction(function () use ($request, $admin, $reason) {
            $this->credit(
                $request->user,
                $request->points_redeemed,
                'redemption_refund',
                $request,
                "Refund for rejected redemption #{$request->id}: {$reason}"
            );

            $this->markProcessed($request, 'rejected', $admin, $reason);
        });
    }

    protected function credit(User $user, int $points, string $type, $reference, string $note): PointsTransaction
    {
        $newBalance = $this->balance($user) + $points;

        return PointsTransaction::create([
            'user_id' => $user->id,
            'type' => $type,
            'points' => $points,
            'balance_after' => $newBalance,
            'reference_type' => get_class($reference),
            'reference_id' => $reference->id,
            'note' => $note,
        ]);
    }

    protected function debit(User $user, int $points, string $type, $reference, string $note): PointsTransaction
    {
        $newBalance = $this->balance($user) - $points;

        return PointsTransaction::create([
            'user_id' => $user->id,
            'type' => $type,
            'points' => -$points,
            'balance_after' => $newBalance,
            'reference_type' => get_class($reference),
            'reference_id' => $reference->id,
            'note' => $note,
        ]);
    }
}