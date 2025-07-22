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
        Schema::table('rates', function (Blueprint $table) {
            $table->string('bands')->nullable();
            $table->integer('additional_cost_per_kg')->nullable();
            $table->integer('intercity_additional_cost_per_kg')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rates', function (Blueprint $table) {
            $table->dropColumn([
                'bands',
                'additional_cost_per_kg',
                'intercity_additional_cost_per_kg'
            ]);
        });
    }
};
