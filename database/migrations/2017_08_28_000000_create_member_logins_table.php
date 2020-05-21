<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberLoginsTable extends Migration
{
    /**
     * 会员登录日志
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_logins', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');

            $table->dateTime('login_time')->nullable()->comment('登录时间');
            $table->string('login_ip')->nullable()->comment('登录IP');
            $table->dateTime('logout_time')->nullable()->comment('登出时间');
            $table->unsignedInteger('duration')->default(0)->comment('在线时长');
            $table->string('versions')->nullable()->comment('客户端版本号');
            $table->string('platform')->nullable()->comment('客户端手机平台');
            $table->string('client')->nullable()->comment('客户端手机型号');

            $table->unsignedTinyInteger('status')->default(0)->comment('状态0正常  1禁用');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_logins');
    }
}
