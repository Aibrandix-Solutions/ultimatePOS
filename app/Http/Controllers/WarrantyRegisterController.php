<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WarrantyRegisterController extends Controller
{
    public function index()
    {
        $business_id = request()->session()->get('user.business_id');

        if (request()->ajax()) {
            $query = DB::table('transaction_sell_lines as tsl')
                ->join('transactions as t', 'tsl.transaction_id', '=', 't.id')
                ->join('contacts as c', 't.contact_id', '=', 'c.id')
                ->join('products as p', 'tsl.product_id', '=', 'p.id')
                ->leftJoin('variations as v', 'tsl.variation_id', '=', 'v.id')
                ->join('sell_line_warranties as slw', 'slw.sell_line_id', '=', 'tsl.id')
                ->join('warranties as w', 'w.id', '=', 'slw.warranty_id')
                ->leftJoin('warranty_claims as wc', function ($join) use ($business_id) {
                    $join->on('wc.sell_line_id', '=', 'tsl.id')
                        ->where('wc.business_id', '=', $business_id)
                        ->whereNull('wc.closed_at');
                })
                ->where('t.business_id', $business_id)
                ->where('t.type', 'sell')
                ->where('t.status', 'final')
                ->select([
                    'tsl.id as sell_line_id',
                    't.id as transaction_id',
                    't.invoice_no',
                    't.transaction_date',
                    'c.name as customer_name',
                    'p.name as product_name',
                    'v.name as variation_name',
                    'tsl.quantity',
                    'w.id as warranty_id',
                    'w.name as warranty_name',
                    'w.duration',
                    'w.duration_type',
                    'wc.id as claim_id',
                    'wc.status as claim_status',
                ]);

            return DataTables::of($query)
                ->editColumn('transaction_date', function ($row) {
                    return !empty($row->transaction_date) ? Carbon::parse($row->transaction_date)->format('Y-m-d H:i') : '';
                })
                ->addColumn('product', function ($row) {
                    $name = $row->product_name;
                    if (!empty($row->variation_name) && $row->variation_name !== 'DUMMY') {
                        $name .= ' - ' . $row->variation_name;
                    }
                    return $name;
                })
                ->addColumn('warranty_exp_date', function ($row) {
                    if (empty($row->transaction_date) || empty($row->duration) || empty($row->duration_type)) {
                        return '';
                    }

                    $date = Carbon::parse($row->transaction_date);
                    if ($row->duration_type === 'days') {
                        $date->addDays((int) $row->duration);
                    } elseif ($row->duration_type === 'months') {
                        $date->addMonths((int) $row->duration);
                    } elseif ($row->duration_type === 'years') {
                        $date->addYears((int) $row->duration);
                    }

                    return $date->format('Y-m-d H:i');
                })
                ->addColumn('action', function ($row) {
                    if (!empty($row->claim_id)) {
                        $url = action([\App\Http\Controllers\WarrantyClaimController::class, 'show'], [$row->claim_id]);
                        return '<a class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary" href="' . e($url) . '">' . __('messages.view') . '</a>';
                    }

                    return '<button type="button" class="tw-dw-btn tw-dw-btn-xs tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none btn-create-warranty-claim"
                        data-transaction-id="' . e($row->transaction_id) . '"
                        data-sell-line-id="' . e($row->sell_line_id) . '"
                        data-invoice-no="' . e($row->invoice_no) . '"
                        data-customer-name="' . e($row->customer_name) . '"
                        data-product-name="' . e($row->product_name) . '">
                        ' . __('lang_v1.add_claim') .
                    '</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('warranty_register.index');
    }
}
