<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checksheet_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('checksheet_id');
            $table->string('title')->index('title_index');
            $table->boolean('required')->default(0);
            $table->foreign('checksheet_id')->references('id')->on('check_sheets')->onDelete('cascade');
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
        Schema::dropIfExists('checksheet_items');
    }
};
