<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumsToCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('title', 100)->after('id')->nullable();
            $table->integer('buy_limit')->nullable()->after('discount');
            $table->integer('get_limit')->nullable()->after('discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('buy_limit');
            $table->dropColumn('get_limit');
        });
    }
}
