<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Stable batch buckets (product_batches) + batch_id on purchase/sell lines.
     */
    public function up(): void
    {
        if (! Schema::hasTable('product_batches')) {
            Schema::create('product_batches', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('business_id')->index();
                $table->unsignedInteger('product_id')->index();
                $table->unsignedInteger('variation_id')->index();
                $table->unsignedInteger('location_id')->index();
                $table->string('batch_label', 191);
                $table->decimal('sell_price_exc_tax', 22, 4)->nullable();
                $table->decimal('sell_price_inc_tax', 22, 4)->nullable();
                $table->decimal('profit_margin', 5, 2)->nullable();
                $table->timestamps();

                $table->unique(
                    ['business_id', 'product_id', 'variation_id', 'location_id', 'batch_label'],
                    'product_batches_unique_label'
                );
            });
        }

        Schema::table('purchase_lines', function (Blueprint $table) {
            if (! Schema::hasColumn('purchase_lines', 'batch_id')) {
                $table->unsignedInteger('batch_id')->nullable()->after('batch_profit_margin');
            }
        });

        Schema::table('transaction_sell_lines', function (Blueprint $table) {
            if (! Schema::hasColumn('transaction_sell_lines', 'batch_id')) {
                $table->unsignedInteger('batch_id')->nullable()->after('lot_no_line_id');
            }
        });

        $this->backfillProductBatchesFromPurchaseLines();
        $this->backfillSellLineBatchIds();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_sell_lines', function (Blueprint $table) {
            if (Schema::hasColumn('transaction_sell_lines', 'batch_id')) {
                $table->dropColumn('batch_id');
            }
        });

        Schema::table('purchase_lines', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_lines', 'batch_id')) {
                $table->dropColumn('batch_id');
            }
        });

        Schema::dropIfExists('product_batches');
    }

    private function backfillProductBatchesFromPurchaseLines(): void
    {
        if (! Schema::hasTable('purchase_lines') || ! Schema::hasTable('transactions')) {
            return;
        }

        $hasBatchNumber = Schema::hasColumn('purchase_lines', 'batch_number');
        if (! $hasBatchNumber) {
            return;
        }

        $groups = DB::table('purchase_lines as pl')
            ->join('transactions as t', 't.id', '=', 'pl.transaction_id')
            ->whereNotNull('pl.batch_number')
            ->where('pl.batch_number', '!=', '')
            ->selectRaw('DISTINCT t.business_id, t.location_id, pl.product_id, pl.variation_id, pl.batch_number as batch_label')
            ->get();

        foreach ($groups as $g) {
            $pbId = DB::table('product_batches')
                ->where('business_id', $g->business_id)
                ->where('location_id', $g->location_id)
                ->where('product_id', $g->product_id)
                ->where('variation_id', $g->variation_id)
                ->where('batch_label', $g->batch_label)
                ->value('id');

            if (empty($pbId)) {
                $sample = DB::table('purchase_lines as pl')
                    ->join('transactions as t', 't.id', '=', 'pl.transaction_id')
                    ->where('t.business_id', $g->business_id)
                    ->where('t.location_id', $g->location_id)
                    ->where('pl.product_id', $g->product_id)
                    ->where('pl.variation_id', $g->variation_id)
                    ->where('pl.batch_number', $g->batch_label)
                    ->orderByDesc('pl.id')
                    ->first(['batch_selling_price', 'batch_selling_price_inc_tax', 'batch_profit_margin']);

                $pbId = DB::table('product_batches')->insertGetId([
                    'business_id' => $g->business_id,
                    'product_id' => $g->product_id,
                    'variation_id' => $g->variation_id,
                    'location_id' => $g->location_id,
                    'batch_label' => $g->batch_label,
                    'sell_price_exc_tax' => $sample->batch_selling_price ?? null,
                    'sell_price_inc_tax' => $sample->batch_selling_price_inc_tax ?? null,
                    'profit_margin' => $sample->batch_profit_margin ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('purchase_lines as pl')
                ->join('transactions as t', 't.id', '=', 'pl.transaction_id')
                ->where('t.business_id', $g->business_id)
                ->where('t.location_id', $g->location_id)
                ->where('pl.product_id', $g->product_id)
                ->where('pl.variation_id', $g->variation_id)
                ->where('pl.batch_number', $g->batch_label)
                ->whereNull('pl.batch_id')
                ->update(['pl.batch_id' => $pbId]);
        }
    }

    private function backfillSellLineBatchIds(): void
    {
        if (! Schema::hasColumn('transaction_sell_lines', 'batch_id')
            || ! Schema::hasColumn('purchase_lines', 'batch_id')) {
            return;
        }

        DB::statement('
            UPDATE transaction_sell_lines tsl
            INNER JOIN purchase_lines pl ON pl.id = tsl.lot_no_line_id
            SET tsl.batch_id = pl.batch_id
            WHERE tsl.lot_no_line_id IS NOT NULL
              AND pl.batch_id IS NOT NULL
              AND (tsl.batch_id IS NULL OR tsl.batch_id = 0)
        ');
    }
};
