<?php

namespace App\Utils;

use App\InstallmentPlan;
use App\Transaction;
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
            $new_paid_amount = 0.0;

            if ($remaining_paid > 0) {
                $new_paid_amount = min($line_amount, $remaining_paid);
                $remaining_paid -= $new_paid_amount;
            }

            $was_paid = $line->status === 'paid';
            $is_paid = ($new_paid_amount >= ($line_amount - 0.0001));

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
}
