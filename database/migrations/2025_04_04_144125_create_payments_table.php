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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('type'); //invoice/cash/mpesa/cheque
            $table->integer('amount');
            $table->string('reference_no');
            $table->datetime('date_paid');
            $table->foreignId('client_id');
            $table->foreignId('shipment_collection_id');
            $table->string('status')->default('Pending Verification');
            $table->integer('paid_by');
            $table->string('received_by');
            $table->string('verified_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
