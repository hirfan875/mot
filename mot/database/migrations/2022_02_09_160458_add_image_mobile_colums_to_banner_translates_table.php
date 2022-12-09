<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageMobileColumsToBannerTranslatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banner_translates', function (Blueprint $table) {
            $table->string('image_mobile')->nullable()->after('image');
            $table->text('button_url')->nullable()->after('button_text');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banner_translates', function (Blueprint $table) {
            $table->dropColumn('image_mobile');
            $table->dropColumn('button_url');
        });
    }
}
