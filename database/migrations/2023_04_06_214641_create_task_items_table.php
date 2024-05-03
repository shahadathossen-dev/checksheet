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
        Schema::create('task_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('checksheet_item_id');
            $table->unsignedBigInteger('tasklist_id');
            $table->string('note')->nullable();
            $table->boolean('done')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('checksheet_item_id')->references('id')->on('checksheet_items')->onDelete('cascade');
            $table->foreign('tasklist_id')->references('id')->on('task_lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_items');
    }
};
