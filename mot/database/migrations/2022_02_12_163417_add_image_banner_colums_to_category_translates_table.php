<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageBannerColumsToCategoryTranslatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_translates', function (Blueprint $table) {
            $table->string('image')->nullable()->after('data');
            $table->string('banner')->nullable()->after('data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_translates', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('banner');
        });
    }
}
