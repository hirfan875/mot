<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountPriceFiledsToProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('promo_price', 10, 2)->after('price')->nullable();
            $table->unsignedBigInteger('promo_source_id')->after('promo_price')->nullable();
            $table->string('promo_source_type', 20)->after('promo_source_id')->nullable();
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
            $table->dropColumn('promo_price');
            $table->dropColumn('promo_source_id');
            $table->dropColumn('promo_source_type');
        });
    }
}
