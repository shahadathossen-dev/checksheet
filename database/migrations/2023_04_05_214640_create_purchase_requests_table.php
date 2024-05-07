<?php

use App\Enums\PurchaseRequestStatus;
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
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index('title_index');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('status', PurchaseRequestStatus::toArray())->default(PurchaseRequestStatus::PENDING());
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_requests');
    }
};
