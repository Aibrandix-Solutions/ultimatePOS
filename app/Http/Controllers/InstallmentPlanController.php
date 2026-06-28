<?php

namespace App\Http\Controllers;

use App\InstallmentPlan;
use App\Transaction;
use App\Utils\InstallmentUtil;
use App\Utils\TransactionUtil;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InstallmentPlanController extends Controller
{
    protected $transactionUtil;

    protected $installmentUtil;

    public function __construct(TransactionUtil $transactionUtil, InstallmentUtil $installmentUtil)
    {
        $this->transactionUtil = $transactionUtil;
        $this->installmentUtil = $installmentUtil;
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
                ->filterColumn('t.invoice_no', function ($query, $keyword) {
                    $query->where('t.invoice_no', 'like', "%{$keyword}%");
                })
                ->filterColumn('c.name', function ($query, $keyword) {
                    $query->where('c.name', 'like', "%{$keyword}%");
                })
                ->filterColumn('t.transaction_date', function ($query, $keyword) {
                    $query->where('t.transaction_date', 'like', "%{$keyword}%");
                })
                ->filterColumn('installment_plans.status', function ($query, $keyword) {
                    $query->where('installment_plans.status', 'like', "%{$keyword}%");
                })
                ->filterColumn('installment_plans.installment_count', function ($query, $keyword) {
                    $query->where('installment_plans.installment_count', 'like', "%{$keyword}%");
                })
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
        $sell_return_total = ! empty($transaction) ? $this->installmentUtil->getSellReturnTotal($transaction->id) : 0;
        $effective_total = ! empty($transaction) ? $this->installmentUtil->getEffectiveSaleTotal($transaction) : 0;
        $balance_due = ! empty($transaction) ? ((float) $effective_total - (float) $paid_amount) : 0;
        $remaining_installment_balance = $this->installmentUtil->getRemainingInstallmentBalance($plan, $transaction);
        $pending_lines_total = $this->installmentUtil->getPendingLinesTotal($plan);
        $needs_adjustment = $this->installmentUtil->planNeedsAdjustment($plan);
        $suggested_amounts = $this->installmentUtil->getSuggestedPendingAmounts($plan);
        $can_edit = auth()->user()->can('sell.payments');
        $show_adjust_alert = $needs_adjustment || (int) request()->input('adjust', 0) === 1;
        $customer_credit = $this->installmentUtil->getCustomerCreditAfterReturns($plan, $transaction);

        return view('installments.show', compact(
            'plan',
            'transaction',
            'paid_amount',
            'balance_due',
            'sell_return_total',
            'effective_total',
            'remaining_installment_balance',
            'pending_lines_total',
            'needs_adjustment',
            'suggested_amounts',
            'can_edit',
            'show_adjust_alert',
            'customer_credit'
        ));
    }

    public function updateLines(Request $request, $id)
    {
        if (! auth()->user()->can('sell.payments')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');
        $plan = InstallmentPlan::where('business_id', $business_id)
            ->with(['lines' => function ($q) {
                $q->orderBy('sequence');
            }])
            ->findOrFail($id);

        if ($plan->status !== 'active') {
            return redirect()
                ->action([\App\Http\Controllers\InstallmentPlanController::class, 'show'], [$plan->id])
                ->with('status', ['success' => 0, 'msg' => __('lang_v1.installment_plan_not_active')]);
        }

        $pending_count = $plan->lines->where('status', '!=', 'paid')->count();
        if ($pending_count === 0) {
            return redirect()
                ->action([\App\Http\Controllers\InstallmentPlanController::class, 'show'], [$plan->id])
                ->with('status', ['success' => 0, 'msg' => __('lang_v1.installment_no_pending_lines')]);
        }

        try {
            DB::beginTransaction();

            $this->installmentUtil->updatePendingLineAmounts(
                $plan,
                (array) $request->input('line_amounts', [])
            );

            $plan = $plan->fresh();
            $remaining = $this->installmentUtil->getRemainingInstallmentBalance($plan);
            if ($remaining < 0.0001 && $plan->status === 'active') {
                $plan->status = 'closed';
                $plan->closed_at = now();
                $plan->save();
            }

            DB::commit();

            return redirect()
                ->action([\App\Http\Controllers\InstallmentPlanController::class, 'show'], [$plan->id])
                ->with('status', ['success' => 1, 'msg' => __('lang_v1.installment_plan_updated_success')]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->action([\App\Http\Controllers\InstallmentPlanController::class, 'show'], [$plan->id])
                ->with('status', ['success' => 0, 'msg' => $e->getMessage()]);
        }
    }
}
