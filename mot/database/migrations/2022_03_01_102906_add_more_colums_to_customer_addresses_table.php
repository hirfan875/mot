<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumsToCustomerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->string('aera')->nullable()->after('address3');
            $table->text('block')->nullable()->after('address3');
            $table->text('street_number')->nullable()->after('address3');
            $table->text('house_apartment')->nullable()->after('address3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->dropColumn('aera');
            $table->dropColumn('block');
            $table->dropColumn('street_number');
            $table->dropColumn('house_apartment');
        });
    }
}
