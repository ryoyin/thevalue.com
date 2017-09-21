<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePastAuctionSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('past_auction_sales', function (Blueprint $table) {
//            id	slug	number	total_lots	start_date	end_date	auction_series_id
            $table->increments('id');
            $table->string('slug');
            $table->string('image_path');
            $table->string('image_fit_path');
            $table->string('image_small_path');
            $table->string('image_medium_path');
            $table->string('image_large_path');
            $table->string('number');
            $table->integer('total_lots');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('auction_house_id');
            $table->integer('country_id');
            $table->integer('city_id');
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
        Schema::dropIfExists('past_auction_sales');
    }
}
