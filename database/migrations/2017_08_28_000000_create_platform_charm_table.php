<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 魅力管理
 * Class CreateMerchantsWalletTable
 */
class CreatePlatformCharmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_charm', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable()->comment('魅力名称');
            $table->unsignedTinyInteger('grade')->default(0)->comment('等级');
            $table->string('icon')->nullable()->comment('图标');
            $table->unsignedInteger('integral')->default(0)->comment('积分数');

            $table->unsignedInteger('text_fee')->default(20)->comment('普通消息收费');
            $table->unsignedInteger('voice_fee')->default(200)->comment('语音消息收费');
            $table->unsignedInteger('video_fee')->default(200)->comment('视频消息收费');
            $table->unsignedInteger('view_picture_fee')->default(20)->comment('颜照库收费');
            $table->unsignedInteger('view_video_fee')->default(20)->comment('视频库收费');

            $table->decimal('gift_rate', 12, 2)->default(0.00)->comment('礼物平台分成占比（%）');
            $table->decimal('chat_rate', 12, 4)->default(0.00)->comment('聊天解锁分成占比（%）');
            $table->decimal('text_rate', 12, 4)->default(0.00)->comment('通消息收费分成占比（%）');
            $table->decimal('voice_rate', 12, 4)->default(0.00)->comment('语音通话消费分成占比（%）');
            $table->decimal('video_rate', 12, 4)->default(0.00)->comment('视频通话消费分成占比（%）');
            $table->decimal('view_picture_rate', 12, 4)->default(0.00)->comment('图片查看分成占比（%）');
            $table->decimal('view_video_rate', 12, 4)->default(0.00)->comment('视频查看分成占比（%）');
            $table->text('describe')->nullable()->comment('特权说明');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态0 正常 1禁用');
            $table->text('remark')->nullable()->comment('备注');

            $table->unique(['grade']);
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
        Schema::dropIfExists('platform_charm');
    }
}
