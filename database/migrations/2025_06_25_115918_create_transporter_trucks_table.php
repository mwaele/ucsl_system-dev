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
        Schema::create('transporter_trucks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transporter_id');
            $table->string('reg_no');
            $table->string('driver_name');
            $table->string('driver_contact');
            $table->string('driver_id_no');
            $table->string('truck_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transporter_trucks');
    }
};
