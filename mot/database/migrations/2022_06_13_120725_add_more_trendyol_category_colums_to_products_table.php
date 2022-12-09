<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreTrendyolCategoryColumsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('trendyol_category')->nullable()->after('barcode');
            $table->unsignedBigInteger('trendyol_categories_id')->nullable()->after('barcode');
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
            $table->dropColumn('trendyol_category');
            $table->dropColumn('trendyol_categories_id');
        });
    }
}
