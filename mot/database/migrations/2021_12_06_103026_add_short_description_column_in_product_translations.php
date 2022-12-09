<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShortDescriptionColumnInProductTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_translates', function (Blueprint $table) {
            $table->text('short_description')->nullable()->after('data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_translates', function (Blueprint $table) {
            $table->dropColumn('short_description');
        });
    }
}
