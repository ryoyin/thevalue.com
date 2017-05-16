<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionSeriesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction_series_details', function (Blueprint $table) {
//            id	name	country	location	lang	auction_series_id
            $table->increments('id');
            $table->string('name');
            $table->string('country');
            $table->string('location');
            $table->string('lang');
            $table->integer('auction_series_id');
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
        Schema::dropIfExists('auction_series_details');
    }
}
