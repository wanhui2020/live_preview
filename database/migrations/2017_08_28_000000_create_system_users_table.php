<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('姓名');
            $table->string('email')->unique()->comment('邮箱');
            $table->string('mobile')->nullable()->comment('手机号');
            $table->string('password')->nullable()->comment('密码');
            $table->string('bind_mac')->nullable()->comment('设备绑定');
            $table->unsignedTinyInteger('type')->default(1)->comment('角色,0系统管理员1运营主管');
            $table->string('login_mac')->nullable()->comment('最后登录Mac');
            $table->string('login_ip')->nullable()->comment('最后登录ip');
            $table->dateTime('login_time')->nullable()->comment('最后登录时间');

            $table->string('security_code')->nullable()->comment('安全码');

            $table->unsignedTinyInteger('status')->default(0)->comment('状态 0:正常 1:禁用');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->text('remark')->nullable()->comment('备注');
            $table->unsignedInteger('versions')->default(0)->comment('版本号');
            $table->rememberToken();
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
        Schema::dropIfExists('system_users');
    }
}
