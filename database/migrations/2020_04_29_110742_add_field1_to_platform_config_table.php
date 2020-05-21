<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddField1ToPlatformConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('platform_config', function (Blueprint $table) {
            $table->unsignedDecimal('invite_cash', 12, 4)->default(0.0)->comment('邀请注册奖励现金（%）');
            $table->unsignedInteger('register_cash')->default(0)->comment('注册获得奖励现金');
            $table->unsignedTinyInteger('authentication_binding_phone')->default(0)->comment('认证是否绑定手机 0是 1否');
            $table->dropColumn('private_chat'); //删除private_chat字段
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
