<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifiedNotificationsSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_devices', function (Blueprint $table) {
            $table->addColumn('tinyInteger', 'is_order_notifications', ['length' => 1])->nullable()->after('status');
            $table->renameColumn('status', 'is_general_notifications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_devices', function (Blueprint $table) {
            $table->renameColumn('is_general_notifications', 'status');
            $table->dropColumn('is_order_notification');
        });
    }
}
