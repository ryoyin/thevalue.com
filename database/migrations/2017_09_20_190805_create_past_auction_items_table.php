<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePastAuctionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('past_auction_items', function (Blueprint $table) {
            // id	slug	number	image_path	image_large_path	image_medium_path	image_small_path	estimate	price	auction_sale_id
            $table->increments('id');
            $table->string('slug');
            $table->string('number');
            $table->string('dimension')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_fit_path')->nullable();
            $table->string('image_large_path')->nullable();
            $table->string('image_medium_path')->nullable();
            $table->string('image_small_path')->nullable();
            $table->string('estimate_value_initial')->nullable();
            $table->string('estimate_value_end')->nullable();
            $table->string('sold_value')->nullable();
            $table->string('sorting')->nullable();
            $table->integer('status')->default(0);
            $table->unsignedInteger('auction_sale_id')->unsigned();
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
        Schema::dropIfExists('past_auction_items');
    }
}
