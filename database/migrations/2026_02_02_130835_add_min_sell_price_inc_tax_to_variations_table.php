<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table('variations', function (Blueprint $table) {
            $table->decimal('min_sell_price_inc_tax', 22, 4)
                ->nullable()
                ->after('sell_price_inc_tax');
        });

        //Backfill existing variations so MSP behavior matches previous default.
        DB::table('variations')
            ->whereNull('min_sell_price_inc_tax')
            ->update([
                'min_sell_price_inc_tax' => DB::raw('sell_price_inc_tax'),
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('variations', function (Blueprint $table) {
            $table->dropColumn('min_sell_price_inc_tax');
        });
    }
};
