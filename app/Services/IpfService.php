<?php

namespace App\Services;

use App\Models\InsuranceOrder;
use App\Models\IpfPlan;
use App\Models\IpfSetting;
use App\Models\IpfTransaction;
use Illuminate\Support\Facades\DB;

class IpfService
{
    /**
     * Creates an IPF plan for a new order, snapshotting today's admin-set
     * rates onto the plan. Future rate changes never retroactively affect
     * a plan already created.
     *
     * @throws \RuntimeException
     */
    public function createPlan(InsuranceOrder $order, float $totalPremium): IpfPlan
    {
        $settings = IpfSetting::current();

        $downPaymentAmount = round($totalPremium * $settings->down_payment_percent / 100, 2);
        $financedAmount    = round($totalPremium - $downPaymentAmount, 2);
        $dailyInstallment  = round($financedAmount * $settings->daily_rate_percent / 100, 2);

        return DB::transaction(function () use ($order, $totalPremium, $settings, $downPaymentAmount, $financedAmount, $dailyInstallment) {
            $plan = IpfPlan::create([
                'insurance_order_id'   => $order->id,
                'total_premium'        => $totalPremium,
                'down_payment_percent' => $settings->down_payment_percent,
                'down_payment_amount'  => $downPaymentAmount,
                'financed_amount'      => $financedAmount,
                'daily_rate_percent'   => $settings->daily_rate_percent,
                'daily_installment'    => $dailyInstallment,
                'penalty_percent'      => $settings->penalty_percent,
                'outstanding_balance'  => $financedAmount,
                'start_date'           => now()->toDateString(),
                'last_charged_date'    => now()->toDateString(),
                'status'               => 'active',
            ]);

            IpfTransaction::create([
                'ipf_plan_id'      => $plan->id,
                'type'             => 'down_payment',
                'amount'           => $downPaymentAmount,
                'balance_after'    => $financedAmount,
                'transaction_date' => now()->toDateString(),
                'note'             => 'Initial down payment collected at order creation.',
            ]);

            return $plan;
        });
    }

    /**
     * Records a customer payment against their plan. Amount doesn't have to
     * match daily_installment exactly — supports partial or catch-up payments.
     *
     * @throws \RuntimeException
     */
    public function recordPayment(IpfPlan $plan, float $amount, ?string $note = null): IpfTransaction
    {
        if ($plan->status !== 'active') {
            throw new \RuntimeException('Cannot record a payment against a plan that is not active.');
        }

        if ($amount <= 0) {
            throw new \RuntimeException('Payment amount must be greater than zero.');
        }

        return DB::transaction(function () use ($plan, $amount, $note) {
            $newBalance = max(0, $plan->outstanding_balance - $amount);

            $transaction = IpfTransaction::create([
                'ipf_plan_id'      => $plan->id,
                'type'             => 'installment',
                'amount'           => $amount,
                'balance_after'    => $newBalance,
                'transaction_date' => now()->toDateString(),
                'note'             => $note,
            ]);

            $plan->update([
                'outstanding_balance' => $newBalance,
                'status'               => $newBalance <= 0 ? 'completed' : 'active',
            ]);

            return $transaction;
        });
    }

    /**
     * Daily job: for every active plan not yet charged today, check whether a
     * payment was recorded today; if not, apply a penalty (daily_installment *
     * penalty_percent) directly onto the outstanding balance.
     *
     * Run once daily via the scheduler, late in the day, so same-day payments
     * still count before the penalty check runs.
     */
    public function applyDailyPenalties(): int
    {
        $today = now()->toDateString();
        $count = 0;

        IpfPlan::query()->where('status', 'active')
            ->where(function ($q) use ($today) {
                $q->whereNull('last_charged_date')->orWhere('last_charged_date', '<', $today);
            })
            ->chunkById(100, function ($plans) use ($today, &$count) {
                foreach ($plans as $plan) {
                    $paidToday = $plan->transactions()
                        ->where('type', 'installment')
                        ->whereDate('transaction_date', $today)
                        ->exists();

                    if ($paidToday) {
                        $plan->update(['last_charged_date' => $today]);
                        continue;
                    }

                    $penalty = round($plan->daily_installment * $plan->penalty_percent / 100, 2);
                    $newBalance = $plan->outstanding_balance + $penalty;

                    DB::transaction(function () use ($plan, $penalty, $newBalance, $today) {
                        IpfTransaction::create([
                            'ipf_plan_id'      => $plan->id,
                            'type'             => 'penalty',
                            'amount'           => $penalty,
                            'balance_after'    => $newBalance,
                            'transaction_date' => $today,
                            'note'             => 'Missed daily installment penalty.',
                        ]);

                        $plan->update([
                            'outstanding_balance' => $newBalance,
                            'last_charged_date'    => $today,
                        ]);
                    });

                    $count++;
                }
            });

        return $count;
    }
}
