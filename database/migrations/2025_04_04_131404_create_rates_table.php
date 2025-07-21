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
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->integer("approvedBy")->nullable();
             // Add the foreign key column 'added_by'
            $table->foreignId('added_by')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('office_id');
            $table->foreignId('zone_id');
            $table->string("routeFrom")->nullable();
            $table->string("zone")->nullable();
            $table->string("origin")->nullable();
            $table->string("destination")->nullable();
            $table->decimal("rate", 8, 2)->default(0.00);
            $table->string("type")->default("normal"); 
            $table->dateTime("applicableFrom")->nullable();
            $table->dateTime("applicableTo")->nullable();
            $table->string("status")->default("active"); //inactive
            $table->string("approvalStatus")->default("pending"); //pending/approved
            $table->dateTime("dateApproved")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
