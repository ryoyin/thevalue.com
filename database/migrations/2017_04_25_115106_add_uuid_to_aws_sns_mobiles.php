<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUuidToAwsSnsMobiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aws_sns_mobiles', function (Blueprint $table) {
            $table->smallInteger('uuid')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aws_sns_mobiles', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}
