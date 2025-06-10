<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sent_messages', function (Blueprint $table) {
            $table->id();
            $table->string('request_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('rider_id')->nullable();
            $table->string('recipient_type'); // 'client' or 'rider'
            $table->string('recipient_name');
            $table->string('phone_number');
            $table->string('subject');
            $table->text('message');
            $table->timestamps();

            // Foreign keys
            $table->foreign('client_id')->references('id')->on('clients')->nullOnDelete();
            $table->foreign('rider_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sent_messages');
    }
};

