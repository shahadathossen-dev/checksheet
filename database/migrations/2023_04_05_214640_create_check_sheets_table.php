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
        Schema::create('check_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index('title_index');
            $table->text('description')->nullable();
            $table->integer('due_by')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->enum('type', CheckSheetType::toArray())->default(CheckSheetType::DAILY());
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
        Schema::dropIfExists('check_sheets');
    }
};
