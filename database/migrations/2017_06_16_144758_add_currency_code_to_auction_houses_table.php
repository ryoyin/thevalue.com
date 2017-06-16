<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrencyCodeToAuctionHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auction_houses', function($table) {
            $table->string('currency_code', 5)->after('email');
            $table->string('dollar_sign', 5)->after('currency_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auction_houses', function($table) {
            $table->dropColumn('currency_code');
            $table->dropColumn('dollar_sign');
        });
    }
}
