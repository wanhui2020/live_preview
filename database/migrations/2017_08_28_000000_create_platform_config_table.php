<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatformConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_config', function (Blueprint $table) {
            $table->increments('id');

            $table->string('gold_name')->default('能量')->comment('虚拟币名称');
            $table->string('cash_name')->default('金币')->comment('现金名称');
            $table->unsignedInteger('chat_unlock_duration')->default(7)->comment('聊天解锁时长单位天');
            $table->unsignedInteger('view_unlock_duration')->default(7)->comment('查看解锁时长单位天');


            $table->unsignedDecimal('invite_recharge_rate', 12, 4)->default(0.2)->comment('邀请充值奖励比（%）');
            $table->unsignedDecimal('invite_consumption_rate', 12, 4)->default(0.1)->comment('邀请消费奖励比（%）');
            $table->unsignedInteger('invite_register_award')->default(888)->comment('邀请注册奖励金币');

            $table->unsignedTinyInteger('platform_text_audit')->default(0)->comment('平台文本审核方式0=人工审核 ，1=系统审核，关闭审核');
            $table->unsignedTinyInteger('platform_image_audit')->default(0)->comment('平台图片审核方式0=人工审核 ，1=系统审核，关闭审核');
            $table->unsignedTinyInteger('platform_video_audit')->default(0)->comment('平台图片审核方式0=人工审核，1=系统审核，关闭审核');

            $table->unsignedTinyInteger('headpic_audit')->default(0)->comment('头像审核0=审核，1=不审核');
            $table->unsignedTinyInteger('picture_audit')->default(0)->comment('图片审核0=审核，1=不审核');
            $table->unsignedTinyInteger('video_audit')->default(0)->comment('视频审核0=审核，1=不审核');
            $table->unsignedTinyInteger('nickname_audit')->default(0)->comment('昵称审核0=审核，1=不审核');
            $table->unsignedTinyInteger('signature_audit')->default(0)->comment('签名审核0=审核，1=不审核');
            $table->unsignedTinyInteger('aphorism_audit')->default(0)->comment('格言审核0=审核，1=不审核');
            $table->unsignedTinyInteger('chat_audit')->default(0)->comment('文本信息审核0=审核，1=不审核');
            $table->unsignedTinyInteger('realname_audit')->default(0)->comment('实名审核0=审核，1=不审核');
            $table->unsignedTinyInteger('selfie_audit')->default(0)->comment('自拍审核0=审核，1=不审核');
            $table->unsignedTinyInteger('selfie_realname')->default(0)->comment('自拍前实名0=需要，1=不需要');

            $table->unsignedInteger('signin_award')->default(200)->comment('签到奖励金币数量');
            $table->unsignedInteger('online_robot')->default(10)->comment('在线陪聊');

            $table->unsignedInteger('charm_period')->default(15)->comment('魅力统计周期');
            $table->unsignedInteger('charm_online_duration_weight')->default(1)->comment('魅力在线时长权重');
            $table->unsignedInteger('charm_totalk_duration_weight')->default(1)->comment('魅力被叫通话时长权重');
            $table->unsignedInteger('charm_togift_gold_weight')->default(1)->comment('魅力接收礼物能量数权重');
            $table->unsignedInteger('charm_tolike_count_weight')->default(1)->comment('魅力接收点赞数权重');

            $table->unsignedInteger('vip_period')->default(0)->comment('VIP统计周期0不限');
            $table->unsignedInteger('vip_online_duration_weight')->default(1)->comment('VIP在线时长权重');
            $table->unsignedInteger('vip_fromtalk_duration_weight')->default(1)->comment('VIP主叫通话时长权重');
            $table->unsignedInteger('vip_fromgift_gold_weight')->default(1)->comment('VIP赠送礼物能量数权重');
            $table->unsignedInteger('vip_fromlike_count_weight')->default(1)->comment('VIP点赞数权重');
            $table->unsignedInteger('vip_recharge_total_weight')->default(1)->comment('VIP充值合计权重');


            $table->unsignedDecimal('withdraw_rate', 12, 4)->default(0.1)->comment('提现费率');
            $table->unsignedDecimal('withdraw_min', 12, 4)->default(100)->comment('最小提现金额');
            $table->unsignedDecimal('withdraw_max', 12, 4)->default(10000)->comment('最大提现金额');
            $table->unsignedDecimal('withdraw_day_max', 12, 4)->default(10000)->comment('日最大提现金额');
            $table->unsignedDecimal('withdraw_day_count', 12, 4)->default(1)->comment('日最大提现次数');

            $table->unsignedDecimal('recharge_rate', 12, 4)->default(0.1)->comment('充值费率');
            $table->unsignedDecimal('recharge_min', 12, 4)->default(100)->comment('最小充值金额');
            $table->unsignedDecimal('recharge_max', 12, 4)->default(100000)->comment('最大充值金额');
            $table->unsignedDecimal('gold_rate', 12, 4)->default(80)->comment('金币兑换比例');
            $table->unsignedDecimal('conversion_rate', 12, 4)->default(0.8)->comment('会员兑换手续费');
            $table->unsignedDecimal('conversion_min', 12, 4)->default(100)->comment('最小兑换金币');

            $table->decimal('register_energy', 12, 2)->default(0.00)->comment('注册获得能量');
            $table->decimal('recommender_income_rate', 12, 2)->default(0.00)->comment('推荐人收入激励分成');
            $table->decimal('recommender_recharge_rate', 12, 2)->default(0.00)->comment('推荐人充值激励分成');
            $table->decimal('unanswered', 12, 0)->default(0.00)->comment('电话未接/挂断(扣魅力值)');
            $table->unsignedInteger('user_age')->default(0)->comment('用户的最低年龄');
            $table->unsignedInteger('superior_revenue_time')->default(0)->comment('上级收益时间(天)');


            $table->unsignedTinyInteger('status')->default(0)->comment('状态。0=正常，1=禁用');
            $table->unsignedTinyInteger('sort')->default(0)->comment('排序');
            $table->text('remark')->nullable()->comment('备注');
            $table->unsignedInteger('versions')->default(0)->comment('版本号');
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
        Schema::dropIfExists('platform_config');
    }
}
