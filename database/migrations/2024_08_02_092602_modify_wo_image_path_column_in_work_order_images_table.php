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
        Schema::table('work_order_images', function (Blueprint $table) {
            //
            Schema::table('work_order_images', function (Blueprint $table) {
                $table->text('wo_image_path')->change();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_order_images', function (Blueprint $table) {
            //
        });
    }
};
