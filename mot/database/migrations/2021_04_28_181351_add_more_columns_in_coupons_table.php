<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumnsInCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->index(['coupon_code', 'status']);
            $table->unsignedBigInteger('store_id')->nullable()->after('status');
            $table->tinyInteger('is_admin')->default(0)->after('store_id');

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
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropIndex(['coupon_code', 'status']);
            $table->dropForeign(['store_id']);
            $table->dropColumn('store_id');
            $table->dropColumn('is_admin');
        });
    }
}
