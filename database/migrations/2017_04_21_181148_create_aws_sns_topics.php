<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAwsSnsTopics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aws_sns_topics', function (Blueprint $table) {
//            id	locale	name	topic_arn	description
            $table->increments('id');
            $table->string('locale', 2);
            $table->string('name');
            $table->string('topic_arn');
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
        Schema::dropIfExists('aws_sns_topics');
    }
}
