<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NullableBrandIdInProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            \DB::statement('ALTER TABLE `products` MODIFY `brand_id` bigint UNSIGNED NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            \DB::statement('ALTER TABLE `products` MODIFY `brand_id` bigint UNSIGNED NOT NULL;');
        });
    }
}
