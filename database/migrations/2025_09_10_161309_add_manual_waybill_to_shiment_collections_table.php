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
           $table->boolean('manual_waybill_status')->default(0)
        ->after('status');
            $table->string('manual_waybill')->nullable()->after('manual_waybill_status');
            $table->string('manual_waybillNo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_collections', function (Blueprint $table) {
            $table->dropColumn('manual_waybill_status');
            $table->dropColumn('manual_waybill');
            $table->dropColumn('manual_waybillNo');
        });
    }
};
