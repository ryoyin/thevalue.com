<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction_sales', function (Blueprint $table) {
//            id	slug	number	total_lots	start_date	end_date	auction_series_id
            $table->increments('id');
            $table->string('slug');
            $table->string('image_path')->nullable();
            $table->string('number');
            $table->integer('total_lots');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
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
        Schema::dropIfExists('auction_sales');
    }
}
