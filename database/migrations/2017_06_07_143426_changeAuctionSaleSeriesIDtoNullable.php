<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAuctionSaleSeriesIDtoNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auction_sales', function (Blueprint $table) {
            $table->integer('auction_series_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auction_sales', function (Blueprint $table) {
            $table->integer('auction_series_id')->nullable(false)->change();
        });
    }
}
