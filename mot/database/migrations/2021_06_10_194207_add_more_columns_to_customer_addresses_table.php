<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumnsToCustomerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->text('address2')->nullable()->after('address');
            $table->text('address3')->nullable()->after('address2');
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
            $table->dropColumn(['address2', 'address3']);
        });
    }
}
