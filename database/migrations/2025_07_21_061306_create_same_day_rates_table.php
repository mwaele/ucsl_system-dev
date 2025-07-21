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
        Schema::create('same_day_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id');
            $table->string('destination');
            $table->string('bands');
            $table->integer('additional_kg')->nullable();
            $table->integer('intercity_additional_kg')->nullable();
            $table->integer('rate');
            $table->dateTime("applicableFrom")->nullable();
            $table->dateTime("applicableTo")->nullable();
            $table->string("status")->default("active"); //inactive
            $table->string("approvalStatus")->default("pending"); //pending/approved
            $table->dateTime("dateApproved")->nullable();
            $table->integer("approvedBy")->nullable();
            $table->foreignId('added_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('same_day_rates');
    }
};
