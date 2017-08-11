<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToAuctionSaleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auction_sales', function($table) {
            $table->index('auction_series_id', 'auction_series_id');
            $table->index('auction_house_id', 'auction_house_id');
        });

        Schema::table('auction_sale_details', function($table) {
            $table->index('auction_sale_id', 'auction_sale_id');
        });

        Schema::table('auction_items', function($table) {
            $table->index('auction_sale_id', 'auction_sale_id');
        });

        Schema::table('auction_item_details', function($table) {
            $table->index('auction_item_id', 'auction_item_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auction_sales', function($table) {
            $table->dropIndex('auction_series_id');
            $table->dropIndex('auction_house_id');
        });

        Schema::table('auction_sale_details', function($table) {
            $table->dropIndex('auction_sale_id');
        });

        Schema::table('auction_items', function($table) {
            $table->dropIndex('auction_sale_id');
        });

        Schema::table('auction_item_details', function($table) {
            $table->dropIndex('auction_item_id');
        });
    }
}
