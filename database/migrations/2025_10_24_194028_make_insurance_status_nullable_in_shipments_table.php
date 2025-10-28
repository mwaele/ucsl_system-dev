<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('shipment_collections', function (Blueprint $table) {
            $table->string('insurance_status')->nullable()->default('not_insured')->change();
        });
    }

    public function down(): void
    {
        Schema::table('shipment_collections', function (Blueprint $table) {
            $table->string('insurance_status')->nullable(false)->default(null)->change();
        });
    }
};

