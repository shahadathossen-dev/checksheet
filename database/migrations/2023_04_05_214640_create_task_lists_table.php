<?php

use Carbon\Carbon;
use App\Enums\CheckSheetType;
use App\Enums\CheckSheetStatus;
use App\Enums\TaskListStatus;
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
        Schema::create('task_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('checksheet_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('type', CheckSheetType::toArray())->default(CheckSheetType::DAILY());
            $table->dateTime('submit_date')->default(Carbon::now())->index('submit_date_index');
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->date('due_date')->default(Carbon::today())->index('due_date_index');
            $table->enum('status', TaskListStatus::toArray())->default(TaskListStatus::PENDING());
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('checksheet_id')->references('id')->on('check_sheets');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('submitted_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_lists');
    }
};
