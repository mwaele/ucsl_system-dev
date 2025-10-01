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
        $table->unsignedBigInteger('delivery_failed_id')->nullable()->after('id');

        $table->foreign('delivery_failed_id')
              ->references('id')->on('delivery_faileds')
              ->onDelete('set null'); // If the record is deleted,
              //  set null
        
        $table->text('delivery_failure_remarks')->nullable()->after('delivery_failed_id');
    });
}

public function down(): void
{
    Schema::table('shipment_collections', function (Blueprint $table) {
        $table->dropForeign(['delivery_failed_id']);
        $table->dropColumn('delivery_failed_id');
        $table->dropColumn('delivery_failure_remarks');
    });
}

};
