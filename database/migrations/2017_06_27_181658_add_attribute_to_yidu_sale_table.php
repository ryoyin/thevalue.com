<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttributeToYiduSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yidu_sales', function (Blueprint $table) {
            $table->boolean('resize')->after('image');
            $table->boolean('pushS3')->after('resize');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yidu_sales', function (Blueprint $table) {
            $table->dropColumn('resize');
            $table->dropColumn('pushS3');
        });
    }
}
