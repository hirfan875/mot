<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFullTextIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * we should'nt have to use two separate indexes
         */
        DB::statement('ALTER TABLE products ADD FULLTEXT fulltext_title (title)');
        DB::statement('ALTER TABLE products ADD FULLTEXT fulltext_meta_keyword (meta_keyword)');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE products DROP index fulltext_title');
        DB::statement('ALTER TABLE products DROP index fulltext_meta_keyword');
    }
}
