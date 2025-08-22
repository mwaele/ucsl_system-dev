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
            $table->string('priority_level')->nullable();
            $table->string('fragile_item')->nullable();
            $table->integer('priority_level_amount')->nullable();
            $table->integer('fragile_item_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_collections', function (Blueprint $table) {
            $table->dropColumn(['priority_level_amount','fragile_item_amount','priority_level','fragile_item']);
        });
    }
};
