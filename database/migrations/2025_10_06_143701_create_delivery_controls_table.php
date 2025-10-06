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
        Schema::create('delivery_controls', function (Blueprint $table) {
            $table->id();
            $table->string('control_id');
            $table->string('details')->nullable();
            $table->string('ctr_time');
            $table->integer('route_id')->nullable();
            $table->string('ctr_days')->nullable();
            $table->string('ctr_months')->nullable();
            $table->string('ctr_years')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_controls');
    }
};
