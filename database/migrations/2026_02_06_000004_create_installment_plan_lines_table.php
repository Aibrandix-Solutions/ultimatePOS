<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('installment_plan_lines', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('installment_plan_id');
            $table->unsignedInteger('sequence');

            $table->date('due_date');
            $table->decimal('amount', 22, 4);
            $table->decimal('paid_amount', 22, 4)->default(0);
            $table->string('status', 20)->default('pending'); // pending|paid
            $table->dateTime('paid_on')->nullable();

            $table->timestamps();

            $table->index(['installment_plan_id', 'sequence']);
            $table->index(['installment_plan_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installment_plan_lines');
    }
};
