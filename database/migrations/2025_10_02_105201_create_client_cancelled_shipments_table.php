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
        Schema::create('client_cancelled_shipments', function (Blueprint $table) {
            $table->id();
            $table->string('requestId');
            $table->text('reason');
            $table->string('cancelled_by'); // e.g., 'client' or 'admin'
            $table->string('status')->default('pending'); // e.g., 'pending', 'approved', 'rejected'
            $table->text('admin_comments')->nullable(); 
            $table->timestamp('reviewed_at')->nullable(); // when admin reviewed the cancellation
            $table->string('reviewed_by')->nullable(); // admin user who reviewed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_cancelled_shipments');
    }
};
