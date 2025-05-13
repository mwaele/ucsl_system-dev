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
    Schema::create('itemShipments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('shipment_id');
    $table->string('item_name')->nullable();
    $table->integer('packages')->nullable();
    $table->decimal('weight', 8, 2)->nullable();
    $table->decimal('length', 8, 2)->nullable();
    $table->decimal('width', 8, 2)->nullable();
    $table->decimal('height', 8, 2)->nullable();

    $table->foreign('shipment_id')->references('id')->on('shipment_collections')->onDelete('cascade');
    });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
