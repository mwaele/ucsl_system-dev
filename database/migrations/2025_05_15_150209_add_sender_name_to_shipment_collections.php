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
        Schema::table('shipment_collections', function (Blueprint $table) {
            $table->string('sender_type')->nullable();
            $table->string('sender_name')->nullable();
            $table->string('sender_contact')->nullable();
            $table->string('sender_address')->nullable();
            $table->string('sender_town')->nullable();
            $table->integer('sender_id_no')->nullable();
            $table->integer('vat')->nullable();
            $table->integer('total_cost')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_collections', function (Blueprint $table) {
            $table->dropColumn([
                'sender_type',
                'sender_name',
                'sender_contact',
                'sender_address',
                'sender_town',
                'sender_id_no',
                'vat',
                'total_cost'
            ]);
        });
    }
};
