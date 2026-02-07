<?php

namespace App\Listeners;

use App\InstallmentPlan;
use App\Transaction;
use App\Utils\InstallmentUtil;

class SyncInstallmentPlanOnPaymentChange
{
    protected $installmentUtil;

    public function __construct(InstallmentUtil $installmentUtil)
    {
        $this->installmentUtil = $installmentUtil;
    }

    public function handle($event): void
    {
        $tp = $event->transactionPayment ?? null;
        if (empty($tp) || empty($tp->transaction_id)) {
            return;
        }

        $transaction = Transaction::find($tp->transaction_id);
        if (empty($transaction) || $transaction->type !== 'sell') {
            return;
        }

        $plan = InstallmentPlan::where('transaction_id', $transaction->id)->first();
        if (empty($plan)) {
            return;
        }

        $this->installmentUtil->syncPlanPaymentStatus($plan);
    }
}
