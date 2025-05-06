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
        Schema::table('shipments', function (Blueprint $table) {
            $table->string('senderAddress')->nullable();
            $table->string('receiverAddress')->nullable();
            $table->integer('createdBy');
            $table->integer('receiverClerk')->nullable();
            $table->integer('receiverIdNo')->nullable();
            $table->string('receivedDate')->nullable();
            $table->string('arrivalDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn([
                'senderAddress',
                'receiverAddress',
                'receivedDate',
                'arrivalDate',
                'receiverClerk',
                'createdBy',
                'receiverIdNo'
            ]);
        });
    }
};
