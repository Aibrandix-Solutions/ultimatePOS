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
        Schema::table('transaction_payments', function (Blueprint $table) {
            $table->dateTime('cheque_issue_date')->nullable()->after('cheque_number');
            $table->dateTime('cheque_passing_date')->nullable()->after('cheque_issue_date');
            $table->string('cheque_bank_name')->nullable()->after('cheque_passing_date');
            $table->string('cheque_status', 20)->nullable()->after('cheque_bank_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_payments', function (Blueprint $table) {
            $table->dropColumn([
                'cheque_issue_date',
                'cheque_passing_date',
                'cheque_bank_name',
                'cheque_status',
            ]);
        });
    }
};
