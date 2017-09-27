<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRefNumberToPastAuctionSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('past_auction_sales', function($table) {
            $table->string('ref_number')->after('number');
            $table->renameColumn('city_id', 'auction_location_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('past_auction_sales', function($table) {
            $table->dropColumn('ref_number');
            $table->renameColumn('auction_location_id', 'city_id');
        });
    }
}
