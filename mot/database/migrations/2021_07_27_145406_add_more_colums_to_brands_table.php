<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumsToBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->unsignedBigInteger('store_id')->nullable()->after('status');
            $table->tinyInteger('is_approved')->nullable()->default(1)->index()->after('status');
            // set foreign keys
            $table->foreign('store_id')->references('id')->on('stores')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('is_approved');
            $table->dropColumn('store_id');
        });
    }
}
