<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResizedToPhotos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->boolean('push_s3')->after('size')->default(0);
            $table->boolean('resized')->after('size')->default(0);
            $table->string('image_large_path')->after('size')->nullable();
            $table->string('image_medium_path')->after('size')->nullable();
            $table->string('image_small_path')->after('size')->nullable();
            $table->string('image_blur_path')->after('size')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->dropColumn('push_s3');
            $table->dropColumn('resized');
            $table->dropColumn('image_large_path');
            $table->dropColumn('image_medium_path');
            $table->dropColumn('image_small_path');
            $table->dropColumn('image_blur_path');
        });
    }
}
