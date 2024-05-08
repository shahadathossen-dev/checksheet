<?php

use App\Enums\AdditionalTaskStatus;
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
        Schema::create('additional_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index('title_index');
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->boolean('note_required')->default(0);
            $table->date('due_date');
            $table->date('submit_date')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->enum('status', AdditionalTaskStatus::toArray())->default(AdditionalTaskStatus::PENDING());
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_tasks');
    }
};
