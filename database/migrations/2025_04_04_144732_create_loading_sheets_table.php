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
        Schema::create('loading_sheets', function (Blueprint $table) {
            $table->id();
            $table->dateTime("dispatchDate");
            $table->text("origin");
            $table->text("destination");
            $table->string("vehicleNo")->nullable();
            $table->foreignId('dispatchedBy')->nullable()->constrained('users', 'id')->onDelete('set null');
		// .onDelete("CASCADE");
            $table->text("dispatcherSignature");
            $table->text("transporter");
            $table->integer("driver")->nullable();
            $table->string("driverContact")->nullable();
            $table->text("driverSignature");
            $table->foreignId('receivedBy')->nullable()->constrained('users', 'id')->onDelete('set null');
            // .onDelete("CASCADE");
            $table->dateTime("receievedDate");
            $table->text("receieverSignature");
            $table->text("description")->nullable();
            $table->string("status")->default("active"); //closed, dispatched
            $table->dateTime("dateClosed")->nullable();
            $table->text("remarks")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loading_sheets');
    }
};
