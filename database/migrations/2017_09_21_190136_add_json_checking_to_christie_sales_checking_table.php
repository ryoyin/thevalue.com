<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJsonCheckingToChristieSalesCheckingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('christie_sales_checking', function($table) {
            $table->smallInteger('json')->nullable();
            $table->smallInteger('validate_data')->nullable();
            $table->smallInteger('validate_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('christie_sales_checking', function($table) {
            $table->dropColumn('json');
            $table->dropColumn('validate_data');
            $table->dropColumn('validate_image');
        });
    }
}
