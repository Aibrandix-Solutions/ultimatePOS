<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('transactions', function (Blueprint $table) {
            // Field to mark if this sale is an exchange transaction
            if (!Schema::hasColumn('transactions', 'is_exchange')) {
                $table->boolean('is_exchange')->default(0)->after('is_suspend');
            }
            
            // Field to store the return transaction ID for exchange sales
            if (!Schema::hasColumn('transactions', 'exchange_return_id')) {
                $table->integer('exchange_return_id')->unsigned()->nullable()->after('is_exchange');
            }
            
            // Field to store the original parent sale ID from the return
            if (!Schema::hasColumn('transactions', 'exchange_parent_sale_id')) {
                $table->integer('exchange_parent_sale_id')->unsigned()->nullable()->after('exchange_return_id');
            }
            
            // Field to store the exchange sale ID in the return transaction
            if (!Schema::hasColumn('transactions', 'exchange_sale_id')) {
                $table->integer('exchange_sale_id')->unsigned()->nullable()->after('return_parent_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'is_exchange')) {
                $table->dropColumn('is_exchange');
            }
            
            if (Schema::hasColumn('transactions', 'exchange_return_id')) {
                $table->dropColumn('exchange_return_id');
            }
            
            if (Schema::hasColumn('transactions', 'exchange_parent_sale_id')) {
                $table->dropColumn('exchange_parent_sale_id');
            }
            
            if (Schema::hasColumn('transactions', 'exchange_sale_id')) {
                $table->dropColumn('exchange_sale_id');
            }
        });
    }
};
