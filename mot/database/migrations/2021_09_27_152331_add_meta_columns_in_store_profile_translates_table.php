<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetaColumnsInStoreProfileTranslatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_profile_translates', function (Blueprint $table) {
            $table->text('meta_title')->nullable()->after('status');
            $table->text('meta_desc')->nullable()->after('meta_title');
            $table->text('meta_keyword')->nullable()->after('meta_desc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_profile_translates', function (Blueprint $table) {
            $table->dropColumn('meta_title');
            $table->dropColumn('meta_desc');
            $table->dropColumn('meta_keyword');
        });
    }
}
