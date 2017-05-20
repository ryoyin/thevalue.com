<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChristieSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('christie_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('int_sale_id');
            $table->integer('sale_number');
            $table->integer('get_json');
            $table->integer('to_db');
            $table->integer('get_image');
            $table->integer('status');
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
        Schema::dropIfExists('christie_sales');
    }
}
