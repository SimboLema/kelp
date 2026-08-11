<?php

namespace App\Services;

use App\Models\InsuranceOrder;
use App\Models\IpfAccount;
use App\Models\IpfInstallment;
use App\Models\IpfPayment;
use App\Models\IpfPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class IpfService
{
    /**
     * Open an IPF account for an order against a specific plan, and generate
     * its full daily installment breakdown in the same transaction.
     */
    public function createPlan(InsuranceOrder $order, IpfPlan $plan, float $totalPremium): IpfAccount
    {
        if (IpfAccount::query()->where('insurance_order_id', $order->id)->exists()) {
            throw new RuntimeException('An IPF account already exists for this order.');
        }

        return DB::transaction(function () use ($order, $plan, $totalPremium) {
            $downPaymentAmount = round($totalPremium * ((float) $plan->down_payment_percent / 100), 2);
            $financedAmount = round($totalPremium - $downPaymentAmount, 2);

            $startDate = now()->startOfDay();
            $endDate = $startDate->copy()->addDays(max(0, $plan->duration_days - 1));

            $account = IpfAccount::create([
                'insurance_order_id' => $order->id,
                'user_id' => $order->user_id,
                'ipf_plan_id' => $plan->id,
                'total_premium' => $totalPremium,
                'down_payment_percent' => $plan->down_payment_percent,
                'down_payment_amount' => $downPaymentAmount,
                'financed_amount' => $financedAmount,
                'total_paid' => 0,
                'remaining_amount' => $financedAmount,
                'start_date' => $startDate->toDateString(),
                'expected_end_date' => $endDate->toDateString(),
                'status' => 'pending',
            ]);

            $this->generateInstallments($account, $plan);

            return $account->fresh('installments');
        });
    }

    /**
     * Build the day-by-day payment breakdown for an account.
     *
     * - "fixed": financed amount split evenly across duration_days, with the
     *   last day absorbing any rounding remainder.
     * - "remaining_balance_percentage": each day's payment is daily_rate_percent
     *   of whatever is still owed (a declining schedule, per your "5% of the
     *   remaining amount daily" description). Because a pure percentage of a
     *   shrinking balance never mathematically reaches zero, the final day of
     *   the plan is forced to collect whatever balance is left so the account
     *   actually closes out within duration_days. Flag this assumption if you
     *   want a different close-out rule (e.g. spill remaining days into a grace
     *   period instead of forcing payoff on the last day).
     */
    public function generateInstallments(IpfAccount $account, ?IpfPlan $plan = null): void
    {
        $plan = $plan ?? $account->plan;
        $days = max(1, (int) $plan->duration_days);
        $remaining = (float) $account->financed_amount;
        $dueDate = Carbon::parse($account->start_date);

        $rows = [];

        if ($plan->calculation_method === 'remaining_balance_percentage') {
            $rate = ((float) ($plan->daily_rate_percent ?? 0)) / 100;

            for ($day = 1; $day <= $days && $remaining > 0; $day++) {
                $isLastDay = $day === $days;
                $amount = $isLastDay ? $remaining : round($remaining * $rate, 2);
                $amount = min($amount, $remaining);

                $remaining = round($remaining - $amount, 2);
                $rows[] = $this->installmentRow($account, $day, $dueDate, $amount);
                $dueDate = $dueDate->copy()->addDay();
            }
        } else {
            $base = floor(($remaining / $days) * 100) / 100;

            for ($day = 1; $day <= $days && $remaining > 0; $day++) {
                $amount = $day === $days ? $remaining : min($base, $remaining);

                $remaining = round($remaining - $amount, 2);
                $rows[] = $this->installmentRow($account, $day, $dueDate, $amount);
                $dueDate = $dueDate->copy()->addDay();
            }
        }

        if (! empty($rows)) {
            IpfInstallment::insert($rows);
        }
    }

    private function installmentRow(IpfAccount $account, int $day, Carbon $dueDate, float $amount): array
    {
        return [
            'ipf_account_id' => $account->id,
            'installment_number' => $day,
            'due_date' => $dueDate->toDateString(),
            'amount_due' => $amount,
            'amount_paid' => 0,
            'remaining_amount' => $amount,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Record a payment against an account and apply it to the oldest unpaid
     * installments first (oldest-first waterfall), updating account totals
     * and status as it goes.
     */
    public function recordPayment(IpfAccount $account, float $amount, ?string $note = null, ?string $method = null): IpfPayment
    {
        if ($amount <= 0) {
            throw new RuntimeException('Payment amount must be greater than zero.');
        }

        if ($account->status === 'completed') {
            throw new RuntimeException('This IPF account is already fully paid.');
        }

        return DB::transaction(function () use ($account, $amount, $note, $method) {
            $payment = IpfPayment::create([
                'ipf_account_id' => $account->id,
                'user_id' => $account->user_id,
                'amount' => $amount,
                'payment_method' => $method,
                'transaction_reference' => 'IPF-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6)),
                'status' => 'successful',
                'paid_at' => now(),
                'payment_response' => $note ? ['note' => $note] : null,
            ]);

            $this->applyPaymentToInstallments($account, $payment, $amount);

            $account->refresh();
            $account->total_paid = round((float) $account->total_paid + $amount, 2);
            $account->remaining_amount = max(0, round((float) $account->financed_amount - $account->total_paid, 2));

            if ($account->status === 'pending') {
                $account->status = 'active';
            }
            if ($account->remaining_amount <= 0) {
                $account->status = 'completed';
            }
            $account->save();

            return $payment->fresh();
        });
    }

    private function applyPaymentToInstallments(IpfAccount $account, IpfPayment $payment, float $amount): void
    {
        $installments = $account->installments()
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->orderBy('installment_number')
            ->get();

        $remainingPayment = $amount;
        $lastTouched = null;

        foreach ($installments as $installment) {
            if ($remainingPayment <= 0) {
                break;
            }

            $owed = (float) $installment->remaining_amount;
            $portion = min($owed, $remainingPayment);

            $installment->amount_paid = round((float) $installment->amount_paid + $portion, 2);
            $installment->remaining_amount = round($owed - $portion, 2);
            $installment->status = $installment->remaining_amount <= 0 ? 'paid' : 'partial';
            $installment->paid_at = $installment->status === 'paid' ? now() : $installment->paid_at;
            $installment->save();

            $remainingPayment = round($remainingPayment - $portion, 2);
            $lastTouched = $installment;
        }

        // Any leftover beyond what was owed (an overpayment) is still recorded
        // on the payment itself via `amount`; it just isn't tied to a specific
        // installment once every installment is settled.
        if ($lastTouched) {
            $payment->ipf_installment_id = $lastTouched->id;
            $payment->save();
        }
    }
}