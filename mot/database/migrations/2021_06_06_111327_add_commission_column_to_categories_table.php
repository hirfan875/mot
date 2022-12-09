<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommissionColumnToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->smallInteger('commission')->nullable()->after('slug');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->smallInteger('commission')->nullable()->after('tax_id_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('commission');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('commission');
        });
    }
}
