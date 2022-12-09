<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeColumnInTransactionAttempts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_attempts', function (Blueprint $table) {
            $table->string('type')->nullable()->after('transaction_response');
            $table->unsignedBigInteger('type_id')->nullable()->after('type');
            $table->string('iyzico_type')->nullable()->after('type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_attempts', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('type_id');
            $table->dropColumn('iyzico_type');
        });
    }
}
