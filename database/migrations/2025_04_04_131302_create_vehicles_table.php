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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string("regNo", 150)->unique();
            $table->string("type", 150)->nullable();
            $table->string("color", 150)->nullable();
            $table->string("tonnage", 150)->nullable();
            $table->string("status", 150)->default("available");
            $table->text("description");
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->text("ownedBy");
            $table->foreignId('shipment_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
