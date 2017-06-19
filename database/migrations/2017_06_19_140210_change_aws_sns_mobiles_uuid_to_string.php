<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAwsSnsMobilesUuidToString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aws_sns_mobiles', function (Blueprint $table) {
            $table->string('uuid')->change();
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
            $table->smallInteger('uuid')->change();
        });
    }
}
