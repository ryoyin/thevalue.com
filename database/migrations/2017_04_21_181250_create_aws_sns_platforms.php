<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAwsSnsPlatforms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // id	platform	platform_arn	description
        Schema::create('aws_sns_platforms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('platform');
            $table->string('type', 10);
            $table->string('platform_arn');
            $table->string('description')->nullable();
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
        Schema::dropIfExists('aws_sns_platforms');
    }
}
