<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillingAndPaymentFieldsToShipmentCollectionsTable extends Migration
{
    public function up()
    {
        Schema::table('shipment_collections', function (Blueprint $table) {
            $table->string('billing_party')->nullable()->after('total_cost'); 
            $table->string('payment_mode')->nullable()->after('billing_party'); 
            $table->string('reference')->nullable()->after('payment_mode');
        });
    }

    public function down()
    {
        Schema::table('shipment_collections', function (Blueprint $table) {
            $table->dropColumn(['billing_party', 'payment_mode', 'reference']);
        });
    }
}

