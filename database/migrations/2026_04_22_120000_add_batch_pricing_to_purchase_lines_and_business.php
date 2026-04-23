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
     * @return void
     */
    public function up()
    {
        Schema::table('business', function (Blueprint $table) {
            if (! Schema::hasColumn('business', 'enable_batch_pricing')) {
                $table->boolean('enable_batch_pricing')->default(false)->after('enable_lot_number');
            }
        });

        Schema::table('purchase_lines', function (Blueprint $table) {
            if (! Schema::hasColumn('purchase_lines', 'batch_number')) {
                $table->string('batch_number', 191)->nullable()->after('lot_number')
                    ->comment('Auto-assigned human batch label, unique per product+variation+location');
            }
            if (! Schema::hasColumn('purchase_lines', 'batch_selling_price')) {
                $table->decimal('batch_selling_price', 22, 4)->nullable()->after('batch_number')
                    ->comment('Per-batch selling price excluding tax');
            }
            if (! Schema::hasColumn('purchase_lines', 'batch_selling_price_inc_tax')) {
                $table->decimal('batch_selling_price_inc_tax', 22, 4)->nullable()->after('batch_selling_price')
                    ->comment('Per-batch selling price including tax');
            }
            if (! Schema::hasColumn('purchase_lines', 'batch_profit_margin')) {
                $table->decimal('batch_profit_margin', 5, 2)->nullable()->after('batch_selling_price_inc_tax')
                    ->comment('Profit margin percentage used to compute batch selling price');
            }
        });

        if (Schema::hasColumn('purchase_lines', 'product_id') && Schema::hasColumn('purchase_lines', 'variation_id')) {
            try {
                Schema::table('purchase_lines', function (Blueprint $table) {
                    $table->index(['product_id', 'variation_id'], 'purchase_lines_batch_lookup_idx');
                });
            } catch (\Throwable $e) {
                // Index already exists; ignore.
            }
        }

        $this->backfillBatchNumbers();
    }

    /**
     * Backfill a sequential batch number for every existing purchase line,
     * grouped by (product_id, variation_id, location_id).
     * Also copies the current variation selling price as the batch selling price
     * so legacy rows have usable prices.
     */
    private function backfillBatchNumbers(): void
    {
        if (! Schema::hasTable('transactions') || ! Schema::hasTable('variations')) {
            return;
        }

        $rows = DB::table('purchase_lines as pl')
            ->join('transactions as t', 't.id', '=', 'pl.transaction_id')
            ->leftJoin('variations as v', 'v.id', '=', 'pl.variation_id')
            ->whereNull('pl.batch_number')
            ->orderBy('pl.product_id')
            ->orderBy('pl.variation_id')
            ->orderBy('t.location_id')
            ->orderBy('pl.id')
            ->select([
                'pl.id',
                'pl.product_id',
                'pl.variation_id',
                't.location_id',
                'v.default_sell_price',
                'v.sell_price_inc_tax',
                'v.profit_percent',
            ])
            ->get();

        $counters = [];
        foreach ($rows as $row) {
            $key = $row->product_id.'|'.$row->variation_id.'|'.$row->location_id;
            $counters[$key] = ($counters[$key] ?? 0) + 1;

            DB::table('purchase_lines')
                ->where('id', $row->id)
                ->update([
                    'batch_number' => 'Batch '.$counters[$key],
                    'batch_selling_price' => $row->default_sell_price,
                    'batch_selling_price_inc_tax' => $row->sell_price_inc_tax,
                    'batch_profit_margin' => $row->profit_percent,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_lines', function (Blueprint $table) {
            try {
                $table->dropIndex('purchase_lines_batch_lookup_idx');
            } catch (\Throwable $e) {
                //
            }
            foreach (['batch_number', 'batch_selling_price', 'batch_selling_price_inc_tax', 'batch_profit_margin'] as $col) {
                if (Schema::hasColumn('purchase_lines', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('business', function (Blueprint $table) {
            if (Schema::hasColumn('business', 'enable_batch_pricing')) {
                $table->dropColumn('enable_batch_pricing');
            }
        });
    }
};
