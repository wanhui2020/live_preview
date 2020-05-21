<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldPlatformConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('platform_config', function (Blueprint $table) {
            $table->unsignedTinyInteger('private_chat')->default(1)->comment('0允许，1不允许');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('platform_config', function (Blueprint $table) {
            //
        });
    }
}
