<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranty_claim_status_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('warranty_claim_id')->index();
            $table->string('to_status', 64)->index();
            $table->text('note')->nullable();
            $table->unsignedInteger('created_by')->index();
            $table->timestamps();

            $table->foreign('warranty_claim_id')
                ->references('id')
                ->on('warranty_claims')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranty_claim_status_logs');
    }
};
