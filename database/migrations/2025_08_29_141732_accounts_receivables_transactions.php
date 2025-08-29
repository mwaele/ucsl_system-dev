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
        Schema::create('accounts_receivables_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('request_id')->nullable();
            $table->string('batch_no')->nullable();
            $table->string('waybill_no')->nullable();
            $table->string('reference')->nullable();
            $table->text('details')->nullable();
            $table->date('date');
            $table->dateTime('datetime');
            $table->unsignedBigInteger('posted_by');
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('vat', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('dr', 15, 2)->default(0);
            $table->decimal('cr', 15, 2)->default(0);
            $table->enum('type_of_transaction', ['invoice', 'payment', 'credit_note', 'debit_note', 'journal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts_receivables_transactions');
    }
};
