<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionHouseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction_house_details', function (Blueprint $table) {
            // id	name	address	lang	auction_house_id
            $table->increments('id');
            $table->string('name');
            $table->string('address');
            $table->string('lang');
            $table->string('office_hour');
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
        Schema::dropIfExists('auction_house_details');
    }
}
