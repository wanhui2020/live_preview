<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWechatPlatformShareToMemberUserRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_user_rate', function (Blueprint $table) {
            $table->decimal('wechat_platform_share')->default(0.00)->comment('微信查看平台分成');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_user_rate', function (Blueprint $table) {
            //
        });
    }
}
