<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToPlatformVipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('platform_vip', function (Blueprint $table) {
            $table->unsignedInteger('view_picture_fee')->default(20)->comment('颜照库收费');
            $table->unsignedInteger('view_video_fee')->default(20)->comment('视频库收费');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('platform_vip', function (Blueprint $table) {
            //
        });
    }
}
