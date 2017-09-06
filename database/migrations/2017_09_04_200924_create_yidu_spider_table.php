<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYiduSpiderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yidu_spider', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('int_sale_id');
            $table->integer('retrieve_server')->nullable();
            $table->boolean('status')->default(0); // 0 - pending 1 - downloading 2 - downloaded 3 - empty sale
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
        Schema::dropIfExists('yidu_spider');
    }
}
