<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsWechatPayToPlatformConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('platform_config', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_wechat_pay')->default(0)->comment('是否开启微信查看支付0=是，1=否');
            $table->unsignedTinyInteger('login_mode')->default(0)->comment('登录方式0=全部，1=手机号，2=微信号');
            $table->decimal('wechat_pay_money', 12, 2)->default(0)->comment('微信查看花费金币');
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
