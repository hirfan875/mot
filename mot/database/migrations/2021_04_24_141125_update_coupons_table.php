<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('type', 20)->nullable()->after('end_date');
            $table->string('discount', 10)->nullable()->after('type');
            $table->tinyInteger('usage_limit')->nullable()->default(1)->after('discount');
            $table->string('limit_no', 10)->nullable()->after('usage_limit');
            $table->string('per_user_limit', 10)->nullable()->after('limit_no');
            $table->tinyInteger('applies_to')->nullable()->default(1)->after('per_user_limit');
            $table->decimal('sub_total', 10, 2)->nullable()->after('applies_to');
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
            $table->dropColumn('type');
            $table->dropColumn('discount');
            $table->dropColumn('usage_limit');
            $table->dropColumn('limit_no');
            $table->dropColumn('per_user_limit');
            $table->dropColumn('applies_to');
            $table->dropColumn('sub_total');
        });
    }
}
