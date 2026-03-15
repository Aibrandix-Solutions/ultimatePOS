<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExchangeFieldsToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('is_exchange')->default(0)->after('is_suspend');
            $table->integer('exchange_return_id')->unsigned()->nullable()->after('is_exchange');
            $table->integer('exchange_parent_sale_id')->unsigned()->nullable()->after('exchange_return_id');
            $table->integer('exchange_sale_id')->unsigned()->nullable()->after('exchange_parent_sale_id');
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
            $table->dropColumn(['is_exchange', 'exchange_return_id', 'exchange_parent_sale_id', 'exchange_sale_id']);
        });
    }
}
