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
        Schema::create('shipment_arrivals_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id');
            $table->string('item_name');
            $table->integer("actual_quantity")->nullable();
            $table->integer("actual_weight")->nullable();
            $table->integer("actual_length")->nullable();
            $table->integer("actual_width")->nullable();
            $table->integer("actual_height")->nullable();
            $table->integer("actual_volume")->nullable();
            $table->text("remarks")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_arrivals_items');
    }
};
