<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //category_id, slug, photo_id, hit_counter, share_counter
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->string('slug');
            $table->enum('status', ['draft', 'pending', 'published', 'suspend']);
//            $table->string('source');
            $table->integer('photo_id')->nullable();
            $table->integer('hit_counter')->nullable();
            $table->integer('share_counter')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
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
        Schema::dropIfExists('articles');
    }
}
