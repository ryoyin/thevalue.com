<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAwsSnsMobiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aws_sns_mobiles', function (Blueprint $table) {
            //id	os	type	token	user_data	locale	endpoint_arn	subscription_arn	aws_sns_platform_id	aws_sns_topic_id
            $table->increments('id');
            $table->string('os', 10);
            $table->string('type', 10);
            $table->string('token');
            $table->string('user_data');
            $table->string('locale');
            $table->string('endpoint_arn');
            $table->string('subscription_arn');
            $table->string('aws_sns_platform_id');
            $table->string('aws_sns_topic_id');
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
        Schema::dropIfExists('aws_sns_mobiles');
    }
}
