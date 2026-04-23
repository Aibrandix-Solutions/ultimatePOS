<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductBatch extends Model
{
    protected $guarded = ['id'];

    /**
     * Remaining stock across all received purchase lines tied to this batch.
     */
    public static function remainingStock(int $batchId): float
    {
        $qtySum = '(pl.quantity_sold + pl.quantity_adjusted + pl.quantity_returned + pl.mfg_quantity_used)';

        $row = DB::table('purchase_lines as pl')
            ->join('transactions as t', 't.id', '=', 'pl.transaction_id')
            ->where('pl.batch_id', $batchId)
            ->whereIn('t.type', ['purchase', 'opening_stock', 'purchase_transfer'])
            ->where('t.status', 'received')
            ->selectRaw('COALESCE(SUM(pl.quantity - '.$qtySum.'), 0) as r')
            ->first();

        return $row ? (float) $row->r : 0.0;
    }

    /**
     * Next label "Batch N" from existing product_batches rows for this SKU/location.
     */
    public static function nextBatchLabel(int $business_id, int $product_id, int $variation_id, int $location_id): string
    {
        $labels = self::where('business_id', $business_id)
            ->where('product_id', $product_id)
            ->where('variation_id', $variation_id)
            ->where('location_id', $location_id)
            ->pluck('batch_label');

        $max = 0;
        foreach ($labels as $label) {
            if (preg_match('/^Batch\s+(\d+)$/i', trim((string) $label), $m)) {
                $max = max($max, (int) $m[1]);
            }
        }

        return 'Batch '.($max + 1);
    }

    /**
     * Ensure Batch 1 exists for this SKU at the location (standard restock bucket).
     */
    public static function firstOrCreateBatchOne(int $business_id, int $product_id, int $variation_id, int $location_id): self
    {
        return self::firstOrCreate(
            [
                'business_id' => $business_id,
                'product_id' => $product_id,
                'variation_id' => $variation_id,
                'location_id' => $location_id,
                'batch_label' => 'Batch 1',
            ],
            []
        );
    }

    /**
     * Copy latest per-batch sell fields from a purchase line onto the master batch row.
     * Does not null out master prices when the line clears prices (standard restock).
     */
    public static function syncSellPricesFromPurchaseLine(PurchaseLine $pl): void
    {
        if (empty($pl->batch_id)) {
            return;
        }

        $pb = self::find($pl->batch_id);
        if (empty($pb)) {
            return;
        }

        $dirty = false;
        if ($pl->batch_selling_price_inc_tax !== null) {
            $pb->sell_price_inc_tax = $pl->batch_selling_price_inc_tax;
            $dirty = true;
        }
        if ($pl->batch_selling_price !== null) {
            $pb->sell_price_exc_tax = $pl->batch_selling_price;
            $dirty = true;
        }
        if ($pl->batch_profit_margin !== null) {
            $pb->profit_margin = $pl->batch_profit_margin;
            $dirty = true;
        }

        if ($dirty) {
            $pb->save();
        }
    }
}
