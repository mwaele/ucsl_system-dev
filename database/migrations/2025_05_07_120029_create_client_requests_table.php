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
        Schema::create('client_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clientId');
            $table->string('collectionLocation');
            $table->text('parcelDetails');
            $table->dateTime('dateRequested');
            $table->foreignId('userId');
            $table->foreignId('vehicleId');
            $table->string('requestId');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_requests');
    }
};
