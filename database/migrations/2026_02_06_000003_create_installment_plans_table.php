<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('installment_plans', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('business_id');
            $table->unsignedInteger('transaction_id');
            $table->unsignedInteger('contact_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();

            $table->decimal('down_payment', 22, 4)->default(0);
            $table->unsignedInteger('installment_count');
            $table->unsignedInteger('interval');
            $table->string('interval_type', 20); // days|weeks|months
            $table->date('first_due_date');

            $table->string('status', 20)->default('active'); // active|closed
            $table->dateTime('closed_at')->nullable();

            $table->timestamps();

            $table->unique('transaction_id');
            $table->index(['business_id', 'contact_id']);
            $table->index(['business_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installment_plans');
    }
};
