<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePastAuctionItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('past_auction_item_details', function (Blueprint $table) {
//            id	description	maker	misc	lang	auction_item_id
            $table->increments('id');
            $table->string('title');
            $table->longText('description');
            $table->string('maker');
            $table->string('misc');
            $table->string('lang');
            $table->unsignedInteger('past_auction_item_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('past_auction_item_details');
    }
}
