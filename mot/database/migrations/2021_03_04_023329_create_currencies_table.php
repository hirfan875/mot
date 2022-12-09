<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->nullable()->index();
            $table->string('is_default', 10)->nullable();
            $table->string('title', 50)->nullable();
            $table->string('base_rate', 20)->nullable();
            $table->string('code', 10)->nullable();
            $table->string('symbol', 10)->nullable();
            $table->string('symbol_position', 10)->nullable();
            $table->string('thousand_separator', 10)->nullable();
            $table->string('decimal_separator', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
