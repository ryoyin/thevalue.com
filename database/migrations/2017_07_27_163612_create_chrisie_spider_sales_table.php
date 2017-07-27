<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChrisieSpiderSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('christie_spider_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('int_sale_id');
            $table->boolean('download_images');
            $table->boolean('push_s3');
            $table->integer('christie_spider_id');
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
        Schema::dropIfExists('christie_spider_sales');
    }
}
