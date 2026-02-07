<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Transaction;
use App\TransactionSellLine;
use App\WarrantyClaim;
use App\WarrantyClaimStatusLog;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WarrantyClaimController extends Controller
{
    public function index()
    {
        $business_id = request()->session()->get('user.business_id');

        if (request()->ajax()) {
            $query = DB::table('warranty_claims as wc')
                ->join('transactions as t', 'wc.transaction_id', '=', 't.id')
                ->join('transaction_sell_lines as tsl', 'wc.sell_line_id', '=', 'tsl.id')
                ->join('contacts as c', 'wc.contact_id', '=', 'c.id')
                ->join('products as p', 'tsl.product_id', '=', 'p.id')
                ->leftJoin('variations as v', 'tsl.variation_id', '=', 'v.id')
                ->leftJoin('sell_line_warranties as slw', 'slw.sell_line_id', '=', 'tsl.id')
                ->leftJoin('warranties as w', 'w.id', '=', 'slw.warranty_id')
                ->where('wc.business_id', $business_id)
                ->select([
                    'wc.id',
                    'wc.status',
                    'wc.received_at',
                    'wc.sent_to_supplier_at',
                    'wc.received_from_supplier_at',
                    'wc.returned_to_customer_at',
                    'wc.closed_at',
                    't.invoice_no',
                    't.transaction_date',
                    'c.name as customer_name',
                    'p.name as product_name',
                    'v.name as variation_name',
                    'w.name as warranty_name',
                    'wc.created_at',
                ]);

            return DataTables::of($query)
                ->editColumn('created_at', function ($row) {
                    return !empty($row->created_at) ? Carbon::parse($row->created_at)->format('Y-m-d H:i') : '';
                })
                ->addColumn('product', function ($row) {
                    $name = $row->product_name;
                    if (!empty($row->variation_name) && $row->variation_name !== 'DUMMY') {
                        $name .= ' - ' . $row->variation_name;
                    }
                    return $name;
                })
                ->addColumn('action', function ($row) {
                    $url = action([\App\Http\Controllers\WarrantyClaimController::class, 'show'], [$row->id]);
                    return '<a class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary" href="' . e($url) . '">' . __('messages.view') . '</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('warranty_claims.index');
    }

    public function store(Request $request)
    {
        $business_id = request()->session()->get('user.business_id');

        try {
            $request->validate([
                'transaction_id' => 'required|integer',
                'sell_line_id' => 'required|integer',
                'problem' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            $transaction = Transaction::where('business_id', $business_id)
                ->where('type', 'sell')
                ->where('status', 'final')
                ->findOrFail((int) $request->input('transaction_id'));

            $sell_line = TransactionSellLine::where('transaction_id', $transaction->id)
                ->findOrFail((int) $request->input('sell_line_id'));

            $has_warranty = DB::table('sell_line_warranties')
                ->where('sell_line_id', $sell_line->id)
                ->exists();

            if (!$has_warranty) {
                $output = ['success' => false, 'msg' => __('lang_v1.no_warranty_for_line')];
                return $request->ajax() ? $output : back()->with('status', $output);
            }

            $has_open_claim = WarrantyClaim::where('business_id', $business_id)
                ->where('sell_line_id', $sell_line->id)
                ->whereNull('closed_at')
                ->exists();

            if ($has_open_claim) {
                $output = ['success' => false, 'msg' => __('lang_v1.claim_already_exists')];
                return $request->ajax() ? $output : back()->with('status', $output);
            }

            DB::beginTransaction();

            $claim = WarrantyClaim::create([
                'business_id' => $business_id,
                'transaction_id' => $transaction->id,
                'sell_line_id' => $sell_line->id,
                'contact_id' => $transaction->contact_id,
                'supplier_id' => null,
                'status' => WarrantyClaim::STATUS_RECEIVED_FROM_CUSTOMER,
                'problem' => $request->input('problem'),
                'notes' => $request->input('notes'),
                'received_at' => Carbon::now(),
                'created_by' => auth()->id(),
            ]);

            WarrantyClaimStatusLog::create([
                'warranty_claim_id' => $claim->id,
                'to_status' => WarrantyClaim::STATUS_RECEIVED_FROM_CUSTOMER,
                'note' => $request->input('notes'),
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            $output = ['success' => true, 'msg' => __('lang_v1.added_success')];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());
            $output = ['success' => false, 'msg' => __('messages.something_went_wrong')];
        }

        return $request->ajax() ? $output : redirect()->back()->with('status', $output);
    }

    public function show(WarrantyClaim $warranty_claim)
    {
        $business_id = request()->session()->get('user.business_id');
        abort_unless($warranty_claim->business_id == $business_id, 404);

        $warranty_claim->load([
            'transaction.contact',
            'sell_line.product',
            'sell_line.variations',
            'sell_line.warranties',
            'customer',
            'supplier',
            'status_logs.created_by_user',
        ]);

        $status_sequence = WarrantyClaim::statusSequence();
        $current_index = array_search($warranty_claim->status, $status_sequence, true);
        $next_statuses = [];
        if ($current_index !== false && isset($status_sequence[$current_index + 1])) {
            $next_statuses[] = $status_sequence[$current_index + 1];
        }

        return view('warranty_claims.show')->with(compact('warranty_claim', 'next_statuses'));
    }

    public function updateStatus(Request $request, WarrantyClaim $warranty_claim)
    {
        $business_id = request()->session()->get('user.business_id');
        abort_unless($warranty_claim->business_id == $business_id, 404);

        $request->validate([
            'to_status' => 'required|string',
            'note' => 'nullable|string',
            'supplier_id' => 'nullable|integer',
        ]);

        $to_status = $request->input('to_status');
        $sequence = WarrantyClaim::statusSequence();

        if (!in_array($to_status, $sequence, true)) {
            return ['success' => false, 'msg' => __('messages.something_went_wrong')];
        }

        $current_index = array_search($warranty_claim->status, $sequence, true);
        $target_index = array_search($to_status, $sequence, true);

        if ($current_index === false || $target_index === false || $target_index <= $current_index) {
            return ['success' => false, 'msg' => __('lang_v1.invalid_status_change')];
        }

        try {
            DB::beginTransaction();

            if ($to_status === WarrantyClaim::STATUS_SENT_TO_SUPPLIER) {
                $supplier_id = (int) $request->input('supplier_id');
                if (empty($supplier_id)) {
                    DB::rollBack();
                    return ['success' => false, 'msg' => __('lang_v1.supplier_required')];
                }

                $supplier_exists = Contact::where('business_id', $business_id)
                    ->whereIn('type', ['supplier', 'both'])
                    ->where('id', $supplier_id)
                    ->exists();

                if (!$supplier_exists) {
                    DB::rollBack();
                    return ['success' => false, 'msg' => __('lang_v1.supplier_required')];
                }

                $warranty_claim->supplier_id = $supplier_id;
                $warranty_claim->sent_to_supplier_at = Carbon::now();
            }

            if ($to_status === WarrantyClaim::STATUS_RECEIVED_FROM_SUPPLIER) {
                $warranty_claim->received_from_supplier_at = Carbon::now();
            }

            if ($to_status === WarrantyClaim::STATUS_RETURNED_TO_CUSTOMER) {
                $warranty_claim->returned_to_customer_at = Carbon::now();
                $warranty_claim->closed_at = Carbon::now();
            }

            $warranty_claim->status = $to_status;
            $warranty_claim->save();

            WarrantyClaimStatusLog::create([
                'warranty_claim_id' => $warranty_claim->id,
                'to_status' => $to_status,
                'note' => $request->input('note'),
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return ['success' => true, 'msg' => __('lang_v1.updated_success')];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());
            return ['success' => false, 'msg' => __('messages.something_went_wrong')];
        }
    }
}
