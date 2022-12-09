<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumsToCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->string('capital', 100)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('emoji', 100)->nullable();
            $table->string('emoji_code', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('capital');
            $table->dropColumn('region');
            $table->dropColumn('emoji');
            $table->dropColumn('emoji_code');
        });
    }
}
