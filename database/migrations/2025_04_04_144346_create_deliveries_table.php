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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string("deliveryStatus")->default("Pickup");
            $table->text("deliveryNote")->nullable();
            $table->string("recievedBy");
            $table->integer("recieverId")->nullable();
            $table->integer("receiverIdNumber")->nullable();
            $table->string("recieverPhoneNumber")->nullable();
            $table->text("recieverSignature")->nullable();
              // Nullable foreign key
            $table->foreignId('servedBy')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->string("statusAtDelivery")->nullable();
            $table->string("waybillNo");
            $table->foreignId('shipment_id');
            $table->text("picture")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
