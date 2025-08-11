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
        Schema::create('shipment_arrivals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_collection_id');
            $table->string('requestId');
            $table->string('date_received');
            $table->integer('verified_by');
            $table->integer('cost');
            $table->integer('vat_cost');
            $table->integer('total_cost');
            $table->string('status');
            $table->string('driver_name');
            $table->string('vehicle_reg_no');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_arrivals');
    }
};
