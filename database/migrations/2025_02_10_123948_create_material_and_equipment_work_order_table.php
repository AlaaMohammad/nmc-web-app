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
        Schema::create('material_and_equipment_work_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_and_equipment_id');
            $table->unsignedBigInteger('work_order_id');
            $table->timestamps();

            // Shorter constraint name
            $table->foreign('material_and_equipment_id', 'maeworkorder_mae_id_foreign')
                ->references('id')
                ->on('material_and_equipments')
                ->onDelete('cascade');

            $table->foreign('work_order_id', 'maeworkorder_wo_id_foreign')
                ->references('id')
                ->on('work_orders')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_and_equipment_work_order');
    }
};
