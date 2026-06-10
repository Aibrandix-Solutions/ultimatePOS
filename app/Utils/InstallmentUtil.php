<?php

namespace App\Utils;

use App\InstallmentPlan;
use App\Transaction;
use App\Utils\TransactionUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InstallmentUtil
{
    public function createInstallmentPlanForTransaction(Transaction $transaction, Request $request, float $down_payment, int $business_id, int $user_id): ?InstallmentPlan
    {
        $enable = (int) $request->input('enable_installment_plan', 0) === 1;
        if (! $enable) {
            return null;
        }

        if ($transaction->type !== 'sell' || $transaction->status !== 'final') {
            return null;
        }

        $installment_count = (int) $request->input('installment_count');
        $interval = (int) $request->input('installment_interval', 1);
        $interval_type = (string) $request->input('installment_interval_type', 'months');

        if ($installment_count <= 0) {
            throw new \Exception('Installment count is required.');
        }

        if ($interval <= 0) {
            throw new \Exception('Installment interval is required.');
        }

        if (! in_array($interval_type, ['days', 'weeks', 'months'], true)) {
            throw new \Exception('Invalid installment interval type.');
        }

        $first_due_date_input = $request->input('installment_first_due_date');
        $first_due_date = null;

        if (! empty($first_due_date_input)) {
            $first_due_date = app(\App\Utils\ProductUtil::class)->uf_date($first_due_date_input);
        }

        if (empty($first_due_date)) {
            $due_date = Carbon::parse($transaction->transaction_date)->startOfDay();
            
            // Add the interval to get the first installment due date
            if ($interval_type === 'days') {
                $due_date = $due_date->addDays($interval);
            } elseif ($interval_type === 'weeks') {
                $due_date = $due_date->addWeeks($interval);
            } else {
                $due_date = $due_date->addMonths($interval);
            }
            
            $first_due_date = $due_date->format('Y-m-d');
        }

        $invoice_date = Carbon::parse($transaction->transaction_date)->startOfDay();
        if (Carbon::parse($first_due_date)->startOfDay()->lt($invoice_date)) {
            throw new \Exception('First installment due date cannot be before invoice date.');
        }

        // If already exists, don't recreate.
        $existing = InstallmentPlan::where('transaction_id', $transaction->id)->first();
        if (! empty($existing)) {
            return $existing;
        }

        $remaining = (float) $transaction->final_total - (float) $down_payment;
        // Do not create a plan if there is no pending amount.
        if ($remaining < 0.0001) {
            return null;
        }

        $plan = InstallmentPlan::create([
            'business_id' => $business_id,
            'transaction_id' => $transaction->id,
            'contact_id' => $transaction->contact_id,
            'created_by' => $user_id,
            'down_payment' => round($down_payment, 4),
            'installment_count' => $installment_count,
            'interval' => $interval,
            'interval_type' => $interval_type,
            'first_due_date' => $first_due_date,
            'status' => 'active',
        ]);

        $per = round($remaining / $installment_count, 4);
        $running_total = 0.0;
        $due = Carbon::parse($first_due_date)->startOfDay();

        for ($i = 1; $i <= $installment_count; $i++) {
            $amount = ($i === $installment_count)
                ? round($remaining - $running_total, 4)
                : $per;

            $running_total += $amount;

            $plan->lines()->create([
                'sequence' => $i,
                'due_date' => $due->format('Y-m-d'),
                'amount' => $amount,
                'paid_amount' => 0,
                'status' => 'pending',
            ]);

            if ($interval_type === 'days') {
                $due = $due->copy()->addDays($interval);
            } elseif ($interval_type === 'weeks') {
                $due = $due->copy()->addWeeks($interval);
            } else {
                $due = $due->copy()->addMonths($interval);
            }
        }

        return $plan;
    }

    public function syncPlanPaymentStatus(InstallmentPlan $plan): void
    {
        $transaction = Transaction::with(['payment_lines'])->find($plan->transaction_id);
        if (empty($transaction) || $transaction->type !== 'sell') {
            return;
        }

        $paid_total = 0.0;
        foreach ($transaction->payment_lines as $payment_line) {
            if (! empty($payment_line->is_return)) {
                continue;
            }

            // Ignore pending cheques to match due calculations.
            if ($payment_line->method === 'cheque' && ($payment_line->cheque_status === null || $payment_line->cheque_status !== 'cleared')) {
                continue;
            }

            $paid_total += (float) $payment_line->amount;
        }

        $installment_paid = (float) $paid_total - (float) $plan->down_payment;
        if ($installment_paid < 0) {
            $installment_paid = 0;
        }

        $lines = $plan->lines()->orderBy('sequence')->get();
        $remaining_paid = $installment_paid;

        $all_paid = true;
        foreach ($lines as $line) {
            $line_amount = (float) $line->amount;
            $line_paid_amount = (float) $line->paid_amount;
            $was_settled = $line->status === 'paid'
                && ($line_amount < 0.0001 || $line_paid_amount >= ($line_amount - 0.0001));

            if ($was_settled) {
                $remaining_paid -= $line_paid_amount;
                if ($remaining_paid < 0) {
                    $remaining_paid = 0;
                }
                continue;
            }

            $new_paid_amount = 0.0;
            if ($remaining_paid > 0 && $line_amount > 0) {
                $new_paid_amount = min($line_amount, $remaining_paid);
                $remaining_paid -= $new_paid_amount;
            }

            $was_paid = $line->status === 'paid';
            $is_paid = $line_amount < 0.0001
                || ($new_paid_amount >= ($line_amount - 0.0001));

            $line->paid_amount = round($new_paid_amount, 4);
            $line->status = $is_paid ? 'paid' : 'pending';
            if ($is_paid && ! $was_paid) {
                $line->paid_on = Carbon::now();
            }
            if (! $is_paid) {
                $line->paid_on = null;
                $all_paid = false;
            }
            $line->save();
        }

        if ($all_paid) {
            if ($plan->status !== 'closed') {
                $plan->status = 'closed';
                $plan->closed_at = Carbon::now();
                $plan->save();
            }
        } else {
            if ($plan->status !== 'active') {
                $plan->status = 'active';
                $plan->closed_at = null;
                $plan->save();
            }
        }
    }

    public function getNextPendingInstallmentAmount(InstallmentPlan $plan): ?float
    {
        $line = $plan->lines()
            ->where('status', '!=', 'paid')
            ->orderBy('sequence')
            ->first();

        if (empty($line)) {
            return null;
        }

        return max(0, round((float) $line->amount - (float) $line->paid_amount, 4));
    }

    public function getSellReturnTotal(int $transaction_id): float
    {
        return (float) Transaction::where('return_parent_id', $transaction_id)
            ->where('type', 'sell_return')
            ->where('status', 'final')
            ->sum('final_total');
    }

    public function getEffectiveSaleTotal(Transaction $transaction): float
    {
        $returns = $this->getSellReturnTotal($transaction->id);

        return max(0, (float) $transaction->final_total - $returns);
    }

    public function getRemainingInstallmentBalance(InstallmentPlan $plan, ?Transaction $transaction = null): float
    {
        $transaction = $transaction ?? Transaction::find($plan->transaction_id);
        if (empty($transaction)) {
            return 0;
        }

        $transaction_util = app(TransactionUtil::class);
        $effective_total = $this->getEffectiveSaleTotal($transaction);
        $paid_total = (float) $transaction_util->getTotalPaid($transaction->id);

        return max(0, round($effective_total - $paid_total, 4));
    }

    /**
     * @return array<int, float> line_id => suggested amount
     */
    public function getSuggestedPendingAmounts(InstallmentPlan $plan): array
    {
        $lines = $plan->lines()->orderBy('sequence')->get();
        $pending_lines = $lines->where('status', '!=', 'paid')->values();
        $pending_count = $pending_lines->count();

        if ($pending_count === 0) {
            return [];
        }

        $remaining = $this->getRemainingInstallmentBalance($plan);
        $minimums = [];
        $minimum_total = 0.0;

        foreach ($pending_lines as $line) {
            $minimum = max(0, (float) $line->paid_amount);
            $minimums[(int) $line->id] = $minimum;
            $minimum_total += $minimum;
        }

        $extra = round($remaining - $minimum_total, 4);
        if ($extra < 0) {
            $suggested = [];
            foreach ($pending_lines as $line) {
                $suggested[(int) $line->id] = 0;
            }

            return $suggested;
        }

        $per = $pending_count > 0 ? round($extra / $pending_count, 4) : 0;
        $running_extra = 0.0;
        $suggested = [];

        foreach ($pending_lines as $index => $line) {
            $is_last = ($index === $pending_count - 1);
            $share = $is_last
                ? round($extra - $running_extra, 4)
                : $per;

            $running_extra += $share;
            $suggested[(int) $line->id] = round($minimums[(int) $line->id] + max(0, $share), 4);
        }

        return $suggested;
    }

    public function getPendingLinesTotal(InstallmentPlan $plan): float
    {
        return (float) $plan->lines()
            ->where('status', '!=', 'paid')
            ->sum('amount');
    }

    public function planNeedsAdjustment(InstallmentPlan $plan): bool
    {
        if ($plan->status !== 'active') {
            return false;
        }

        $pending_count = $plan->lines()->where('status', '!=', 'paid')->count();
        if ($pending_count === 0) {
            return false;
        }

        $remaining = $this->getRemainingInstallmentBalance($plan);
        $pending_total = $this->getPendingLinesTotal($plan);

        return abs($pending_total - $remaining) > 0.01;
    }

    /**
     * @param  array<int, mixed>  $line_amounts
     */
    public function updatePendingLineAmounts(InstallmentPlan $plan, array $line_amounts): void
    {
        $transaction_util = app(TransactionUtil::class);
        $lines = $plan->lines()->orderBy('sequence')->get();
        $pending_lines = $lines->where('status', '!=', 'paid');
        $expected_remaining = $this->getRemainingInstallmentBalance($plan);
        $new_pending_total = 0.0;
        $is_fully_settled = $expected_remaining < 0.0001;

        foreach ($pending_lines as $line) {
            if (! array_key_exists($line->id, $line_amounts)) {
                throw new \Exception(__('lang_v1.installment_line_amount_required', ['sequence' => $line->sequence]));
            }

            $amount = (float) $transaction_util->num_uf($line_amounts[$line->id]);
            if ($amount < 0) {
                throw new \Exception(__('lang_v1.installment_line_amount_invalid', ['sequence' => $line->sequence]));
            }

            if (! $is_fully_settled && $amount + 0.0001 < (float) $line->paid_amount) {
                throw new \Exception(__('lang_v1.installment_line_amount_below_paid', ['sequence' => $line->sequence]));
            }

            $line->amount = round($amount, 4);

            if ($is_fully_settled && $amount < 0.0001) {
                $line->paid_amount = 0;
                $line->status = 'paid';
                $line->paid_on = $line->paid_on ?? Carbon::now();
            }

            $line->save();
            $new_pending_total += $amount;
        }

        if (abs($new_pending_total - $expected_remaining) > 0.01) {
            throw new \Exception(__('lang_v1.installment_pending_total_mismatch', [
                'expected' => $transaction_util->num_f($expected_remaining),
                'entered' => $transaction_util->num_f($new_pending_total),
            ]));
        }

        $this->syncPlanPaymentStatus($plan->fresh());

        if ($is_fully_settled) {
            $plan = $plan->fresh();
            if ($plan->status !== 'closed') {
                $plan->status = 'closed';
                $plan->closed_at = Carbon::now();
                $plan->save();
            }
        }
    }

    public function getCustomerCreditAfterReturns(InstallmentPlan $plan, ?Transaction $transaction = null): float
    {
        $transaction = $transaction ?? Transaction::find($plan->transaction_id);
        if (empty($transaction)) {
            return 0;
        }

        $transaction_util = app(TransactionUtil::class);
        $effective_total = $this->getEffectiveSaleTotal($transaction);
        $paid_total = (float) $transaction_util->getTotalPaid($transaction->id);
        $credit = round($paid_total - $effective_total, 4);

        return $credit > 0.0001 ? $credit : 0;
    }
}
