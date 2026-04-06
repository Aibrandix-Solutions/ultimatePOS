<?php

namespace App\Http\Controllers;

use App\InstallmentPlan;
use App\Transaction;
use App\Utils\TransactionUtil;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InstallmentPlanController extends Controller
{
    protected $transactionUtil;

    public function __construct(TransactionUtil $transactionUtil)
    {
        $this->transactionUtil = $transactionUtil;
    }

    public function index(Request $request)
    {
        if (! (auth()->user()->can('sell.view') || auth()->user()->can('sell.payments'))) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        if ($request->ajax()) {
            $query = InstallmentPlan::query()
                ->where('installment_plans.business_id', $business_id)
                ->leftJoin('transactions as t', 'installment_plans.transaction_id', '=', 't.id')
                ->leftJoin('contacts as c', 'installment_plans.contact_id', '=', 'c.id')
                ->select([
                    'installment_plans.id',
                    'installment_plans.transaction_id',
                    'installment_plans.down_payment',
                    'installment_plans.installment_count',
                    'installment_plans.interval',
                    'installment_plans.interval_type',
                    'installment_plans.first_due_date',
                    'installment_plans.status',
                    't.invoice_no',
                    't.transaction_date',
                    't.final_total',
                    'c.name as customer_name',
                ])
                ->selectSub(function ($subquery) {
                    $subquery->from('installment_plan_lines')
                        ->select('due_date')
                        ->whereColumn('installment_plan_lines.installment_plan_id', 'installment_plans.id')
                        ->where('status', 'pending')
                        ->orderBy('sequence')
                        ->limit(1);
                }, 'next_due_date');

            // Apply date range filter if provided
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

            if (! empty($start_date)) {
                $query->whereDate('t.transaction_date', '>=', $this->transactionUtil->uf_date($start_date));
            }

            if (! empty($end_date)) {
                $query->whereDate('t.transaction_date', '<=', $this->transactionUtil->uf_date($end_date));
            }

            return DataTables::of($query)
                ->editColumn('transaction_date', function ($row) {
                    return ! empty($row->transaction_date) ? $this->transactionUtil->format_date($row->transaction_date, true) : '';
                })
                ->editColumn('next_due_date', function ($row) {
                    return ! empty($row->next_due_date) ? $this->transactionUtil->format_date($row->next_due_date) : '';
                })
                ->editColumn('final_total', function ($row) {
                    return '<span class="display_currency" data-currency_symbol="true">' . $row->final_total . '</span>';
                })
                ->editColumn('down_payment', function ($row) {
                    return '<span class="display_currency" data-currency_symbol="true">' . $row->down_payment . '</span>';
                })
                ->addColumn('balance_due', function ($row) {
                    $paid = 0;
                    if (! empty($row->transaction_id)) {
                        $paid = $this->transactionUtil->getTotalPaid($row->transaction_id);
                    }
                    $due = (float) ($row->final_total ?? 0) - (float) $paid;
                    return '<span class="display_currency" data-currency_symbol="true">' . $due . '</span>';
                })
                ->addColumn('interval_label', function ($row) {
                    $type = $row->interval_type;
                    if ($type === 'days') {
                        $type = __('lang_v1.days');
                    } elseif ($type === 'weeks') {
                        $type = __('lang_v1.weeks');
                    } else {
                        $type = __('lang_v1.months');
                    }
                    return (int) $row->interval . ' ' . $type;
                })
                ->addColumn('action', function ($row) {
                    $url = action([\App\Http\Controllers\InstallmentPlanController::class, 'show'], [$row->id]);
                    return '<a class="btn btn-xs btn-primary" href="' . $url . '">' . __('messages.view') . '</a>';
                })
                ->rawColumns(['final_total', 'down_payment', 'balance_due', 'action'])
                ->make(true);
        }

        return view('installments.index');
    }

    public function show($id)
    {
        if (! (auth()->user()->can('sell.view') || auth()->user()->can('sell.payments'))) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $plan = InstallmentPlan::where('business_id', $business_id)
            ->with(['lines' => function ($q) {
                $q->orderBy('sequence');
            }, 'transaction', 'contact'])
            ->findOrFail($id);

        $transaction = $plan->transaction;
        $paid_amount = ! empty($transaction) ? $this->transactionUtil->getTotalPaid($transaction->id) : 0;
        $balance_due = ! empty($transaction) ? ((float) $transaction->final_total - (float) $paid_amount) : 0;

        return view('installments.show', compact('plan', 'transaction', 'paid_amount', 'balance_due'));
    }
}
