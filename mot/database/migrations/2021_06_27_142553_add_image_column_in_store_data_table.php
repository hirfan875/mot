<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageColumnInStoreDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_data', function (Blueprint $table) {
            $table->string('logo', 250)->nullable()->after('banner');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_data', function (Blueprint $table) {
            $table->dropColumn('logo');
        });
    }
}
