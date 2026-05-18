<?php

namespace App\Http\Controllers;

use App\BusinessLocation;
use App\Contact;
use App\Events\TransactionPaymentAdded;
use App\Events\TransactionPaymentUpdated;
use App\Exceptions\AdvanceBalanceNotAvailable;
use App\Exceptions\ChequePaymentNotAllowedForWalkInCustomer;
use App\Transaction;
use App\TransactionPayment;
use App\Utils\CashRegisterUtil;
use App\Utils\ModuleUtil;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Datatables;
use DB;
use Illuminate\Http\Request;

class TransactionPaymentController extends Controller
{
    protected $transactionUtil;

    protected $moduleUtil;

    protected $cashRegisterUtil;

    /**
     * Constructor
     *
     * @param  TransactionUtil  $transactionUtil
     * @return void
     */
    public function __construct(TransactionUtil $transactionUtil, ModuleUtil $moduleUtil, CashRegisterUtil $cashRegisterUtil)
    {
        $this->transactionUtil = $transactionUtil;
        $this->moduleUtil = $moduleUtil;
        $this->cashRegisterUtil = $cashRegisterUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $business_id = $request->session()->get('user.business_id');
            $transaction_id = $request->input('transaction_id');
            $transaction = Transaction::where('business_id', $business_id)->with(['contact'])->findOrFail($transaction_id);

            $transaction_before = $transaction->replicate();

            if (! (auth()->user()->can('purchase.payments') || auth()->user()->can('hms.add_booking_payment') || auth()->user()->can('sell.payments') || auth()->user()->can('all_expense.access') || auth()->user()->can('view_own_expense'))) {
                abort(403, 'Unauthorized action.');
            }

            if ($transaction->payment_status != 'paid') {
                $inputs = $request->only(['amount', 'method', 'note',
                    'card_transaction_number', 'card_type', 'card_holder_name',
                    'cheque_number', 'bank_account_number',
                    'cheque_issue_date', 'cheque_passing_date', 'cheque_bank_name', 'cheque_status',
                ]);
                // Never store sensitive card details
                $inputs['card_number'] = null;
                $inputs['card_year'] = null;
                $inputs['card_security'] = null;
                $inputs['card_month'] = null;
                $inputs['paid_on'] = $this->transactionUtil->uf_date($request->input('paid_on'), true);
                $inputs['transaction_id'] = $transaction->id;
                $inputs['amount'] = $this->transactionUtil->num_uf($inputs['amount']);
                $inputs['created_by'] = auth()->user()->id;
                $inputs['payment_for'] = $transaction->contact_id;

                if ($inputs['method'] == 'cheque') {
                    $inputs['cheque_issue_date'] = !empty($request->input('cheque_issue_date'))
                        ? $this->transactionUtil->uf_date($request->input('cheque_issue_date'), true)
                        : null;
                    $inputs['cheque_passing_date'] = !empty($request->input('cheque_passing_date'))
                        ? $this->transactionUtil->uf_date($request->input('cheque_passing_date'), true)
                        : null;
                    $inputs['cheque_status'] = $request->input('cheque_status') ?: 'pending';
                } else {
                    $inputs['cheque_issue_date'] = null;
                    $inputs['cheque_passing_date'] = null;
                    $inputs['cheque_bank_name'] = null;
                    $inputs['cheque_status'] = null;
                }

                if ($inputs['method'] == 'custom_pay_1') {
                    $inputs['transaction_no'] = $request->input('transaction_no_1');
                } elseif ($inputs['method'] == 'custom_pay_2') {
                    $inputs['transaction_no'] = $request->input('transaction_no_2');
                } elseif ($inputs['method'] == 'custom_pay_3') {
                    $inputs['transaction_no'] = $request->input('transaction_no_3');
                }

                if (! empty($request->input('account_id')) && $inputs['method'] != 'advance') {
                    $inputs['account_id'] = $request->input('account_id');
                }

                $prefix_type = 'purchase_payment';
                if (in_array($transaction->type, ['sell', 'sell_return'])) {
                    $prefix_type = 'sell_payment';
                } elseif (in_array($transaction->type, ['expense', 'expense_refund'])) {
                    $prefix_type = 'expense_payment';
                }

                DB::beginTransaction();

                $ref_count = $this->transactionUtil->setAndGetReferenceCount($prefix_type);
                //Generate reference number
                $inputs['payment_ref_no'] = $this->transactionUtil->generateReferenceNumber($prefix_type, $ref_count);

                $inputs['business_id'] = $request->session()->get('business.id');
                $inputs['document'] = $this->transactionUtil->uploadFile($request, 'document', 'documents');

                //Pay from advance balance
                $payment_amount = $inputs['amount'];
                $contact_balance = ! empty($transaction->contact) ? $transaction->contact->balance : 0;
                if ($inputs['method'] == 'advance' && $inputs['amount'] > $contact_balance) {
                    throw new AdvanceBalanceNotAvailable(__('lang_v1.required_advance_balance_not_available'));
                }

                if ($inputs['method'] == 'cheque' && !empty($transaction->contact) && $transaction->contact->is_default == 1) {
                    throw new ChequePaymentNotAllowedForWalkInCustomer(__('lang_v1.cheque_payment_requires_registered_customer'));
                }

                if (! empty($inputs['amount'])) {
                    $tp = TransactionPayment::create($inputs);

                    if (! empty($request->input('denominations'))) {
                        $this->transactionUtil->addCashDenominations($tp, $request->input('denominations'));
                    }

                    $inputs['transaction_type'] = $transaction->type;
                    event(new TransactionPaymentAdded($tp, $inputs));

                    //Add payment movement to currently open register for the payer user.
                    $this->cashRegisterUtil->addTransactionPaymentToRegister($transaction, $tp, $tp->created_by);
                }

                //update payment status
                $payment_status = $this->transactionUtil->updatePaymentStatus($transaction_id, $transaction->final_total);
                $transaction->payment_status = $payment_status;

                $this->transactionUtil->activityLog($transaction, 'payment_edited', $transaction_before);

                DB::commit();
            }

            $output = ['success' => true,
                'msg' => __('purchase.payment_added_success'),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = __('messages.something_went_wrong');

            if (get_class($e) == \App\Exceptions\AdvanceBalanceNotAvailable::class) {
                $msg = $e->getMessage();
            } elseif (get_class($e) == \App\Exceptions\ChequePaymentNotAllowedForWalkInCustomer::class) {
                $msg = $e->getMessage();
            } else {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());
            }

            $output = ['success' => false,
                'msg' => $msg,
            ];
        }

