<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUrlNDateToSothebysSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sothebys_sales', function($table) {
            $table->string('url')->after('id');
            $table->string('title')->after('url');
            $table->timestamp('start_date')->nullable()->after('url');
            $table->timestamp('end_date')->nullable()->after('start_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sothebys_sales', function($table) {
            $table->dropColumn('url');
            $table->dropColumn('title');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
        });
    }
}
