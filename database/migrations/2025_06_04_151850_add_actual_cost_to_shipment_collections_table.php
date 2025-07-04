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
            $table->decimal('actual_cost', 10, 2)->nullable();
            $table->decimal('actual_vat', 10, 2)->nullable();
            $table->decimal('actual_total_cost', 10, 2)->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_collections', function (Blueprint $table) {
            $table->dropColumn([
                'actual_cost',
                'actual_vat',
                'actual_total_cost',
                'verified_by',
                'verified_at',

            ]);
        });
    }
};
