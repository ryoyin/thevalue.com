<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePastAuctionSaleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('past_auction_sale_details', function (Blueprint $table) {
            //id	type	title	locations	lang	auction_sale_id
            $table->increments('id');
            $table->string('title');
            $table->string('location');
            $table->string('lang');
            $table->unsignedInteger('past_auction_sale_id');
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
        Schema::dropIfExists('past_auction_sale_details');
    }
}
