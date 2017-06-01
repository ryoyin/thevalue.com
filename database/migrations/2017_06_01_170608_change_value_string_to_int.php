<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeValueStringToInt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auction_items', function (Blueprint $table) {
            $table->bigInteger('estimate_value_initial')->nullable()->change();
            $table->bigInteger('estimate_value_end')->nullable()->change();
            $table->bigInteger('sold_value')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auction_items', function (Blueprint $table) {
            $table->string('estimate_value_initial')->nullable()->change();
            $table->string('estimate_value_end')->nullable()->change();
            $table->string('sold_value')->nullable()->change();
        });
    }
}
