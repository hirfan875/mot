<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumsToRequestProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_products', function (Blueprint $table) {
            $table->string('prod_name')->nullable()->after('product_type');
            $table->text('prod_desc')->nullable()->after('product_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_products', function (Blueprint $table) {
            $table->dropColumn('prod_name');
            $table->dropColumn('prod_desc');
        });
    }
}
