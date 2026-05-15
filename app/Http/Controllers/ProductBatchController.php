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
 * Shows one row per product/variation/location that has multi-batch buckets.
 * Clicking a product opens a modal with individual batch details.
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
                ->whereIn('t.type', ['purchase', 'opening_stock', 'purchase_transfer']);

            // Filter for multi-batch products (meaningful multi-batch SKUs).
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

            $qty_used_sql = '(purchase_lines.quantity_sold + purchase_lines.quantity_adjusted + purchase_lines.quantity_returned + purchase_lines.mfg_quantity_used)';
            $remaining_sql = '(purchase_lines.quantity - ' . $qty_used_sql . ')';

            $query->select([
                'purchase_lines.product_id',
                'purchase_lines.variation_id',
                't.location_id',
                'p.name as product_name',
                'p.sku as product_sku',
                'p.type as product_type',
                'pv.name as product_variation_name',
                'v.name as variation_name',
                'v.sub_sku',
                'bl.name as location_name',
                DB::raw('SUM(' . $remaining_sql . ') as total_qty_remaining'),
            ])
                ->groupBy('purchase_lines.product_id', 'purchase_lines.variation_id', 't.location_id');

            return DataTables::of($query)
                ->editColumn('product_name', function ($row) {
                    $name = e($row->product_name) . ' <small class="text-muted">(' . e($row->sub_sku) . ')</small>';
                    if ($row->product_type === 'variable') {
                        $name .= '<br><small><b>' . e($row->product_variation_name) . '</b>: ' . e($row->variation_name) . '</small>';
                    }
                    return $name;
                })
                ->editColumn('total_qty_remaining', function ($row) {
                    $qty = (float) $row->total_qty_remaining;
                    $cls = $qty <= 0 ? 'label-danger' : ($qty < 5 ? 'label-warning' : 'label-success');
                    return '<span class="label ' . $cls . '">' . number_format($qty, 2) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<button type="button" class="btn btn-info btn-xs view_batch_details" data-product_id="' . $row->product_id . '" data-variation_id="' . $row->variation_id . '" data-location_id="' . $row->location_id . '"><i class="fa fa-eye"></i> ' . __('messages.view') . '</button>';
                })
                ->rawColumns(['product_name', 'total_qty_remaining', 'action'])
                ->make(true);
        }

        $business_locations = BusinessLocation::forDropdown($business_id);
        $categories = Category::forDropdown($business_id, 'product');

        return view('product.batches', compact('business_locations', 'categories'));
    }

    /**
     * Get individual batch details for a product variation at a location.
     */
    public function getBatchDetails(Request $request)
    {
        $business_id = $request->session()->get('user.business_id');
        $product_id = $request->input('product_id');
        $variation_id = $request->input('variation_id');
        $location_id = $request->input('location_id');

        if (!auth()->user()->can('product.view') && !auth()->user()->can('view_batch_details')) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure legacy stock is migrated to Batch 1 for accurate price tracking in reports.
        if (Schema::hasTable('product_batches')) {
            $variation = \App\Variation::find($variation_id);
            if ($variation) {
                \App\ProductBatch::firstOrCreateBatchOne(
                    (int) $business_id,
                    (int) $product_id,
                    (int) $variation_id,
                    (int) $location_id,
                    null,
                    null
                );
            }
        }

        $query = PurchaseLine::join('transactions as t', 't.id', '=', 'purchase_lines.transaction_id')
            ->leftJoin('business_locations as bl', 'bl.id', '=', 't.location_id')
            ->join('variations as v', 'v.id', '=', 'purchase_lines.variation_id')
            ->where('t.business_id', $business_id)
            ->where('purchase_lines.product_id', $product_id)
            ->where('purchase_lines.variation_id', $variation_id)
            ->where('t.location_id', $location_id)
            ->whereIn('t.type', ['purchase', 'opening_stock', 'purchase_transfer']);

        if (Schema::hasTable('product_batches')) {
            $query->leftJoin('product_batches as pb', 'pb.id', '=', 'purchase_lines.batch_id');
        }

        $qty_used_sql = '(purchase_lines.quantity_sold + purchase_lines.quantity_adjusted + purchase_lines.quantity_returned + purchase_lines.mfg_quantity_used)';
        $remaining_sql = '(purchase_lines.quantity - ' . $qty_used_sql . ')';

        $batch_label_select = Schema::hasTable('product_batches')
            ? \DB::raw('COALESCE(pb.batch_label, purchase_lines.batch_number) as batch_number')
            : 'purchase_lines.batch_number';

        $selling_price_sql = 'COALESCE(NULLIF(purchase_lines.batch_selling_price_inc_tax, 0), v.sell_price_inc_tax)';
        if (Schema::hasTable('product_batches')) {
            $selling_price_sql = 'COALESCE(NULLIF(purchase_lines.batch_selling_price_inc_tax, 0), pb.sell_price_inc_tax, v.sell_price_inc_tax)';
        }

        $batches = $query->select([
            'purchase_lines.id',
            $batch_label_select,
            'purchase_lines.purchase_price',
            'purchase_lines.purchase_price_inc_tax',
            \DB::raw($selling_price_sql . ' as batch_selling_price_inc_tax'),
            'purchase_lines.quantity as qty_in',
            \DB::raw($qty_used_sql . ' as qty_out'),
            \DB::raw($remaining_sql . ' as qty_remaining'),
            't.transaction_date',
            't.ref_no as purchase_ref',
        ])
            ->orderBy('t.transaction_date', 'desc')
            ->get();

        $product = Product::find($product_id);

        return view('product.partials.batch_details_modal', compact('batches', 'product'));
    }

    public function edit($id)
    {
        $business_id = request()->session()->get('user.business_id');
        if (!auth()->user()->can('product.update')) {
            abort(403, 'Unauthorized action.');
        }

        $purchase_line = PurchaseLine::join('transactions as t', 't.id', '=', 'purchase_lines.transaction_id')
            ->where('t.business_id', $business_id)
            ->where('purchase_lines.id', $id)
            ->select('purchase_lines.*')
            ->firstOrFail();

        $product = Product::findOrFail($purchase_line->product_id);
        
        $tax_rate = 0;
        $tax_calculation_type = 'percentage';
        if (!empty($product->tax)) {
            $tax_obj = \App\TaxRate::find($product->tax);
            if ($tax_obj) {
                $tax_rate = $tax_obj->amount;
                $tax_calculation_type = $tax_obj->calculation_type ?? 'percentage';
            }
        }

        return view('product.partials.edit_batch_modal')
            ->with(compact('purchase_line', 'product', 'tax_rate', 'tax_calculation_type'));
    }

    public function update(Request $request, $id)
    {
        $business_id = $request->session()->get('user.business_id');
        if (!auth()->user()->can('product.update')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $purchase_line = PurchaseLine::join('transactions as t', 't.id', '=', 'purchase_lines.transaction_id')
                ->where('t.business_id', $business_id)
                ->where('purchase_lines.id', $id)
                ->select('purchase_lines.*')
                ->firstOrFail();

            $batch_selling_price_inc_tax = $request->input('batch_selling_price_inc_tax');
            $batch_profit_margin = $request->input('batch_profit_margin');
            $purchase_price = $request->input('purchase_price');
            $purchase_price_inc_tax = $request->input('purchase_price_inc_tax');

            $purchase_line->batch_selling_price_inc_tax = $this->moduleUtil->num_uf($batch_selling_price_inc_tax);
            $purchase_line->batch_profit_margin = $this->moduleUtil->num_uf($batch_profit_margin);
            $purchase_line->purchase_price = $this->moduleUtil->num_uf($purchase_price);
            $purchase_line->purchase_price_inc_tax = $this->moduleUtil->num_uf($purchase_price_inc_tax);
            
            $product = Product::find($purchase_line->product_id);

            // Calculate exclusive price if tax exists
            $tax_rate = 0;
            $tax_calculation_type = 'percentage';
            if (!empty($product->tax)) {
                $tax_obj = \App\TaxRate::find($product->tax);
                if ($tax_obj) {
                    $tax_rate = $tax_obj->amount;
                    $tax_calculation_type = $tax_obj->calculation_type ?? 'percentage';
                }
            }
            
            if ($tax_calculation_type == 'fixed') {
                $purchase_line->batch_selling_price = max(0, $purchase_line->batch_selling_price_inc_tax - $tax_rate);
            } else {
                // Use ProductUtil's helper if possible, or manual math
                $purchase_line->batch_selling_price = ($purchase_line->batch_selling_price_inc_tax / (1 + ($tax_rate / 100)));
            }
            
            $purchase_line->save();

            // Also update the ProductBatch record if linked
            if (!empty($purchase_line->batch_id)) {
                $pb = \App\ProductBatch::find($purchase_line->batch_id);
                if ($pb) {
                    $pb->sell_price_inc_tax = $purchase_line->batch_selling_price_inc_tax;
                    $pb->profit_margin = $purchase_line->batch_profit_margin;
                    $pb->save();
                }
            }

            $output = ['success' => 1, 'msg' => __('lang_v1.updated_success')];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            $output = ['success' => 0, 'msg' => __('messages.something_went_wrong')];
        }

        return $output;
    }
}
