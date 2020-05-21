<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 系统登录日志
 * Class CreateMerchantsWalletTable
 */
class CreateSystemLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_logins', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('relevance');
            $table->string('mac')->nullable()->comment('最后登录Mac');
            $table->string('address')->nullable()->comment('最后登录ip');
            $table->string('device')->nullable()->comment('设备');
            $table->string('browser')->nullable()->comment('浏览器');
            $table->string('referer')->nullable()->comment('来源');
            $table->dateTime('login_time')->nullable()->comment('登入时间');
            $table->dateTime('logout_time')->nullable()->comment('登出时间');

            $table->unsignedTinyInteger('status')->default(0)->comment('状态 0:正常 1:禁用');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->text('remark')->nullable()->comment('备注');
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
        Schema::dropIfExists('system_logins');
    }
}
