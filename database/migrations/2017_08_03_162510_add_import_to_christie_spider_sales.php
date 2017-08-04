<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImportToChristieSpiderSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('christie_spider_sales', function (Blueprint $table) {
            $table->boolean('import')->after('download_images')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('christie_spider_sales', function (Blueprint $table) {
            $table->dropColumn('import');
        });
    }
}
