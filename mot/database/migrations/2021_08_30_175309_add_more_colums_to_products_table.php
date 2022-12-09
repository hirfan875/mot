<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('weight', 50)->nullable()->after('image');
            $table->string('length', 50)->nullable()->after('image');
            $table->string('height', 50)->nullable()->after('image');
            $table->string('width', 50)->nullable()->after('image');
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
            $table->dropColumn('weight');
            $table->dropColumn('length');
            $table->dropColumn('height');
            $table->dropColumn('width');
        });
    }
}
