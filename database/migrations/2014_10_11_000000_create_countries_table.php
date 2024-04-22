<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();

            $table->string('code')->index('code')->nullable();
            $table->string('alpha2')->index('alpha2')->nullable();
            $table->string('alpha3')->index('alpha3')->nullable();
            $table->json('country_calling_codes')->nullable();
            $table->json('currencies')->nullable();
            $table->string('emoji')->nullable();
            $table->string('ioc')->nullable();
            $table->json('languages')->nullable();
            $table->string('name')->index('name')->nullable();
            $table->string('status')->nullable();
            $table->integer('zone_id')->nullable();
            $table->string('zone_name')->index('zone_name')->nullable();

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
