<?php

use App\Enums\LeaveStatus;
use App\Enums\LeaveType;
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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('checked_by')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date')->default(date("Y-m-d"));
            $table->date('end_date')->default(date("Y-m-d"));
            $table->enum('type', LeaveType::toArray())->default(LeaveType::INDIVIDUAL());
            $table->enum('status', LeaveStatus::toArray())->default(LeaveStatus::PENDING());
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('checked_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leaves');
    }
};
