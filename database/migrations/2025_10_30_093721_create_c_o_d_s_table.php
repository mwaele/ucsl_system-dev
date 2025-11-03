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
        Schema::create('c_o_d_s', function (Blueprint $table) {
            $table->id();
            $table->string('requestId');
            $table->integer('collectedBy');
            $table->string('dateCollected');
            $table->string('amountCollected');  
            $table->integer('expectedAmount');
            $table->integer('collectionBalance');
            $table->string('riderRemarks')->nullable();
            $table->integer('amountReceived')->nullable();
            $table->integer('receivedBy')->nullable();
            $table->string('dateReceived')->nullable(); 
            $table->integer('receicedBalance')->nullable();
            $table->string('receiverRemarks')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_o_d_s');
    }
};
