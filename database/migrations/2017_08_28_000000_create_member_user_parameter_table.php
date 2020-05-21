<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 会员参数
 * Class CreateMemberUsersTable
 */
class CreateMemberUserParameterTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('member_user_parameter', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->unsignedTinyInteger('is_disturb')->default(0)->comment('勿扰 0 关闭 1开启');
            $table->unsignedTinyInteger('is_location')->default(1)->comment('是否开启定位 0 关闭 1开启');
            $table->unsignedTinyInteger('is_stranger')->default(0)->comment('陌生人信息 0 接收 1不接收');
            $table->unsignedTinyInteger('is_text')->default(0)->comment('文本信息 0 接收 1不接收');
            $table->unsignedTinyInteger('is_voice')->default(1)->comment('语音信息 0 接收 1不接收');
            $table->unsignedTinyInteger('is_video')->default(0)->comment('视频信息 0 接收 1不接收');
            $table->string('greeting')->nullable()->comment('问候语');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态0 正常 1禁用');
            $table->text('remark')->nullable()->comment('备注');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_user_parameter');
    }
}
