<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToAuctionSeries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auction_series', function (Blueprint $table) {
            $table->enum('status', array('pending', 'published', 'withdraw'))->after('auction_house_id')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auction_series', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
