<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpcomingToYiduSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yidu_sales', function (Blueprint $table) {
            $table->boolean('upcoming')->after('int_sale_id')->default(1);
            $table->boolean('raw_push_s3')->after('upcoming')->default(0);
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
            $table->dropColumn('upcoming');
            $table->dropColumn('raw_push_s3');
        });
    }
}
