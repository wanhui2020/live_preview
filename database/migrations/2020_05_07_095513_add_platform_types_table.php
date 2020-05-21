<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlatformTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('platform_types',function (Blueprint $table) {
            $table->unsignedTinyInteger('is_system')->default(0)->comment('是否系统 0 否 1 是');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('platform_types', function (Blueprint $table) {
            $table->dropColumn('is_system');
        });
    }
}
