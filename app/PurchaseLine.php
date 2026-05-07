<?php

namespace App;

use App\BusinessLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class PurchaseLine extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function transaction()
    {
        return $this->belongsTo(\App\Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(\App\Product::class, 'product_id');
    }

    public function variations()
    {
        return $this->belongsTo(\App\Variation::class, 'variation_id');
    }

    /**
     * Set the quantity.
     *
     * @param  string  $value
     * @return float $value
     */
    public function getQuantityAttribute($value)
    {
        return (float) $value;
    }

    /**
     * Get the unit associated with the purchase line.
     */
    public function sub_unit()
    {
        return $this->belongsTo(\App\Unit::class, 'sub_unit_id');
    }

    /**
     * Give the quantity remaining for a particular
     * purchase line.
     *
     * @return float $value
     */
    public function getQuantityRemainingAttribute()
    {
        return (float) ($this->quantity - $this->quantity_used);
    }

    /**
     * Give the sum of quantity sold, adjusted, returned.
     *
     * @return float $value
     */
    public function getQuantityUsedAttribute()
    {
        return (float) ($this->quantity_sold + $this->quantity_adjusted + $this->quantity_returned + $this->mfg_quantity_used);
    }

    public function line_tax()
    {
        return $this->belongsTo(\App\TaxRate::class, 'tax_id');
    }

    public function purchase_order_line()
    {
        return $this->belongsTo(\App\PurchaseLine::class, 'purchase_order_line_id');
    }

    public function purchase_requisition_line()
    {
        return $this->belongsTo(\App\PurchaseLine::class, 'purchase_requisition_line_id');
    }

    public function product_batch()
    {
        return $this->belongsTo(ProductBatch::class, 'batch_id');
    }

    /**
     * Compute the next batch label ("Batch N") for a given
     * product + variation + location combination.
     *
     * Batches are scoped per location because UltimatePOS stock
     * is tracked per (variation, location).
     */
    public static function nextBatchNumber($product_id, $variation_id, $location_id)
    {
        if (Schema::hasTable('product_batches')) {
            $business_id = BusinessLocation::where('id', $location_id)->value('business_id');
            if (! empty($business_id)) {
                return ProductBatch::nextBatchLabel(
                    (int) $business_id,
                    (int) $product_id,
                    (int) $variation_id,
                    (int) $location_id
                );
            }
        }

        $latest = self::join('transactions as t', 't.id', '=', 'purchase_lines.transaction_id')
            ->where('purchase_lines.product_id', $product_id)
            ->where('purchase_lines.variation_id', $variation_id)
            ->where('t.location_id', $location_id)
            ->whereNotNull('purchase_lines.batch_number')
            ->orderByDesc('purchase_lines.id')
            ->value('purchase_lines.batch_number');

        $next = 1;
        if (! empty($latest) && preg_match('/(\d+)\s*$/', $latest, $m)) {
            $next = ((int) $m[1]) + 1;
        } else {
            $count = self::join('transactions as t', 't.id', '=', 'purchase_lines.transaction_id')
                ->where('purchase_lines.product_id', $product_id)
                ->where('purchase_lines.variation_id', $variation_id)
                ->where('t.location_id', $location_id)
                ->count();
            $next = max(1, $count) + 1;
        }

        return 'Batch '.$next;
    }

    /**
     * Available batches for a given variation/location with remaining stock.
     * Used by the POS batch-selection popup.
     *
     * Returns id, batch_number, purchase_price, selling price (inc tax),
     * and remaining qty.
     */
    public static function availableBatches($variation_id, $location_id, $business_id = null)
    {
        $q = self::join('transactions as t', 't.id', '=', 'purchase_lines.transaction_id')
            ->where('purchase_lines.variation_id', $variation_id)
            ->where('t.location_id', $location_id)
            ->whereIn('t.type', ['purchase', 'opening_stock', 'purchase_transfer'])
            ->where('t.status', 'received')
            ->whereRaw('(purchase_lines.quantity - purchase_lines.quantity_sold - purchase_lines.quantity_adjusted - purchase_lines.quantity_returned - purchase_lines.mfg_quantity_used) > 0');

        if ($business_id) {
            $q->where('t.business_id', $business_id);
        }

        return $q->orderBy('purchase_lines.id')
            ->select([
                'purchase_lines.id',
                'purchase_lines.batch_number',
                'purchase_lines.lot_number',
                'purchase_lines.exp_date',
                'purchase_lines.purchase_price',
                'purchase_lines.purchase_price_inc_tax',
                'purchase_lines.batch_selling_price',
                'purchase_lines.batch_selling_price_inc_tax',
                'purchase_lines.batch_profit_margin',
                'purchase_lines.quantity',
                'purchase_lines.quantity_sold',
                'purchase_lines.quantity_adjusted',
                'purchase_lines.quantity_returned',
                'purchase_lines.mfg_quantity_used',
                't.transaction_date',
                't.ref_no',
            ])
            ->get();
    }
}
