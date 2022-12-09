<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreTrendyolColumsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('barcode')->nullable()->after('is_approved');
            $table->string('stock_code')->nullable()->after('stock');
            $table->string('currency_type')->nullable()->after('created_by_id');
            $table->integer('vat_rate')->nullable()->after('created_by_id');
            $table->integer('cargo_company_id')->nullable()->after('created_by_id');
            
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
            $table->dropColumn('barcode');
            $table->dropColumn('stock_code');
            $table->dropColumn('currency_type');
            $table->dropColumn('vat_rate');
            $table->dropColumn('cargo_company_id');
        });
    }
}
