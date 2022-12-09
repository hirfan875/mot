<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFlagColumnsInLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->text('emoji')->nullable()->after('direction');
            $table->text('emoji_uc')->nullable()->after('emoji');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->dropColumn('emoji');
            $table->dropColumn('emoji_uc');
        });
    }
}
