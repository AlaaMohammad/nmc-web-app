<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_order_visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_order_id');
            $table->foreign('work_order_id')->references('id')->on('work_orders');
            $table->string('visit_number');

            $table->dateTime('checkin_time')->nullable();
            $table->string('checked_in_by')->nullable();

            $table->dateTime('checkout_time')->nullable();
            $table->string('checked_out_by')->nullable();
            $table->string('duration')->nullable(); // '1 hour 30 minutes

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_visits');
    }
};
