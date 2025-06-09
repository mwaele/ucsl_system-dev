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
        Schema::table('shipment_items', function (Blueprint $table) {
            $table->integer("actual_quantity")->nullable();
            $table->integer("actual_weight")->nullable();
            $table->integer("actual_length")->nullable();
            $table->integer("actual_width")->nullable();
            $table->integer("actual_height")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_items', function (Blueprint $table) {
            $table->dropColumn([
                "actual_quantity",
                "actual_weight",
                "actual_length",
                "actual_width",
                "actual_height",
            ]);
        });
    }
};
