<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction_series', function (Blueprint $table) {
//            id	slug	total_lots	start_date	end_date	auction_house_id
            $table->increments('id');
            $table->string('slug');
            $table->integer('total_lots');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('auction_house_id');
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
        Schema::dropIfExists('auction_series');
    }
}
