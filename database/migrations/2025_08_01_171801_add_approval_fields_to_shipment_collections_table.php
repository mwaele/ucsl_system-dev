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
            $table->timestamp('approved_at')->nullable()->after('agent_approved');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
            $table->string('grn_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_collections', function (Blueprint $table) {
            //
        });
    }
};
