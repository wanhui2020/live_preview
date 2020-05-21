<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

//平台参数配置
class PlatformConfigResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id, //ID
            'cash_name' => $this->cash_name,//现金名称
            'gold_name' => $this->gold_name,//代币名称
            'gift_rate' => $this->gift_rate,//礼物平台分成占比
            'chat_rate' => $this->chat_rate,//聊天解锁分成占比
            'chat_unlock_duration' => $this->chat_unlock_duration,//聊天解锁时长单位天
            'voice_rate' => $this->voice_rate,//语音通话消费分成占比
            'video_rate' => $this->video_rate,//视频通话消费分成占比
            'view_picture_rate' => $this->cost_rate,//图片查看分成占比
            'view_video_rate' => $this->cost_rate,//视频查看分成占比
            'give_rate' => $this->cost_rate,//打赏佣金分成占比
            'invite_recharge_rate' => $this->invite_recharge_rate,//邀请充值奖励比
            'invite_consumption_rate' => $this->invite_consumption_rate,//邀请消费奖励比
            'invite_register_award' => $this->invite_register_award,//邀请注册奖励金币
            'text_fee' => $this->text_fee,//文本消息单条收费金币
            'chat_unlock' => $this->chat_unlock,//聊天解锁收费
            'voice_fee' => $this->voice_fee,//语音消息收费
            'video_fee' => $this->video_fee,//视频消息收费
            'view_picture_fee' => $this->view_picture_fee,//颜照库收费
            'view_video_fee' => $this->view_video_fee,//视频库收费
            'signin_award' => $this->signin_award,//签到奖励金币数量
            'text_free' => $this->text_free,//认证用户免费文本信息发放数量
            'selfie_realname' => 1,//自拍前实名0=需要，1=不需要
            'withdraw_rate' => $this->withdraw_rate,//提现费率
            'withdraw_min' => $this->withdraw_min,//最小提现金额
            'gold_rate' => round($this->gold_rate,2),//金币兑换余额比例
            'conversion_rate' => $this->conversion_rate,//会员兑换手续费
            'conversion_min' => $this->conversion_min,//最小兑换金币
            'is_wechat_pay' => $this->is_wechat_pay,//是否开启微信查看支付0=是，1=否
            'wechat_pay_money' => $this->wechat_pay_money,//微信查看花费金币
            'login_mode' => $this->login_mode,//登录方式0=全部，1=手机号，2=微信号
            'notice_display' => $this->notice_display,//公告显示 0是 1否
            'notice_agreement' => $this->notice_agreement,//同意协议 0是 1否
            'self_sex' => $this->self_sex,//自拍认证 0不限 1男2女

        ];
    }
}
