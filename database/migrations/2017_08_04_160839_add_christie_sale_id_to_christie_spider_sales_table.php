<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChristieSaleIdToChristieSpiderSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('christie_spider_sales', function($table) {

            $table->string('sale_id')->after('int_sale_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('christie_spider_sales', function($table) {
            $table->dropColumn('sale_id');
        });
    }
}
