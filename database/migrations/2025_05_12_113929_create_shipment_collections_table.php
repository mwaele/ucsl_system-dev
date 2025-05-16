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
        Schema::create('shipment_collections', function (Blueprint $table) {
            $table->id();
            $table->string('receiver_name');
            $table->string('receiver_id_no');
            $table->string('receiver_phone');
            $table->string('receiver_address');
            $table->string('receiver_town');
            $table->string('requestId');
            $table->foreignId('client_id');
            $table->unsignedBigInteger('origin_id');
            $table->unsignedBigInteger('destination_id');
            $table->decimal('cost', 10, 2);
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_collections');
    }
};
