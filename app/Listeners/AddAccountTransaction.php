<?php

namespace App\Listeners;

use App\AccountTransaction;
use App\BusinessLocation;
use App\Events\TransactionPaymentAdded;
use App\Utils\ModuleUtil;
use App\Utils\TransactionUtil;

class AddAccountTransaction
{
    protected $transactionUtil;

    protected $moduleUtil;

    /**
     * Constructor
     *
     * @param  TransactionUtil  $transactionUtil
     * @return void
     */
    public function __construct(TransactionUtil $transactionUtil, ModuleUtil $moduleUtil)
    {
        $this->transactionUtil = $transactionUtil;
        $this->moduleUtil = $moduleUtil;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TransactionPaymentAdded $event)
    {
        //echo "<pre>";print_r($event->transactionPayment->toArray());exit;
        if ($event->transactionPayment->method == 'advance') {
            $this->transactionUtil->updateContactBalance($event->transactionPayment->payment_for, $event->transactionPayment->amount, 'deduct');
        }

        // Note: Removed module check - we want to create account transactions even if module is not explicitly enabled
        // This allows default payment accounts to work properly



        // Get account_id from form OR from default account settings
        $account_id = $event->formInput['account_id'] ?? null;

        // If no account manually selected, use default account for this payment method.
        // Guard: payments without a linked transaction (e.g. some advance/unallocated payments)
        // don't have a location_id, so skip location-based default lookup.
        if (empty($account_id) && !empty($event->transactionPayment->method) && $event->transactionPayment->method != 'advance') {
            // Get the business location from transaction
            $location_id = null;

            // Ensure transaction relationship is loaded
            $payment = $event->transactionPayment;
            if (!$payment->relationLoaded('transaction')) {
                $payment->load('transaction');
            }

            // Some payments can exist without a transaction_id (unallocated/advance-like).
            // In that case, we cannot resolve a location-based default payment account.
            if (empty($payment->transaction_id) || (!empty($payment->is_advance) && (int) $payment->is_advance === 1)) {
                return;
            }

            // Get location_id from transaction
            if ($payment->transaction) {
                $location_id = $payment->transaction->location_id;
            }

            if (!empty($location_id)) {
                $location = BusinessLocation::find($location_id);

                if ($location && !empty($location->default_payment_accounts)) {
                    $default_accounts = json_decode($location->default_payment_accounts, true);
                    $payment_method = $payment->method;

                    // Get default account for this payment method
                    if (isset($default_accounts[$payment_method]['account']) && !empty($default_accounts[$payment_method]['account'])) {
                        $account_id = $default_accounts[$payment_method]['account'];

                        // Log for debugging
                        \Log::info('Using default account for payment', [
                            'payment_method' => $payment_method,
                            'default_account_id' => $account_id,
                            'location_id' => $location_id,
                            'transaction_id' => $payment->transaction_id,
                        ]);
                    } else {
                        \Log::warning('Default account not found for payment method', [
                            'payment_method' => $payment_method,
                            'location_id' => $location_id,
                            'available_defaults' => array_keys($default_accounts),
                        ]);
                    }
                } else {
                    \Log::warning('Location or default_payment_accounts not found', [
                        'location_id' => $location_id,
                        'has_location' => !empty($location),
                        'has_defaults' => !empty($location->default_payment_accounts ?? null),
                    ]);
                }
            } else {
                \Log::warning('Could not determine location_id for payment', [
                    'payment_id' => $payment->id,
                    'transaction_id' => $payment->transaction_id,
                    'has_transaction' => !empty($payment->transaction),
                ]);
            }
        }

        // Create account transaction if we have an account (either manual or default)
        if (!empty($account_id) && $event->transactionPayment->method != 'advance') {
            $type = !empty($event->transactionPayment->payment_type) ? $event->transactionPayment->payment_type : AccountTransaction::getAccountTransactionType($event->formInput['transaction_type']);
            $account_transaction_data = [
                'amount' => $event->formInput['amount'],
                'account_id' => $account_id,
                'type' => $type,
                'operation_date' => $event->transactionPayment->paid_on,
                'created_by' => $event->transactionPayment->created_by,
                'transaction_id' => $event->transactionPayment->transaction_id,
                'transaction_payment_id' => $event->transactionPayment->id,
            ];

            //If change return then set type as debit
            if ($event->formInput['transaction_type'] == 'sell' && isset($event->formInput['is_return']) && $event->formInput['is_return'] == 1) {
                $account_transaction_data['type'] = 'debit';
            }

            if ($event->formInput['transaction_type'] == 'hms_booking' && isset($event->formInput['is_return']) && $event->formInput['is_return'] == 1) {
                $account_transaction_data['type'] = 'debit';
            }

            if ($event->formInput['transaction_type'] == 'gym_subscription' && isset($event->formInput['is_return']) && $event->formInput['is_return'] == 1) {
                $account_transaction_data['type'] = 'debit';
            }



            AccountTransaction::createAccountTransaction($account_transaction_data);
        }
    }
}
