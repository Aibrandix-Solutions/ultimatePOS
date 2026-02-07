<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranty_claims', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('business_id')->index();
            $table->unsignedInteger('transaction_id')->index();
            $table->unsignedInteger('sell_line_id')->index();

            $table->unsignedInteger('contact_id')->index(); // customer
            $table->unsignedInteger('supplier_id')->nullable()->index();

            $table->string('status', 64)->index();
            $table->text('problem')->nullable();
            $table->text('notes')->nullable();

            $table->dateTime('received_at')->nullable();
            $table->dateTime('sent_to_supplier_at')->nullable();
            $table->dateTime('received_from_supplier_at')->nullable();
            $table->dateTime('returned_to_customer_at')->nullable();
            $table->dateTime('closed_at')->nullable()->index();

            $table->unsignedInteger('created_by')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranty_claims');
    }
};
