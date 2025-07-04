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
        Schema::create('loading_sheets', function (Blueprint $table) {
            $table->id();
            $table->dateTime('dispatch_date')->nullable();
            $table->foreignId('dispatcher_id');
            $table->foreignId('office_id');
            $table->foreignId('station_id');
            $table->foreignId('destination_id');
            $table->string('destination');
            $table->integer('batch_no')->unique();
            $table->string('dispatched_by');
            $table->string('transported_by');
            $table->string('transporter_phone');
            $table->string('reg_details')->nullable();
            $table->text('transporter_signature')->nullable();
            $table->string('vehicle_reg_no');
            $table->string('received_by')->nullable();
            $table->string('receiver_phone')->nullable();
            $table->integer('receiver_id_no')->nullable();
            $table->dateTime('received_date')->nullable();
            $table->text('receiver_signature')->nullable();
            $table->dateTime('date_closed')->nullable();
            $table->string('remarks')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loading_sheets');
    }
};
