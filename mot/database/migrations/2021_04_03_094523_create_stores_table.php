<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->nullable()->index();
            $table->string('slug', 75)->nullable()->index();
            $table->string('name', 75)->nullable();
            $table->tinyInteger('type')->nullable()->default(0);
            $table->string('tax_id', 50)->nullable();
            $table->string('tax_id_type', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('zipcode', 15)->nullable();
            $table->string('phone', 20)->nullable();
            $table->timestamps();
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->unsignedBigInteger('store_id')->after('status');
            $table->boolean('is_owner')->default(false)->after('store_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_vendor_id_foreign');
            $table->dropColumn('vendor_id');
            $table->unsignedBigInteger('store_id')->after('brand_id');
            $table->foreign('store_id')->references('id')->on('stores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_store_id_foreign');
            $table->dropColumn('store_id');
            $table->bigInteger('vendor_id');
            $table->foreign('vendor_id')->references('id')->on('vendors');
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('store_id');
            $table->dropColumn('is_owner');
        });

        Schema::dropIfExists('stores');
    }
}
