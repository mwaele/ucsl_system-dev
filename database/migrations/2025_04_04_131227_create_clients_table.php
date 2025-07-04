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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string("accountNo")->nullable();
            $table->string("name");
            $table->string("email");
            $table->string("password")->nullable();
            $table->string("contact");
            $table->string("address");
            $table->string("city")->nullable();
            $table->string("building")->nullable();
            $table->string("country");
            $table->string("category");
            $table->string("contactPerson");
            $table->string("contactPersonPhone")->nullable();
            $table->string("contactPersonEmail")->nullable();
            $table->integer('contact_person_id_no')->nullable();
            $table->string("type");
            $table->string("industry")->nullable();
            $table->string("kraPin")->nullable();
            $table->string("postalCode")->nullable();
            $table->string("status")->nullable();
            $table->string('special_rates_status')->nullable();
            $table->string("verificationCode")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
