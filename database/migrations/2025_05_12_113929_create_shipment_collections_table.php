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
            $table->string('requestId');
            $table->foreignId('client_id');
            $table->unsignedBigInteger('origin_id');
            $table->unsignedBigInteger('destination_id');
            $table->foreignId('collected_by')->nullable()->constrained('users');
            $table->string('consignment_no')->nullable();
            $table->string('waybill_no')->nullable();
            $table->string('sender_type')->nullable();
            $table->string('sender_name')->nullable();
            $table->string('sender_contact')->nullable();
            $table->string('sender_email')->nullable();
            $table->string('sender_address')->nullable();
            $table->string('sender_town')->nullable();
            $table->integer('sender_id_no')->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('receiver_id_no')->nullable();
            $table->string('receiver_phone')->nullable();
            $table->string('receiver_email')->nullable();
            $table->string('receiver_address')->nullable();
            $table->string('receiver_town')->nullable();
            $table->string('special_rates_status')->nullable();
            $table->integer('base_cost')->nullable();
            $table->decimal('cost', 10, 2);
            $table->integer('vat')->nullable();
            $table->integer('total_cost')->nullable();
            $table->decimal('actual_cost', 10, 2)->nullable();
            $table->decimal('actual_vat', 10, 2)->nullable();
            $table->decimal('actual_total_cost', 10, 2)->nullable();
            $table->integer('total_weight')->nullable();
            $table->integer('total_quantity')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->string('billing_party')->nullable(); 
            $table->string('payment_mode')->nullable(); 
            $table->string('reference')->nullable();
            $table->string('manifest_generated_status')->nullable();
            $table->string('status')->nullable();
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
