<?php

use Carbon\Carbon;
use App\Enums\CheckSheetType;
use App\Enums\CheckSheetStatus;
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
            $table->string('title')->index('title_index');
            $table->text('description');
            $table->date('due_date')->default(Carbon::today());
            $table->unsignedBigInteger('checksheet_id');
            $table->foreign('checksheet_id')->references('id')->on('check_sheets');
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->foreign('submitted_by')->references('id')->on('users');
            $table->enum('status', CheckSheetStatus::toArray())->default(CheckSheetStatus::PENDING());
            $table->timestamps();
            $table->softDeletes();
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
