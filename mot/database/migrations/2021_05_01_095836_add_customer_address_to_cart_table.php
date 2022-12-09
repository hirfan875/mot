<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerAddressToCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->dropForeign('customer_addresses_customer_id_foreign');
        });
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->change(); //to support guest
        });
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers');
        });
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')->references('id')->on('customer_addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('address_id');
        });

        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->dropForeign('customer_addresses_customer_id_foreign');
        });
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable(false)->change();
        });
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers');
        });


    }
}
