<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDimensionToAuctionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auction_items', function (Blueprint $table) {
            $table->string('dimension')->after('slug')->nullable();
        });

        Schema::table('auction_item_details', function (Blueprint $table) {
            $table->longText('provenance')->after('misc')->nullable();
            $table->longText('post_lot_text')->after('provenance')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auction_items', function (Blueprint $table) {
            $table->dropColumn('dimension');
        });

        Schema::table('auction_item_details', function (Blueprint $table) {
            $table->dropColumn('provenance');
            $table->dropColumn('post_lot_text');
        });
    }
}
