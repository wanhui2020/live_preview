<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberUserRateTable extends Migration
{
    /**
     * 会员费率设置
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_user_rate', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->unsignedTinyInteger('is_custom')->default(0)->comment('是否定制费率0否1是');

            $table->decimal('gift_rate', 12, 2)->default(0.00)->comment('礼物平台分成占比（%）');
            $table->decimal('chat_rate', 12, 4)->default(0.00)->comment('聊天解锁分成占比（%）');
            $table->decimal('text_rate', 12, 4)->default(0.00)->comment('通消息收费分成占比（%）');
            $table->decimal('voice_rate', 12, 4)->default(0.00)->comment('语音通话消费分成占比（%）');
            $table->decimal('video_rate', 12, 4)->default(0.00)->comment('视频通话消费分成占比（%）');
            $table->decimal('view_picture_rate', 12, 4)->default(0.00)->comment('图片查看分成占比（%）');
            $table->decimal('view_video_rate', 12, 4)->default(0.00)->comment('视频查看分成占比（%）');

            $table->decimal('middleman_income_rate', 12, 4)->default(0.00)->comment('收入激励分成占比（%）');
            $table->decimal('middleman_recharge_rate', 12, 4)->default(0.00)->comment('充值激励分成占比（%）');
            $table->decimal('recommender_income_rate', 12, 2)->default(0.00)->comment('推荐人收入激励分成（%）');
            $table->decimal('recommender_recharge_rate', 12, 2)->default(0.00)->comment('推荐人充值激励分成（%）');

            $table->unsignedInteger('chat_fee')->default(0)->comment('聊天解锁收费');
            $table->unsignedInteger('text_fee')->default(0)->comment('普通消息收费');
            $table->unsignedInteger('voice_fee')->default(0)->comment('语音消息收费');
            $table->unsignedInteger('video_fee')->default(0)->comment('视频消息收费');
            $table->unsignedInteger('view_picture_fee')->default(0)->comment('颜照库收费');
            $table->unsignedInteger('view_video_fee')->default(0)->comment('视频库收费');

            $table->decimal('recommender_income_rate', 12, 2)->default(0.00)->comment('推荐人收入激励分成');
            $table->decimal('recommender_recharge_rate', 12, 2)->default(0.00)->comment('推荐人充值激励分成');
            $table->unsignedInteger('superior_revenue_time')->default(0)->comment('上级收益时间(天)');

            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态0 正常 1禁用');
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
        Schema::dropIfExists('member_user_rate');
    }
}
