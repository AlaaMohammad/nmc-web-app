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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('wo_number');
            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('services');
            $table->unsignedBigInteger('service_category_id');
            $table->foreign('service_category_id')->references('id')->on('service_categories');
            $table->text('scope')->nullable();
            $table->unsignedBigInteger('nte')->nullable();
            $table->longText('client_description');
            $table->longText('technician_report')->nullable();
            $table->string('priority')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->string('location')->nullable();
           $table->float('latitude')->nullable();
           $table->float('longitude')->nullable();
            $table->string('current_status')->default('pending');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('assignment_status', ['pending', 'assigned', 'unassigned'])->default('unassigned');
            $table->unsignedInteger('assigned_to')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->foreign('assigned_to')->references('id')->on('technicians');
            $table->enum('payment_status', ['Pending', 'Paid'])->default('Pending');
            $table->enum('internal_status', ['Check In', 'Pending Acceptance', 'Denied', 'Work Complete', 'Accepted - Pending ETA', 'Finance - Pending Payment', 'Quote Needed', 'Check Out - Pending Return ETA', 'New - Pending PO', 'Pending - Avetta', 'Quote Denied', 'New - Pending Info', 'New', 'ETA Submitted', 'Quote Review', 'Quote Review Complete', 'Sourcing Needed', 'DNA', 'Waiting on Parts', 'Invoice Submitted', 'Hold', 'Pending Approval', 'Quote Approved - Pending ETA', 'Tech Report Needed', 'Pending PO Uplift', 'Finance - Paid', 'Cancelled by Client', 'Quote Closed', 'Final - No Cost/Sell', 'Invoice Rejected', 'Deleted', 'Customer Preapproval', 'Denied by Customer'])->default('New');
            $table->string('reason')->nullable();
            $table->dateTime('approval_time')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();

            $table->date('due_by')->nullable();
            $table->text('specific_instructions')->nullable();
            $table->string('timeslot')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
