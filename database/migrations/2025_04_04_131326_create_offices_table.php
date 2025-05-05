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
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('createdBy')->constrained('users', 'id')->onDelete('cascade');
		    $table->string("name");
		    $table->string("shortName");
		    $table->string("country");
		    $table->string("city");
		    $table->text("longitude")->nullable();
		    $table->text("latitude")->nullable();
		    $table->string("type")->default("staff"); //agent
		    $table->integer("mpesaTill")->nullable();
		    $table->integer("mpesaPaybill")->nullable();
		    $table->text("mpesaTillStkCallBack")->nullable();
		    $table->text("mpesaTillC2bConfirmation")->nullable();
		    $table->text("mpesaTillC2bValidation")->nullable();
		    $table->text("mpesaPaybillStkCallBack")->nullable();
		    $table->text("mpesaPaybillC2bConfirmation")->nullable();
		    $table->text("mpesaPaybillC2bValidation")->nullable();
		    $table->integer("approvedBy")->nullable();
		    $table->string("status")->default("pending");
		
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};
