<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumnsToStoreQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_questions', function (Blueprint $table) {
            $table->tinyInteger('status')->after('store_id');
            $table->tinyInteger('is_archive')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_questions', function (Blueprint $table) {
            $table->dropColumn(['status', 'is_archive']);
        });
    }
}
