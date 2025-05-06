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
        Schema::create('shipments', function (Blueprint $table) {
			$table->id();
			$table->string("waybillNo")->unique();
			$table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
			$table->foreignId('client_id')->constrained('clients', 'id')->onDelete('cascade')->nullable();
			$table->integer("loadingSheetId")->nullable();
			$table->string("senderName");
			$table->string("senderEmail")->nullable();
			$table->string("senderPhone");
			$table->string("senderTown")->nullable();
			$table->string("senderCountry")->nullable()->default("Kenya");
			$table->string("senderStreet")->nullable();
			$table->string("senderBuilding")->nullable();
			$table->string("senderAccountNo")->nullable();
			$table->string("senderIdNo")->nullable();
			$table->string("senderKraPin")->nullable();
			$table->string("senderContactPerson")->nullable();
			$table->string("senderContactPersonPhone")->default(null);
			$table->string("receiverName");
			$table->string("receiverEmail")->nullable();
			$table->string("receiverPhone");
			$table->string("clientBranchOrigin")->nullable();
			$table->string("clientBranchDestination")->nullable();
			$table->string("receiverTown")->nullable();
			$table->string("receiverCountry")->nullable()->default("Kenya");
			$table->string("receiverStreet")->nullable();
			$table->string("receiverBuilding")->nullable();
			$table->string("receiverContactPerson")->nullable();
			$table->string("receiverContactPersonPhone")->default(null);
			$table->text("descriptionOfGoods")->nullable();
			$table->text("origin")->nullable(); //from rates
			$table->text("destination")->nullable(); //from rates
			$table->text("shippedVia")->nullable();
			$table->dateTime("deliveryDate")->nullable();
			$table->string("trackingNo")->nullable();
			$table->dateTime("dispatchDate")->nullable();
			$table->integer("dispatchBy")->nullable();
			$table->text("service")->nullable();
			$table->integer("actualWeight")->nullable();
			$table->integer("length")->nullable();
			$table->integer("width")->nullable();
			$table->integer("qty")->nullable();
			$table->integer("weight")->nullable();
			$table->integer("height")->nullable();
			$table->string("cbm")->nullable();
			$table->integer("numOfPackages")->nullable();
			$table->dateTime("expectedDeliveryDate");
			$table->string("shipmentType")->default("normal");
			$table->string("statusAtCollection")->default("Not yet collected");
			$table->integer("cost")->nullable();
			$table->text("picture")->nullable();
			$table->text("customerNote")->nullable();
			$table->string("pickupLocation")->nullable();
			$table->string("deliveryLocation")->nullable();
			$table->enum('deliveryOption', ['doortodoor', 'officetooffice'])
			->default('doortodoor');
			$table->string("deliveryStatus")->default("Not Delivered");
			$table->string("paymentStatus")->default("Not Paid");
			$table->string("paymentType")->default("COD");
			$table->string("clientType")->default("Walkin");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
