<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add purchase cost columns to product_batches so that when a batch is
     * refilled the purchase row pre-fills with that batch's own cost.
     */
    public function up(): void
    {
        if (! Schema::hasTable('product_batches')) {
            return;
        }

        Schema::table('product_batches', function (Blueprint $table) {
            if (! Schema::hasColumn('product_batches', 'purchase_price_exc_tax')) {
                $table->decimal('purchase_price_exc_tax', 22, 4)->nullable()->after('profit_margin');
            }
            if (! Schema::hasColumn('product_batches', 'purchase_price_inc_tax')) {
                $table->decimal('purchase_price_inc_tax', 22, 4)->nullable()->after('purchase_price_exc_tax');
            }
        });

        // Back-fill from the most recent received purchase line for each batch.
        if (Schema::hasTable('purchase_lines') && Schema::hasTable('transactions')) {
            DB::statement("
                UPDATE product_batches pb
                INNER JOIN (
                    SELECT
                        pl.batch_id,
                        pl.purchase_price        AS pp_exc,
                        pl.purchase_price_inc_tax AS pp_inc
                    FROM purchase_lines pl
                    INNER JOIN transactions t ON t.id = pl.transaction_id
                    WHERE t.type IN ('purchase','opening_stock','purchase_transfer')
                      AND t.status = 'received'
                      AND pl.batch_id IS NOT NULL
                    ORDER BY t.transaction_date DESC, pl.id DESC
                ) latest ON latest.batch_id = pb.id
                SET
                    pb.purchase_price_exc_tax = latest.pp_exc,
                    pb.purchase_price_inc_tax = latest.pp_inc
                WHERE pb.purchase_price_exc_tax IS NULL
            ");
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('product_batches')) {
            return;
        }

        Schema::table('product_batches', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('product_batches', 'purchase_price_exc_tax')) {
                $cols[] = 'purchase_price_exc_tax';
            }
            if (Schema::hasColumn('product_batches', 'purchase_price_inc_tax')) {
                $cols[] = 'purchase_price_inc_tax';
            }
            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
