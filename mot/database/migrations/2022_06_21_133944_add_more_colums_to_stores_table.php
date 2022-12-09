<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumsToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('mobile')->nullable()->after('phone');
            $table->string('bank_name')->nullable()->after('tax_office');
            $table->string('account_title')->nullable()->after('tax_office');
            $table->string('company_website')->nullable()->after('email');
            $table->string('social_media')->nullable()->after('email');
            $table->string('legal_papers')->nullable()->after('seller_id');
            $table->string('signature')->nullable()->after('seller_id');
            $table->string('goods_services')->nullable()->after('seller_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('mobile');
            $table->dropColumn('bank_name');
            $table->dropColumn('account_title');
            $table->dropColumn('company_website');
            $table->dropColumn('social_media');
            $table->dropColumn('legal_papers');
            $table->dropColumn('signature');
            $table->dropColumn('goods_services');
        });
    }
}
