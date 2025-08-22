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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('request_id');
            $table->string('agent_name');
            $table->string('agent_id_no')->unique();
            $table->string('agent_phone_no')->nullable();
            $table->text('agent_reason')->nullable();
            $table->boolean('agent_requested')->default(false);
            $table->boolean('agent_approved')->default(false);
            $table->boolean('agent_declined')->default(false);
            $table->text('agent_approval_remarks')->nullable();
            $table->timestamp('agent_approved_at')->nullable();
            $table->unsignedBigInteger('agent_approved_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
