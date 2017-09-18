<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoshowStatusToAuctionSaleItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE auction_items MODIFY status ENUM('pending', 'sold', 'bought in', 'withdraw', 'noshow') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE auction_items MODIFY status ENUM('pending', 'sold', 'bought in', 'withdraw') NOT NULL");
    }
}
