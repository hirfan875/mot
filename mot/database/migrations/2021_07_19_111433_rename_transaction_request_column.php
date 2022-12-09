<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTransactionRequestColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_attempts', function (Blueprint $table) {
            $table->renameColumn('transacton_request', 'transaction_request');
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
            $table->renameColumn('transaction_request', 'transacton_request');
        });
    }
}
