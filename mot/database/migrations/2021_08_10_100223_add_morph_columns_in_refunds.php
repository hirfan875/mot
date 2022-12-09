<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMorphColumnsInRefunds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_refunds', function (Blueprint $table) {
            $table->string('refund_type')->change()->nullable();
            $table->string('type')->nullable()->after('refund_type');
            $table->unsignedBigInteger('type_id')->nullable()->after('type');
            $table->string('notes')->nullable()->change();
            $table->string('refunded_id')->nullable();
            $table->string('refunded_refrence')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_refunds', function (Blueprint $table) {
            $table->bigInteger('refund_type')->change();
            $table->dropColumn('type');
            $table->dropColumn('type_id');
            $table->string('notes')->change();
            $table->dropColumn('refunded_id');
            $table->dropColumn('refunded_refrence');
        });
    }
}
