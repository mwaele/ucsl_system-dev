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
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            $table->string("deliveryStatus")->default("Pickup");
            $table->text("note")->nullable();
            $table->string("office")->nullable();
            $table->string("location")->nullable();
            $table->string("loadingSheetId")->nullable();
            $table->string("waybillNo")->nullable();
            $table->integer("userId")->nullable();
            $table->foreignId('shipment_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trackings');
    }
};
