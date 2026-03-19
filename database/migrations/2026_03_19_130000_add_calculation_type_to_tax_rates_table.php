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
        if (! Schema::hasColumn('tax_rates', 'calculation_type')) {
            Schema::table('tax_rates', function (Blueprint $table) {
                $table->enum('calculation_type', ['percentage', 'fixed'])
                    ->default('percentage')
                    ->after('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('tax_rates', 'calculation_type')) {
            Schema::table('tax_rates', function (Blueprint $table) {
                $table->dropColumn('calculation_type');
            });
        }
    }
};
