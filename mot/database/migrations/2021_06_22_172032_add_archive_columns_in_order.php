<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArchiveColumnsInOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_orders', function (Blueprint $table) {
            $table->tinyInteger('is_archived')->default(0)->after('mot_fee');
            $table->timestamp('archived_date')->nullable()->after('is_archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_orders', function (Blueprint $table) {
            $table->dropColumn('is_archived');
            $table->dropColumn('archived_date');
        });
    }
}
