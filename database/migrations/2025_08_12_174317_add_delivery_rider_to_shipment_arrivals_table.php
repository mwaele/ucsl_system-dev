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
        Schema::table('shipment_arrivals', function (Blueprint $table) {
            $table->integer('delivery_rider')->nullable();
            $table->string('delivery_rider_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_arrivals', function (Blueprint $table) {
            $table->dropColumn(['delivery_rider','delivery_rider_status']);
        });
    }
};
