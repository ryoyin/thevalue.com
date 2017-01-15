<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //title, note, short_desc, description, source, author, photographer, status
        Schema::create('article_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lang', 4);
            $table->string('title');
            $table->string('note');
            $table->longText('short_desc');
            $table->longText('description');
            $table->string('source');
            $table->string('author');
            $table->string('photographer');
            $table->enum('status', ['draft', 'pending', 'published', 'suspend']);
            $table->integer('article_id');
            $table->softDeletes();
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
        Schema::dropIfExists('article_details');
    }
}
