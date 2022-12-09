<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameProductTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('product_tags', 'product_tag');

        // add is_admin column
        Schema::table('tags', function (Blueprint $table) {
            $table->tinyInteger('is_admin')->nullable()->after('status');
        });

        // drop timestamp columns from 'product_tag
        Schema::table('product_tag', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        // drop timestamp columns from 'category_product'
        Schema::table('category_product', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // remove is_admin column
        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });

        // drop timestamp columns from 'product_tag
        Schema::table('product_tag', function (Blueprint $table) {
            $table->timestamps();
        });

        // drop timestamp columns from 'category_product'
        Schema::table('category_product', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::rename('product_tag', 'product_tags');
    }
}
