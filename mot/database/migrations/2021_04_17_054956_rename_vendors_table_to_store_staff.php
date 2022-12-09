<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameVendorsTableToStoreStaff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('vendors', 'store_staff');

        // remove slug column
        Schema::table('store_staff', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropUnique('vendors_email_unique');
            $table->dropIndex('vendors_status_index');
            $table->dropIndex('vendors_password_index');

            $table->unique('email');
            $table->index('status');
            $table->index('password');

            // set foreign keys
            $table->foreign('store_id')->references('id')->on('stores')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('store_staff', 'vendors');

        // add slug column
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('slug', 60)->nullable()->index()->after('password');

            $table->dropUnique('store_staff_email_unique');
            $table->dropIndex('store_staff_status_index');
            $table->dropIndex('store_staff_password_index');

            $table->unique('email');
            $table->index('status');
            $table->index('password');

            // drop foreign
            $table->dropForeign('store_staff_store_id_foreign');
        });
    }
}
