<?php

namespace App\Http\Controllers;

use App\BusinessLocation;
use App\Category;
use App\Product;
use App\PurchaseLine;
use App\Utils\ModuleUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

/**
 * Batch Details page (Products → Batch Details).
 *
 * Shows one row per purchase line in a multi-batch context: only SKU + location
 * combinations that have more than one batch bucket (Batch 1 + Batch 2, etc.).
 * Filterable by product, location, and date range.
 */
class ProductBatchController extends Controller
{
    protected $moduleUtil;

    public function __construct(ModuleUtil $moduleUtil)
    {
        $this->moduleUtil = $moduleUtil;
    }

    /**
     * Index / DataTable endpoint.
     */
    public function index(Request $request)
    {
        $business_id = $request->session()->get('user.business_id');

        if (!auth()->user()->can('product.view') && !auth()->user()->can('view_batch_details')) {
            abort(403, 'Unauthorized action.');
        }

        if ($request->ajax()) {
            $query = PurchaseLine::join('transactions as t', 't.id', '=', 'purchase_lines.transaction_id')
                ->join('products as p', 'p.id', '=', 'purchase_lines.product_id')
                ->join('variations as v', 'v.id', '=', 'purchase_lines.variation_id')
                ->leftJoin('product_variations as pv', 'pv.id', '=', 'v.product_variation_id')
                ->leftJoin('business_locations as bl', 'bl.id', '=', 't.location_id')
                ->where('t.business_id', $business_id)
                ->whereIn('t.type', ['purchase', 'opening_stock', 'purchase_transfer'])
                ->where(function ($q) {
                    $q->whereNotNull('purchase_lines.batch_number')
                        ->orWhereNotNull('purchase_lines.batch_id');
                });

            if (Schema::hasTable('product_batches')) {
                $query->leftJoin('product_batches as pb', 'pb.id', '=', 'purchase_lines.batch_id');
            }

            // Only products/variations that have 2+ batches at the same location (meaningful multi-batch SKUs).
            if (Schema::hasTable('product_batches')) {
                $multiBatchSub = DB::table('product_batches')
                    ->where('business_id', $business_id)
                    ->select('product_id', 'variation_id', 'location_id')
                    ->groupBy('product_id', 'variation_id', 'location_id')
                    ->havingRaw('COUNT(*) > 1');

                $query->joinSub($multiBatchSub, 'multibatch', function ($join) {
                    $join->on('multibatch.product_id', '=', 'purchase_lines.product_id')
                        ->on('multibatch.variation_id', '=', 'purchase_lines.variation_id')
                        ->on('multibatch.location_id', '=', 't.location_id');
                });
            } else {
                $multiBatchSub = DB::table('purchase_lines as pl_mb')
                    ->join('transactions as t_mb', 't_mb.id', '=', 'pl_mb.transaction_id')
                    ->where('t_mb.business_id', $business_id)
                    ->whereIn('t_mb.type', ['purchase', 'opening_stock', 'purchase_transfer'])
                    ->whereNotNull('pl_mb.batch_number')
                    ->where('pl_mb.batch_number', '!=', '')
                    ->select('pl_mb.product_id', 'pl_mb.variation_id', 't_mb.location_id')
                    ->groupBy('pl_mb.product_id', 'pl_mb.variation_id', 't_mb.location_id')
                    ->havingRaw('COUNT(DISTINCT pl_mb.batch_number) > 1');

                $query->joinSub($multiBatchSub, 'multibatch', function ($join) {
                    $join->on('multibatch.product_id', '=', 'purchase_lines.product_id')
                        ->on('multibatch.variation_id', '=', 'purchase_lines.variation_id')
                        ->on('multibatch.location_id', '=', 't.location_id');
                });
            }

            if ($product_id = $request->input('product_id')) {
                $query->where('purchase_lines.product_id', $product_id);
            }
            if ($location_id = $request->input('location_id')) {
                $query->where('t.location_id', $location_id);
            }
            if ($start = $request->input('start_date')) {
                $query->whereDate('t.transaction_date', '>=', $start);
            }
            if ($end = $request->input('end_date')) {
                $query->whereDate('t.transaction_date', '<=', $end);
            }

            $qty_used_sql = '(purchase_lines.quantity_sold + purchase_lines.quantity_adjusted + purchase_lines.quantity_returned + purchase_lines.mfg_quantity_used)';
            $remaining_sql = '(purchase_lines.quantity - '.$qty_used_sql.')';

            $batch_label_select = Schema::hasTable('product_batches')
                ? \DB::raw('COALESCE(pb.batch_label, purchase_lines.batch_number) as batch_number')
                : 'purchase_lines.batch_number';

            $query->select([
                'purchase_lines.id',
                $batch_label_select,
                'purchase_lines.purchase_price',
                'purchase_lines.purchase_price_inc_tax',
                'purchase_lines.batch_selling_price',
                'purchase_lines.batch_selling_price_inc_tax',
                'purchase_lines.quantity as qty_in',
                \DB::raw($qty_used_sql.' as qty_out'),
                \DB::raw($remaining_sql.' as qty_remaining'),
                't.transaction_date',
                't.ref_no as purchase_ref',
                'p.name as product_name',
                'p.sku as product_sku',
                'p.type as product_type',
                'pv.name as product_variation_name',
                'v.name as variation_name',
                'v.sub_sku',
                'bl.name as location_name',
            ]);

            return DataTables::of($query)
                ->editColumn('product_name', function ($row) {
                    $name = e($row->product_name).' <small class="text-muted">('.e($row->sub_sku).')</small>';
                    if ($row->product_type === 'variable') {
                        $name .= '<br><small><b>'.e($row->product_variation_name).'</b>: '.e($row->variation_name).'</small>';
                    }
                    return $name;
                })
                ->editColumn('purchase_price', function ($row) {
                    return '<span class="display_currency" data-currency_symbol="true">'.$row->purchase_price.'</span>';
                })
                ->editColumn('batch_selling_price_inc_tax', function ($row) {
                    $val = $row->batch_selling_price_inc_tax ?? 0;
                    return '<span class="display_currency" data-currency_symbol="true">'.$val.'</span>';
                })
                ->editColumn('qty_in', fn ($row) => number_format((float) $row->qty_in, 2))
                ->editColumn('qty_out', fn ($row) => number_format((float) $row->qty_out, 2))
                ->editColumn('qty_remaining', function ($row) {
                    $qty = (float) $row->qty_remaining;
                    $cls = $qty <= 0 ? 'label-danger' : ($qty < 5 ? 'label-warning' : 'label-success');
                    return '<span class="label '.$cls.'">'.number_format($qty, 2).'</span>';
                })
                ->editColumn('transaction_date', fn ($row) => \Carbon\Carbon::parse($row->transaction_date)->format(config('constants.date_format', 'd-m-Y')))
                ->rawColumns(['product_name', 'purchase_price', 'batch_selling_price_inc_tax', 'qty_remaining'])
                ->make(true);
        }

        $business_locations = BusinessLocation::forDropdown($business_id);
        $categories = Category::forDropdown($business_id, 'product');

        return view('product.batches', compact('business_locations', 'categories'));
    }
}
