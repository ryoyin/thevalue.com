<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionSaleTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction_sale_times', function (Blueprint $table) {
//            id	type	lots	start_datetime	end_datetime	auction_sale_id
            $table->increments('id');
            $table->string('type');
            $table->integer('lots');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('auction_sale_id');
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
        Schema::dropIfExists('auction_sale_times');
    }
}
