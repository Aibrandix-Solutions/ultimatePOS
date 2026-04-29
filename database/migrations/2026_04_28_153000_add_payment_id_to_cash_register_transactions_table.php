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
        Schema::table('cash_register_transactions', function (Blueprint $table) {
            $table->unsignedInteger('payment_id')->nullable()->after('transaction_id');
            $table->index('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_register_transactions', function (Blueprint $table) {
            $table->dropIndex(['payment_id']);
            $table->dropColumn('payment_id');
        });
    }
};
