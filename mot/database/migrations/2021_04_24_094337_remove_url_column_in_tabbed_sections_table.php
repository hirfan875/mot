<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUrlColumnInTabbedSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tabbed_sections', function (Blueprint $table) {
            $table->dropColumn('view_all_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tabbed_sections', function (Blueprint $table) {
            $table->text('view_all_url')->nullable()->after('category_id');
        });
    }
}
