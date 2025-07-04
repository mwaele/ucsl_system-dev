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
        Schema::create('tracking_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trackId');
            $table->foreignId('user_id')->nullable();
            $table->foreignId('vehicle_id')->nullable();
            $table->dateTime('date');
            $table->text('details');
            $table->integer('qty')->nullable();
            $table->integer('weight')->nullable();
            $table->integer('volume')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_infos');
    }
};
