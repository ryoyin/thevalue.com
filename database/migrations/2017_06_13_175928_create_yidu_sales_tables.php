<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYiduSalesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yidu_sales', function (Blueprint $table) {
            // id	int_sale_id	html	json	image	import	status
            $table->increments('id');
            $table->string('int_sale_id');
            $table->boolean('html')->nullable();
            $table->boolean('json')->nullable();
            $table->boolean('image')->nullable();
            $table->boolean('import')->nullable();
            $table->tinyInteger('status')->default(0);

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
        Schema::dropIfExists('yidu_sales');
    }
}
