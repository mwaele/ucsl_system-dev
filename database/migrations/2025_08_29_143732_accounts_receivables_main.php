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
        Schema::create('accounts_receivables_main', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id'); // FK to clients
            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('current', 15, 2)->default(0);
            $table->decimal('30_days', 15, 2)->default(0);
            $table->decimal('60_days', 15, 2)->default(0);
            $table->decimal('90_days', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts_receivables_main');
    }
};
