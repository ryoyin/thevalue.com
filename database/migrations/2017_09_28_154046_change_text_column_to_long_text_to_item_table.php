<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTextColumnToLongTextToItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('past_auction_items', function (Blueprint $table) {
            $table->longText('dimension')->change();
        });

        Schema::table('past_auction_item_details', function (Blueprint $table) {
            $table->longText('title')->change();
            $table->longText('description')->change();
            $table->longText('maker')->change();
            $table->longText('misc')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
