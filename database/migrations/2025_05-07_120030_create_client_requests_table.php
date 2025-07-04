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
            $table->foreignId('category_id');
            $table->foreignId('sub_category_id');
            $table->string('requestId')->unique();
            $table->string('collectionLocation')->nullable();
            $table->text('parcelDetails')->nullable();
            $table->dateTime('dateRequested')->nullable();
            $table->foreignId('userId')->nullable();
            $table->foreignId('vehicleId')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('office_id')->nullable();
            $table->string('status')->default('pending collection');
            $table->foreignId('collected_by')->nullable()->constrained('users');
            $table->string('consignment_no')->nullable();
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
