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
            $table->decimal('total_insurance_amount', 10, 2)->nullable()->after('fragile_item_amount');
            $table->decimal('insurance_charged_amount', 10, 2)->nullable()->after('total_insurance_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_collections', function (Blueprint $table) {
            $table->dropColumn('total_insurance_amount');
            $table->dropColumn('insurance_charged_amount');
        });
    }
};
