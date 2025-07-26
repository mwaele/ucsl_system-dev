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
        Schema::create('shipment_deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('requestId');
            $table->integer('client_id');
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->integer('receiver_id_no');
            $table->string('receiver_type');
            $table->string('agent_name')->nullable();
            $table->string('agent_phone')->nullable();
            $table->integer('agent_id_no')->nullable();
            $table->string('delivery_location');
            $table->datetime('delivery_datetime');
            $table->integer('delivered_by');
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_deliveries');
    }
};
