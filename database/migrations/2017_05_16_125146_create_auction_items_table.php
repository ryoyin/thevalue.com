<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction_items', function (Blueprint $table) {
            // id	slug	number	image_path	image_large_path	image_medium_path	image_small_path	estimate	price	auction_sale_id
            $table->increments('id');
            $table->string('slug');
            $table->integer('number');
            $table->string('image_path');
            $table->string('image_large_path');
            $table->string('image_medium_path');
            $table->string('image_small_path');
            $table->string('currency_code');
            $table->string('estimate_value_initial');
            $table->string('estimate_value_end');
            $table->string('sold_value');
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
        Schema::dropIfExists('auction_items');
    }
}
