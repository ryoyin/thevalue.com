<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRetrieveServerToChristieSpiderSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('christie_spider_sales', function (Blueprint $table) {
            $table->integer('retrieve_server')->after('push_s3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        chema::table('christie_spider_sales', function (Blueprint $table) {
            $table->dropColumn('retrieve_server');
        });
    }
}
