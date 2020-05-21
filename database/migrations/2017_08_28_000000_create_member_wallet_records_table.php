<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberWalletRecordsTable extends Migration
{
    /**
     * 金币流水
     *
     * @return void
     *
     * 类型
     * 现金
     * 11:余额充值收入，
     * 12余额金币兑换收入
     * 13余额打赏收入
     * 14邀请充值奖励收入
     * 15语音视频收入
     * 16文本聊天收入
     * 17资源查看收入
     * 18礼物接收收入
     * 19经济人收入奖励
     * 20经济人充值奖励
     * 50推荐人收入奖励
     * 51推荐人充值奖励
     * 52微信查看收入
     * 53微信查看支出
     *
     * 21余额提现支出
     * 22余额打赏支出
     * 23能量充值支出
     * 24VIP购买支出
     *
     *能量
     * 31能量充值收入
     * 32语音视频收入
     * 33文本聊天收入
     * 34资源查看收入
     * 35礼物接收收入
     * 36邀请注册能量奖励
     * 37邀请消费金币奖励
     * 38聊天解锁收入
     * 39注册获得能量
     * 55邀请注册获得金币
     * 56注册获得金币
     *
     *
     * 41语音视频支出
     * 43文本聊天支出
     * 44资源查看支出
     * 45礼物赠送支出
     * 46金币兑换支出
     * 47聊天解锁支出
     */
    public function up()
    {
        Schema::create('member_wallet_records', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('member_id')->default(0)->comment('所属会员');
            $table->nullableMorphs('relevance');//业务关联
            $table->unsignedTinyInteger('type')->default(0)->comment('类型');
            $table->decimal('money', 12, 2)->default(0)->comment('数量');
            $table->decimal('surplus', 12, 2)->default(0)->comment('结余数量');
            $table->text('remark')->nullable()->comment('交易备注');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态0:正常 1:禁用  ');
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
        Schema::dropIfExists('member_wallet_records');
    }
}