        return redirect()->back()->with(['status' => $output]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! (auth()->user()->can('sell.payments') || auth()->user()->can('purchase.payments') || auth()->user()->can('hms.add_booking_payment'))) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $transaction = Transaction::where('id', $id)
                                        ->with(['contact', 'business', 'transaction_for'])
                                        ->first();
            $payments_query = TransactionPayment::where('transaction_id', $id);

            $accounts_enabled = false;
            if ($this->moduleUtil->isModuleEnabled('account')) {
                $accounts_enabled = true;
                $payments_query->with(['payment_account']);
            }

            $payments = $payments_query->get();
            $location_id = ! empty($transaction->location_id) ? $transaction->location_id : null;
            $payment_types = $this->transactionUtil->payment_types($location_id, true);

            return view('transaction_payment.show_payments')
                    ->with(compact('transaction', 'payments', 'payment_types', 'accounts_enabled'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! auth()->user()->can('edit_purchase_payment') && ! auth()->user()->can('edit_sell_payment') && !auth()->user()->can('hms.edit_booking_payment')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $payment_line = TransactionPayment::with(['denominations'])->where('method', '!=', 'advance')->findOrFail($id);

            $transaction = Transaction::where('id', $payment_line->transaction_id)
                                        ->where('business_id', $business_id)
                                        ->with(['contact', 'location'])
                                        ->first();

            $payment_types = $this->transactionUtil->payment_types($transaction->location);

            //Accounts
            $accounts = $this->moduleUtil->accountsDropdown($business_id, true, false, true);

            return view('transaction_payment.edit_payment_row')
                        ->with(compact('transaction', 'payment_types', 'payment_line', 'accounts'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('edit_purchase_payment') && ! auth()->user()->can('edit_sell_payment') && ! auth()->user()->can('all_expense.access') && ! auth()->user()->can('view_own_expense') && !auth()->user()->can('hms.edit_booking_payment')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $business_id = request()->session()->get('user.business_id');

            $inputs = $request->only(['amount', 'method', 'note',
                'card_transaction_number', 'card_type', 'card_holder_name',
                'cheque_number', 'bank_account_number',
                'cheque_issue_date', 'cheque_passing_date', 'cheque_bank_name', 'cheque_status',
            ]);
            // Never store sensitive card details
            $inputs['card_number'] = null;
            $inputs['card_year'] = null;
            $inputs['card_security'] = null;
            $inputs['card_month'] = null;
            $inputs['paid_on'] = $this->transactionUtil->uf_date($request->input('paid_on'), true);
            $inputs['amount'] = $this->transactionUtil->num_uf($inputs['amount']);

            if ($inputs['method'] == 'cheque') {
                $inputs['cheque_issue_date'] = !empty($request->input('cheque_issue_date'))
                    ? $this->transactionUtil->uf_date($request->input('cheque_issue_date'), true)
                    : null;
                $inputs['cheque_passing_date'] = !empty($request->input('cheque_passing_date'))
                    ? $this->transactionUtil->uf_date($request->input('cheque_passing_date'), true)
                    : null;
                $inputs['cheque_status'] = $request->input('cheque_status') ?: 'pending';
            } else {
                $inputs['cheque_issue_date'] = null;
                $inputs['cheque_passing_date'] = null;
                $inputs['cheque_bank_name'] = null;
                $inputs['cheque_status'] = null;
            }

            if ($inputs['method'] == 'custom_pay_1') {
                $inputs['transaction_no'] = $request->input('transaction_no_1');
            } elseif ($inputs['method'] == 'custom_pay_2') {
                $inputs['transaction_no'] = $request->input('transaction_no_2');
            } elseif ($inputs['method'] == 'custom_pay_3') {
                $inputs['transaction_no'] = $request->input('transaction_no_3');
            }

            if (! empty($request->input('account_id'))) {
                $inputs['account_id'] = $request->input('account_id');
            }

            $payment = TransactionPayment::where('method', '!=', 'advance')->findOrFail($id);

            if (! empty($request->input('denominations'))) {
                $this->transactionUtil->updateCashDenominations($payment, $request->input('denominations'));
            }

            //Update parent payment if exists
            if (! empty($payment->parent_id)) {
                $parent_payment = TransactionPayment::find($payment->parent_id);
                $parent_payment->amount = $parent_payment->amount - ($payment->amount - $inputs['amount']);

                $parent_payment->save();
            }

            $business_id = $request->session()->get('user.business_id');

            $transaction = Transaction::where('business_id', $business_id)
                                ->with(['contact'])
                                ->find($payment->transaction_id);

            if ($inputs['method'] == 'cheque' && !empty($transaction->contact) && $transaction->contact->is_default == 1) {
                throw new ChequePaymentNotAllowedForWalkInCustomer(__('lang_v1.cheque_payment_requires_registered_customer'));
            }

            $transaction_before = $transaction->replicate();
            $document_name = $this->transactionUtil->uploadFile($request, 'document', 'documents');
            if (! empty($document_name)) {
                $inputs['document'] = $document_name;
            }

            DB::beginTransaction();

            $payment->update($inputs);

            //update payment status
            $payment_status = $this->transactionUtil->updatePaymentStatus($payment->transaction_id);
            $transaction->payment_status = $payment_status;

            $this->transactionUtil->activityLog($transaction, 'payment_edited', $transaction_before);

            DB::commit();

            //event
            event(new TransactionPaymentUpdated($payment, $transaction->type));

            $output = ['success' => true,
                'msg' => __('purchase.payment_updated_success'),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = __('messages.something_went_wrong');

            if (get_class($e) == \App\Exceptions\ChequePaymentNotAllowedForWalkInCustomer::class) {
                $msg = $e->getMessage();
            } else {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());
            }

            $output = ['success' => false,
                'msg' => $msg,
            ];
        }

        return redirect()->back()->with(['status' => $output]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! auth()->user()->can('delete_purchase_payment') && ! auth()->user()->can('delete_sell_payment') && ! auth()->user()->can('all_expense.access') && ! auth()->user()->can('view_own_expense') && !auth()->user()->can('hms.delete_booking_payment')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $payment = TransactionPayment::findOrFail($id);

                DB::beginTransaction();

                if (! empty($payment->transaction_id)) {
                    TransactionPayment::deletePayment($payment);
                } else { //advance payment
                    $adjusted_payments = TransactionPayment::where('parent_id',
                                                $payment->id)
                                                ->get();

                    $total_adjusted_amount = $adjusted_payments->sum('amount');

                    //Get customer advance share from payment and deduct from advance balance
                    $total_customer_advance = $payment->amount - $total_adjusted_amount;
                    if ($total_customer_advance > 0) {
                        $this->transactionUtil->updateContactBalance($payment->payment_for, $total_customer_advance, 'deduct');
                    }

                    //Delete all child payments
                    foreach ($adjusted_payments as $adjusted_payment) {
                        //Make parent payment null as it will get deleted
                        $adjusted_payment->parent_id = null;
                        TransactionPayment::deletePayment($adjusted_payment);
                    }

                    //Delete advance payment
                    TransactionPayment::deletePayment($payment);
                }

                DB::commit();

                $output = ['success' => true,
                    'msg' => __('purchase.payment_deleted_success'),
                ];
            } catch (\Exception $e) {
                DB::rollBack();

                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }

    /**
     * Update cheque status for a payment line.
     *
     * Allowed statuses: pending, cleared, bounced
     */
    public function updateChequeStatus(Request $request, $payment_id)
    {
        if (!request()->ajax()) {
            abort(404);
        }

        $business_id = $request->session()->get('user.business_id');
        $status = $request->input('cheque_status');
        $allowed_statuses = ['pending', 'cleared', 'bounced'];

        if (empty($status) || !in_array($status, $allowed_statuses, true)) {
            return ['success' => false, 'msg' => __('messages.something_went_wrong')];
        }

        $payment = TransactionPayment::where('business_id', $business_id)
            ->where('method', 'cheque')
            ->findOrFail($payment_id);

        $transaction_type = null;
        if (!empty($payment->transaction_id)) {
            $transaction_type = Transaction::where('business_id', $business_id)
                ->where('id', $payment->transaction_id)
                ->value('type');
        }

        $can_edit = false;
        if (in_array($transaction_type, ['purchase', 'purchase_return'], true)) {
            $can_edit = auth()->user()->can('edit_purchase_payment');
        } elseif (in_array($transaction_type, ['sell', 'sell_return'], true)) {
            $can_edit = auth()->user()->can('edit_sell_payment');
        } else {
            $can_edit = auth()->user()->can('edit_purchase_payment') || auth()->user()->can('edit_sell_payment');
        }

        if (!$can_edit) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();

            //Update the clicked payment
            $payment->cheque_status = $status;
            $payment->save();

            //If this is a parent due-pay cheque payment, update its child allocations too.
            //If this is a child payment, also update its parent + siblings for consistency.
            if (empty($payment->parent_id)) {
                TransactionPayment::where('business_id', $business_id)
                    ->where('parent_id', $payment->id)
                    ->where('method', 'cheque')
                    ->update(['cheque_status' => $status]);
            } else {
                TransactionPayment::where('business_id', $business_id)
                    ->where(function ($q) use ($payment) {
                        $q->where('id', $payment->parent_id)
                            ->orWhere('parent_id', $payment->parent_id);
                    })
                    ->where('method', 'cheque')
                    ->update(['cheque_status' => $status]);
            }

            //Recalculate payment_status for affected transactions.
            $affected_transaction_ids = collect();

            if (!empty($payment->transaction_id)) {
                $affected_transaction_ids->push($payment->transaction_id);
            }

            if (empty($payment->parent_id)) {
                $child_transaction_ids = TransactionPayment::where('business_id', $business_id)
                    ->where('parent_id', $payment->id)
                    ->whereNotNull('transaction_id')
                    ->pluck('transaction_id');
                $affected_transaction_ids = $affected_transaction_ids->merge($child_transaction_ids);
            } else {
                $sibling_transaction_ids = TransactionPayment::where('business_id', $business_id)
                    ->where('parent_id', $payment->parent_id)
                    ->whereNotNull('transaction_id')
                    ->pluck('transaction_id');
                $affected_transaction_ids = $affected_transaction_ids->merge($sibling_transaction_ids);
            }

            $affected_transaction_ids = $affected_transaction_ids->filter()->unique()->values();
            foreach ($affected_transaction_ids as $tid) {
                $t = Transaction::where('business_id', $business_id)->find($tid);
                if (!empty($t)) {
                    $this->transactionUtil->updatePaymentStatus($t->id, $t->final_total);
                }
            }

            DB::commit();

            return ['success' => true, 'msg' => __('lang_v1.updated_success')];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            return ['success' => false, 'msg' => __('messages.something_went_wrong')];
        }
    }

    /**
     * Adds new payment to the given transaction.
     *
     * @param  int  $transaction_id
     * @return \Illuminate\Http\Response
     */
    public function addPayment($transaction_id)
    {
        if (! auth()->user()->can('purchase.payments') && ! auth()->user()->can('sell.payments') && ! auth()->user()->can('all_expense.access') && ! auth()->user()->can('view_own_expense') && !auth()->user()->can('hms.add_booking_payment')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $transaction = Transaction::where('business_id', $business_id)
                                        ->with(['contact', 'location'])
                                        ->findOrFail($transaction_id);
            if ($transaction->payment_status != 'paid') {
                $show_advance = in_array($transaction->type, ['sell', 'purchase']) ? true : false;
                $payment_types = $this->transactionUtil->payment_types($transaction->location, $show_advance);

                $paid_amount = $this->transactionUtil->getTotalPaid($transaction_id);
                $amount = $transaction->final_total - $paid_amount;
                if ($amount < 0) {
                    $amount = 0;
                }

                $amount_formated = $this->transactionUtil->num_f($amount);

                $payment_line = new TransactionPayment();
                $payment_line->amount = $amount;
                $payment_line->method = 'cash';
                $payment_line->paid_on = \Carbon::now()->toDateTimeString();

                //Accounts
                $accounts = $this->moduleUtil->accountsDropdown($business_id, true, false, true);

                $view = view('transaction_payment.payment_row')
                ->with(compact('transaction', 'payment_types', 'payment_line', 'amount_formated', 'accounts'))->render();

                $output = ['status' => 'due',
                    'view' => $view, ];
            } else {
                $output = ['status' => 'paid',
                    'view' => '',
                    'msg' => __('purchase.amount_already_paid'),  ];
            }

            return json_encode($output);
        }
    }

    /**
     * Shows contact's payment due modal
     *
     * @param  int  $contact_id
     * @return \Illuminate\Http\Response
     */
    public function getPayContactDue($contact_id)
    {
        if (! (auth()->user()->can('sell.payments') || auth()->user()->can('purchase.payments'))) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $due_payment_type = request()->input('type');
            $query = Contact::where('contacts.id', $contact_id)
                            ->leftjoin('transactions AS t', 'contacts.id', '=', 't.contact_id');
            if ($due_payment_type == 'purchase') {
                $query->select(
                    DB::raw("SUM(IF(t.type = 'purchase', final_total, 0)) as total_purchase"),
                    DB::raw("SUM(IF(t.type = 'purchase', (SELECT COALESCE(SUM(amount), 0) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id AND (transaction_payments.method != 'cheque' OR transaction_payments.cheque_status = 'cleared')), 0)) as total_paid"),
                    'contacts.name',
                    'contacts.supplier_business_name',
                    'contacts.id as contact_id'
                    );
            } elseif ($due_payment_type == 'purchase_return') {
                $query->select(
                    DB::raw("SUM(IF(t.type = 'purchase_return', final_total, 0)) as total_purchase_return"),
                    DB::raw("SUM(IF(t.type = 'purchase_return', (SELECT COALESCE(SUM(amount), 0) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id AND (transaction_payments.method != 'cheque' OR transaction_payments.cheque_status = 'cleared')), 0)) as total_return_paid"),
                    'contacts.name',
                    'contacts.supplier_business_name',
                    'contacts.id as contact_id'
                    );
            } elseif ($due_payment_type == 'sell') {
                $customer_paid_sql = Util::sqlPaymentCountsTowardContactDue();
                $query->select(
                    DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', final_total, 0)) as total_invoice"),
                    DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', (SELECT COALESCE(SUM(IF(is_return = 1,-1*amount,amount)), 0) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id AND {$customer_paid_sql}), 0)) as total_paid"),
                    DB::raw("SUM(IF(t.type = 'ledger_discount', t.final_total, 0)) as total_ledger_discount"),
                    DB::raw("SUM(IF(t.type = 'sell_return', final_total, 0)) as total_sell_return"),
                    DB::raw("SUM(IF(t.type = 'sell_return', (SELECT COALESCE(SUM(amount), 0) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id AND {$customer_paid_sql}), 0)) as sell_return_paid"),
                    'contacts.name',
                    'contacts.supplier_business_name',
                    'contacts.id as contact_id'
                );
            } elseif ($due_payment_type == 'sell_return') {
                $customer_paid_sql = Util::sqlPaymentCountsTowardContactDue();
                $query->select(
                    DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', final_total, 0)) as total_invoice"),
                    DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', (SELECT COALESCE(SUM(IF(is_return = 1,-1*amount,amount)), 0) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id AND {$customer_paid_sql}), 0)) as total_paid"),
                    DB::raw("SUM(IF(t.type = 'ledger_discount', t.final_total, 0)) as total_ledger_discount"),
                    DB::raw("SUM(IF(t.type = 'sell_return', final_total, 0)) as total_sell_return"),
                    DB::raw("SUM(IF(t.type = 'sell_return', (SELECT COALESCE(SUM(amount), 0) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id AND {$customer_paid_sql}), 0)) as total_return_paid"),
                    'contacts.name',
                    'contacts.supplier_business_name',
                    'contacts.id as contact_id'
                    );
            }

            //Query for opening balance details
            $opening_balance_paid_sql = in_array($due_payment_type, ['sell', 'sell_return'])
                ? Util::sqlPaymentCountsTowardContactDue()
                : Util::sqlPaymentCountsAsClearedOnly();
            $query->addSelect(
                DB::raw("SUM(IF(t.type = 'opening_balance', final_total, 0)) as opening_balance"),
                DB::raw("SUM(IF(t.type = 'opening_balance', (SELECT COALESCE(SUM(amount), 0) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id AND {$opening_balance_paid_sql}), 0)) as opening_balance_paid")
            );
            $contact_details = $query->first();

            $payment_line = new TransactionPayment();
            if ($due_payment_type == 'purchase') {
                $contact_details->total_purchase = empty($contact_details->total_purchase) ? 0 : $contact_details->total_purchase;
                $payment_line->amount = $contact_details->total_purchase -
                                    $contact_details->total_paid;
            } elseif ($due_payment_type == 'purchase_return') {
                $payment_line->amount = $contact_details->total_purchase_return -
                                    $contact_details->total_return_paid;
            } elseif ($due_payment_type == 'sell') {
                $contact_details->total_invoice = empty($contact_details->total_invoice) ? 0 : $contact_details->total_invoice;
                $contact_details->total_paid = empty($contact_details->total_paid) ? 0 : $contact_details->total_paid;
                $contact_details->total_ledger_discount = empty($contact_details->total_ledger_discount) ? 0 : $contact_details->total_ledger_discount;

                $contact_details->opening_balance = ! empty($contact_details->opening_balance) ? $contact_details->opening_balance : 0;
                $contact_details->opening_balance_paid = ! empty($contact_details->opening_balance_paid) ? $contact_details->opening_balance_paid : 0;
                $ob_due = $contact_details->opening_balance - $contact_details->opening_balance_paid;

                $payment_line->amount = max(0, $contact_details->total_invoice -
                                    $contact_details->total_paid - $contact_details->total_ledger_discount + $ob_due);
            } elseif ($due_payment_type == 'sell_return') {
                $contact_details->total_sell_return = empty($contact_details->total_sell_return) ? 0 : $contact_details->total_sell_return;
                $contact_details->total_return_paid = empty($contact_details->total_return_paid) ? 0 : $contact_details->total_return_paid;

                $payment_line->amount = max(0, $contact_details->total_sell_return -
                                    $contact_details->total_return_paid);
            }

            //If opening balance due exists add to payment amount
            $contact_details->opening_balance = ! empty($contact_details->opening_balance) ? $contact_details->opening_balance : 0;
            $contact_details->opening_balance_paid = ! empty($contact_details->opening_balance_paid) ? $contact_details->opening_balance_paid : 0;
            $ob_due = $contact_details->opening_balance - $contact_details->opening_balance_paid;
            if ($ob_due > 0 && !in_array($due_payment_type, ['sell', 'sell_return'])) {
                $payment_line->amount += $ob_due;
            }

            $amount_formated = $this->transactionUtil->num_f($payment_line->amount);

            $contact_details->total_paid = empty($contact_details->total_paid) ? 0 : $contact_details->total_paid;

            $payment_line->method = 'cash';
            $payment_line->paid_on = \Carbon::now()->toDateTimeString();

            $payment_types = $this->transactionUtil->payment_types(null, false, $business_id);

            //Accounts
            $accounts = $this->moduleUtil->accountsDropdown($business_id, true);

            // For sell_return: calculate existing sale due so modal can offer the 'deduct from due' option
            $sale_due_amount = 0;
            if ($due_payment_type == 'sell_return') {
                $contact_details->total_invoice = empty($contact_details->total_invoice) ? 0 : $contact_details->total_invoice;
                $contact_details->total_paid    = empty($contact_details->total_paid) ? 0 : $contact_details->total_paid;
                $contact_details->total_ledger_discount = empty($contact_details->total_ledger_discount) ? 0 : $contact_details->total_ledger_discount;
                $ob = ($contact_details->opening_balance ?? 0) - ($contact_details->opening_balance_paid ?? 0);
                $sale_due_amount = max(0, $contact_details->total_invoice - $contact_details->total_paid - $contact_details->total_ledger_discount + $ob);
            }

            return view('transaction_payment.pay_supplier_due_modal')
                        ->with(compact('contact_details', 'payment_types', 'payment_line', 'due_payment_type', 'ob_due', 'amount_formated', 'accounts', 'sale_due_amount'));
        }
    }

    /**
     * Adds Payments for Contact due
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postPayContactDue(Request $request)
    {
        if (! (auth()->user()->can('sell.payments') || auth()->user()->can('purchase.payments'))) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $business_id = $request->session()->get('business.id');
            $contact_id = $request->input('contact_id');
            $due_payment_type = $request->input('due_payment_type', 'sell');

            $previous_due = $this->getContactDueAmountByType($contact_id, $due_payment_type, $business_id);
            $amount_paid = $this->transactionUtil->num_uf($request->input('amount'));

            // If the user chose to deduct the return credit from the customer's existing sale due,
            // we need to perform two actions:
            // 1. Pay the 'sell' transactions (reduces what the customer owes).
            // 2. Pay the 'sell_return' transactions (marks the return credit as "used/cleared").
            $return_credit_action = $request->input('return_credit_action', 'pay_back');
            $is_deduction = ($due_payment_type === 'sell_return' && $return_credit_action === 'deduct_from_due');

            DB::beginTransaction();

            if ($is_deduction) {
                // Only offset up to the customer's gross sale due. Any excess return credit
                // must remain as sell_return due (refundable), not advance balance.
                $sale_due_amount = $this->getContactGrossSaleDueAmount($contact_id, $business_id);
                $deduct_amount = min($amount_paid, $sale_due_amount);

                if ($deduct_amount <= 0) {
                    throw new \Exception(__('lang_v1.no_due_balance_to_offset'));
                }

                $request->merge([
                    'amount' => $this->transactionUtil->num_f($deduct_amount),
                    'suppress_excess_advance' => true,
                    'due_payment_type' => 'sell',
                ]);
                $tp_sell = $this->transactionUtil->payContact($request);

                // Mark the same portion of return credit as applied against sale due.
                $request->merge(['due_payment_type' => 'sell_return']);
                $tp_return = $this->transactionUtil->payContact($request);

                $tp = $tp_sell; // Use the sell payment as the primary reference for the register
            } else {
                $tp = $this->transactionUtil->payContact($request);
            }

            //payContact creates child payments allocated to actual due transactions.
            //Push each allocated child payment to register movement.
            $allocated_payments = TransactionPayment::where('parent_id', $tp->id)
                ->whereNotNull('transaction_id')
                ->with('transaction')
                ->get();

            $total_allocated = $allocated_payments->sum('amount');

            foreach ($allocated_payments as $allocated_payment) {
                if (!empty($allocated_payment->transaction)) {
                    $this->cashRegisterUtil->addTransactionPaymentToRegister(
                        $allocated_payment->transaction,
                        $allocated_payment,
                        $allocated_payment->created_by
                    );
                }
            }

            // Also record the PARENT payment into the register for the advance/excess portion.
            // This covers:
            // (a) Pure advance payments (no due invoices, full amount is advance balance)
            // (b) Excess payments (some allocated to invoices, rest goes to advance balance)
            // (c) Pay-back to customer for sell_return (no allocation — full amount is cash-out)
            $advance_amount = $tp->amount - $total_allocated;
            if ($advance_amount > 0.001) {
                // Build a synthetic transaction stub for the register entry
                // so the correct credit/debit type is determined by contact type.
                $contact_for_register = Contact::find($contact_id);
                $synthetic_transaction = (object) [
                    'type' => ($contact_for_register && $contact_for_register->type === 'supplier') ? 'purchase' : 'sell',
                    'contact_id' => $contact_id,
                    'id' => null,
                ];
                // Clone the payment object and set amount to the advance portion only
                $advance_payment_entry = clone $tp;
                $advance_payment_entry->amount = $advance_amount;
                $this->cashRegisterUtil->addTransactionPaymentToRegister(
                    $synthetic_transaction,
                    $advance_payment_entry,
                    $tp->created_by
                );
            }

            $pos_settings = ! empty(session()->get('business.pos_settings')) ? json_decode(session()->get('business.pos_settings'), true) : [];
            $enable_cash_denomination_for_payment_methods = ! empty($pos_settings['enable_cash_denomination_for_payment_methods']) ? $pos_settings['enable_cash_denomination_for_payment_methods'] : [];
            //add cash denomination
            if (in_array($tp->method, $enable_cash_denomination_for_payment_methods) && ! empty($request->input('denominations')) && ! empty($pos_settings['enable_cash_denomination_on']) && $pos_settings['enable_cash_denomination_on'] == 'all_screens') {
                $denominations = [];

                foreach ($request->input('denominations') as $key => $value) {
                    if (! empty($value)) {
                        $denominations[] = [
                            'business_id' => $business_id,
                            'amount' => $key,
                            'total_count' => $value,
                        ];
                    }
                }

                if (! empty($denominations)) {
                    $tp->denominations()->createMany($denominations);
                }
            }

            DB::commit();

            // After commit, safely sync all active installment plans for this customer.
            // This ensures all child transaction_payments are readable from the DB.
            try {
                $installmentUtil = app(\App\Utils\InstallmentUtil::class);
                $active_plans = \App\InstallmentPlan::whereHas('transaction', function ($q) use ($contact_id) {
                    $q->where('contact_id', $contact_id);
                })->where('status', 'active')->get();

                foreach ($active_plans as $plan) {
                    $installmentUtil->syncPlanPaymentStatus($plan);
                }
            } catch (\Exception $e) {
                \Log::emergency('Installment sync failed: ' . $e->getMessage());
            }

            $output = [
                'success' => true,
                'msg' => __('purchase.payment_added_success'),
            ];

            if ($request->ajax()) {
                $contact = Contact::where('business_id', $business_id)->findOrFail($contact_id);
                $total_due = $this->getContactDueAmountByType($contact_id, $due_payment_type, $business_id);

                $business = $request->session()->get('business');
                $business_name = $request->session()->get('business.name');
                if (empty($business_name)) {
                    $business_name = is_array($business) ? ($business['name'] ?? '') : ($business->name ?? '');
                }

                $location = BusinessLocation::where('business_id', $business_id)
                    ->where('is_active', 1)
                    ->first();

                $cashier_name = trim((auth()->user()->first_name ?? '').' '.(auth()->user()->last_name ?? ''));
                if (empty($cashier_name)) {
                    $cashier_name = auth()->user()->username ?? '';
                }

                $next_due_date = null;
                if (! empty($contact->pay_term_number) && ! empty($contact->pay_term_type)) {
                    $next_due_date = $contact->pay_term_type === 'months'
                        ? \Carbon\Carbon::now()->addMonths($contact->pay_term_number)
                        : \Carbon\Carbon::now()->addDays($contact->pay_term_number);
                    $next_due_date = $this->transactionUtil->format_date($next_due_date->toDateTimeString());
                }

                $receipt_html = view('transaction_payment.due_payment_receipt', [
                    'business_name' => $business_name,
                    'location_name' => $location->name ?? null,
                    'location_address' => $location->location_address ?? null,
                    'location_contact' => $location->mobile ?? null,
                    'location_email' => $location->email ?? null,

                    'payment_date' => $this->transactionUtil->format_date($tp->paid_on, true),
                    'payment_ref_no' => $tp->payment_ref_no ?? '',
                    'cashier_name' => $cashier_name,

                    'contact_name' => $contact->name ?? '',
                    'contact_mobile' => $contact->mobile ?? '',

                    'previous_due' => $previous_due,
                    'amount_paid' => $amount_paid,
                    'total_due' => $total_due,
                    'next_due_date' => $next_due_date,

                    'footer_text' => $request->session()->get('business.receipt_footer') ?? '',
                ])->render();

                $output['receipt'] = [
                    'html_content' => $receipt_html,
                ];
                $output['print_title'] = $tp->payment_ref_no ?? 'Payment Receipt';
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => 'File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage(),
            ];
        }

        if ($request->ajax()) {
            return response()->json($output);
        }

        return redirect()->back()->with(['status' => $output]);
    }

    private function getContactDueAmountByType($contact_id, $due_payment_type, $business_id)
    {
        $query = Contact::where('contacts.id', $contact_id)
            ->where('contacts.business_id', $business_id)
            ->leftJoin('transactions as t', 'contacts.id', '=', 't.contact_id');

        if ($due_payment_type === 'purchase') {
            $query->select(
                DB::raw("SUM(IF(t.type = 'purchase', final_total, 0)) as total_purchase"),
                DB::raw("SUM(IF(t.type = 'purchase', (SELECT COALESCE(SUM(amount), 0) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id AND (transaction_payments.method != 'cheque' OR transaction_payments.cheque_status = 'cleared')), 0)) as total_paid")
            );
        } else {
            // default to sell – include sell_return so credit notes reduce the due
            $customer_paid_sql = Util::sqlPaymentCountsTowardContactDue();
            $query->select(
                DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', final_total, 0)) as total_invoice"),
                DB::raw("SUM(IF(t.type = 'sell', (SELECT SUM(IF(is_return = 1, -1*amount, amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id AND {$customer_paid_sql}), 0)) as total_paid"),
                DB::raw("SUM(IF(t.type = 'ledger_discount', t.final_total, 0)) as total_ledger_discount"),
                DB::raw("SUM(IF(t.type = 'sell_return', final_total, 0)) as total_sell_return"),
                DB::raw("SUM(IF(t.type = 'sell_return', (SELECT COALESCE(SUM(amount), 0) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id AND {$customer_paid_sql}), 0)) as sell_return_paid")
            );
        }

        $opening_balance_paid_sql = $due_payment_type === 'purchase'
            ? Util::sqlPaymentCountsAsClearedOnly()
            : Util::sqlPaymentCountsTowardContactDue();
        $query->addSelect(
            DB::raw("SUM(IF(t.type = 'opening_balance', final_total, 0)) as opening_balance"),
            DB::raw("SUM(IF(t.type = 'opening_balance', (SELECT COALESCE(SUM(amount), 0) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id AND {$opening_balance_paid_sql}), 0)) as opening_balance_paid")
        );

        $details = $query->first();
        if (empty($details)) {
            return 0;
        }

        $ob_due = (float) (($details->opening_balance ?? 0) - ($details->opening_balance_paid ?? 0));
        $due = 0;

        if ($due_payment_type === 'purchase') {
            $due = (float) (($details->total_purchase ?? 0) - ($details->total_paid ?? 0));
        } elseif ($due_payment_type === 'sell') {
            $total_sell_return = (float) ($details->total_sell_return ?? 0);
            $sell_return_paid = (float) ($details->sell_return_paid ?? 0);
            $sell_return_due = $total_sell_return - $sell_return_paid;

            $due = (float) (($details->total_invoice ?? 0) - ($details->total_paid ?? 0) - ($details->total_ledger_discount ?? 0) + $ob_due - $sell_return_due);
            $due = max(0, $due);
            $ob_due = 0; // Already added
        } elseif ($due_payment_type === 'sell_return') {
            $total_sell_return = (float) ($details->total_sell_return ?? 0);
            $sell_return_paid = (float) ($details->sell_return_paid ?? 0);
            $sell_return_due = $total_sell_return - $sell_return_paid;
            $total_ledger_discount = (float) ($details->total_ledger_discount ?? 0);

            $sale_due = (float) (($details->total_invoice ?? 0) - ($details->total_paid ?? 0) - $total_ledger_discount + $ob_due);
            $due = max(0, $sell_return_due - $sale_due);
            $ob_due = 0; // Already handled in sale_due
        }

        if ($ob_due > 0) {
            $due += $ob_due;
        }

        return $due;
    }

    /**
     * Gross sale due before netting sell-return credit (used for return-credit offset).
     */
    private function getContactGrossSaleDueAmount($contact_id, $business_id)
    {
        $customer_paid_sql = Util::sqlPaymentCountsTowardContactDue();
        $opening_balance_paid_sql = Util::sqlPaymentCountsTowardContactDue();

        $details = Contact::where('contacts.id', $contact_id)
            ->where('contacts.business_id', $business_id)
            ->leftJoin('transactions as t', 'contacts.id', '=', 't.contact_id')
            ->select(
                DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', final_total, 0)) as total_invoice"),
                DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', (SELECT COALESCE(SUM(IF(is_return = 1,-1*amount,amount)), 0) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id AND {$customer_paid_sql}), 0)) as total_paid"),
                DB::raw("SUM(IF(t.type = 'ledger_discount', t.final_total, 0)) as total_ledger_discount"),
                DB::raw("SUM(IF(t.type = 'opening_balance', final_total, 0)) as opening_balance"),
                DB::raw("SUM(IF(t.type = 'opening_balance', (SELECT COALESCE(SUM(amount), 0) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id AND {$opening_balance_paid_sql}), 0)) as opening_balance_paid")
            )
            ->first();

        if (empty($details)) {
            return 0;
        }

        $ob_due = (float) (($details->opening_balance ?? 0) - ($details->opening_balance_paid ?? 0));

        return max(0, (float) (($details->total_invoice ?? 0) - ($details->total_paid ?? 0) - ($details->total_ledger_discount ?? 0) + $ob_due));
    }

    /**
     * view details of single..,
     * payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function viewPayment($payment_id)
    {
        if (! (auth()->user()->can('sell.payments') ||
                auth()->user()->can('purchase.payments') ||
                auth()->user()->can('edit_sell_payment') ||
                auth()->user()->can('delete_sell_payment') ||
                auth()->user()->can('edit_purchase_payment') ||
                auth()->user()->can('delete_purchase_payment') ||
                auth()->user()->can('hms.add_booking_payment')
            )) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('business.id');
            $single_payment_line = TransactionPayment::findOrFail($payment_id);

            $transaction = null;
            if (! empty($single_payment_line->transaction_id)) {
                $transaction = Transaction::where('id', $single_payment_line->transaction_id)
                                ->with(['contact', 'location', 'transaction_for'])
                                ->first();
            } else {
                $child_payment = TransactionPayment::where('business_id', $business_id)
                        ->where('parent_id', $payment_id)
                        ->with(['transaction', 'transaction.contact', 'transaction.location', 'transaction.transaction_for'])
                        ->first();
                $transaction = ! empty($child_payment) ? $child_payment->transaction : null;
            }

            $payment_types = $this->transactionUtil->payment_types(null, false, $business_id);

            return view('transaction_payment.single_payment_view')
                    ->with(compact('single_payment_line', 'transaction', 'payment_types'));
        }
    }

    /**
     * Retrieves all the child payments of a parent payments
     * payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showChildPayments($payment_id)
    {
        if (! (auth()->user()->can('sell.payments') ||
                auth()->user()->can('purchase.payments') ||
                auth()->user()->can('edit_sell_payment') ||
                auth()->user()->can('delete_sell_payment') ||
                auth()->user()->can('edit_purchase_payment') ||
                auth()->user()->can('delete_purchase_payment')
            )) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('business.id');

            $child_payments = TransactionPayment::where('business_id', $business_id)
                                                    ->where('parent_id', $payment_id)
                                                    ->with(['transaction', 'transaction.contact'])
                                                    ->get();

            $payment_types = $this->transactionUtil->payment_types(null, false, $business_id);

            return view('transaction_payment.show_child_payments')
                    ->with(compact('child_payments', 'payment_types'));
        }
    }

    /**
     * Retrieves list of all opening balance payments.
     *
     * @param  int  $contact_id
     * @return \Illuminate\Http\Response
     */
    public function getOpeningBalancePayments($contact_id)
    {
        if (! (auth()->user()->can('sell.payments') ||
                auth()->user()->can('purchase.payments') ||
                auth()->user()->can('edit_sell_payment') ||
                auth()->user()->can('delete_sell_payment') ||
                auth()->user()->can('edit_purchase_payment') ||
                auth()->user()->can('delete_purchase_payment')
            )) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('business.id');
        if (request()->ajax()) {
            $query = TransactionPayment::leftjoin('transactions as t', 'transaction_payments.transaction_id', '=', 't.id')
                ->where('t.business_id', $business_id)
                ->where('t.type', 'opening_balance')
                ->where('t.contact_id', $contact_id)
                ->where('transaction_payments.business_id', $business_id)
                ->select(
                    'transaction_payments.amount',
                    'method',
                    'paid_on',
                    'transaction_payments.payment_ref_no',
                    'transaction_payments.document',
                    'transaction_payments.id',
                    'cheque_number',
                    'card_transaction_number',
                    'bank_account_number'
                )
                ->groupBy('transaction_payments.id');

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('t.location_id', $permitted_locations);
            }

            return Datatables::of($query)
                ->editColumn('paid_on', '{{@format_datetime($paid_on)}}')
                ->editColumn('method', function ($row) {
                    $method = __('lang_v1.'.$row->method);
                    if ($row->method == 'cheque') {
                        $method .= '<br>('.__('lang_v1.cheque_no').': '.$row->cheque_number.')';
                    } elseif ($row->method == 'card') {
                        $method .= '<br>('.__('lang_v1.card_transaction_no').': '.$row->card_transaction_number.')';
                    } elseif ($row->method == 'bank_transfer') {
                        $method .= '<br>('.__('lang_v1.bank_account_no').': '.$row->bank_account_number.')';
                    } elseif ($row->method == 'custom_pay_1') {
                        $method = __('lang_v1.custom_payment_1').'<br>('.__('lang_v1.transaction_no').': '.$row->transaction_no.')';
                    } elseif ($row->method == 'custom_pay_2') {
                        $method = __('lang_v1.custom_payment_2').'<br>('.__('lang_v1.transaction_no').': '.$row->transaction_no.')';
                    } elseif ($row->method == 'custom_pay_3') {
                        $method = __('lang_v1.custom_payment_3').'<br>('.__('lang_v1.transaction_no').': '.$row->transaction_no.')';
                    }

                    return $method;
                })
                ->editColumn('amount', function ($row) {
                    return '<span class="display_currency paid-amount" data-orig-value="'.$row->amount.'" data-currency_symbol = true>'.$row->amount.'</span>';
                })
                ->addColumn('action', '<button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline  tw-dw-btn-primary view_payment" data-href="{{ action([\App\Http\Controllers\TransactionPaymentController::class, \'viewPayment\'], [$id]) }}"><i class="fas fa-eye"></i> @lang("messages.view")
                    </button> <button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline  tw-dw-btn-info edit_payment" 
                    data-href="{{action([\App\Http\Controllers\TransactionPaymentController::class, \'edit\'], [$id]) }}"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
                    &nbsp; <button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline  tw-dw-btn-error delete_payment" 
                    data-href="{{ action([\App\Http\Controllers\TransactionPaymentController::class, \'destroy\'], [$id]) }}"
                    ><i class="fa fa-trash" aria-hidden="true"></i> @lang("messages.delete")</button> @if(!empty($document))<a href="{{asset("/uploads/documents/" . $document)}}" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline  tw-dw-btn-accent" download=""><i class="fa fa-download"></i> @lang("purchase.download_document")</a>@endif')
                ->rawColumns(['amount', 'method', 'action'])
                ->make(true);
        }
    }
}
