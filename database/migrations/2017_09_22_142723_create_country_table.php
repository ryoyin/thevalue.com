<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            // name,alpha-2,alpha-3,country-code,iso_3166-2,region,sub-region,region-code,sub-region-code
            $table->increments('id');

            $table->string('name');
            $table->string('alpha_2');
            $table->string('alpha_3');
            $table->string('country_code');
            $table->string('iso_3166_2');
            $table->string('region')->nullable();
            $table->string('sub_region')->nullable();
            $table->string('region_code')->nullable();
            $table->string('sub_region_code')->nullable();

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
        Schema::dropIfExists('countries');
    }
}
