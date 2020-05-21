<?php

namespace App\Http\Resources;

use App\Facades\PlatformFacade;
use Illuminate\Http\Resources\Json\JsonResource;

//资金流水
class WalletRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'record_id' => $this->id, //ID
            'nick_name' => $this->member->nick_name, //ID
            'relevance_type' => $this->relevance_type($this),//账户类型
            'type' => $this->type($this),//摘要
            'money' => $this->money,//金额
            'surplus' => $this->surplus,//结余
            'remark' => $this->remark,//备注
            'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at)),//发生时间
        ];
    }

    /**
     * 区分现金账户还是金币账户
     * @param $that
     * @return string
     */
    public function relevance_type($that)
    {
        if ($that->relevance_type == 'MemberWalletCash'){
            return PlatformFacade::config('cash_name');
        }else{
            return PlatformFacade::config('gold_name');
        }
    }

    /**
     * 摘要
     * @param $that
     * @return string
     */
    public function type($that)
    {
        if ($that->type === 11) {
            return PlatformFacade::config('gold_name').'充值收入';
        }
        if ($that->type === 12) {
            return PlatformFacade::config('gold_name').'充值兑换收入';
        }
        if ($that->type === 13) {
            return PlatformFacade::config('gold_name').'打赏收入';
        }
        if ($that->type === 14) {
            return '邀请充值奖励收入';
        }
        if ($that->type === 16) {
            return '文本信息收入'. PlatformFacade::config('gold_name');
        }
        if ($that->type === 17) {
            return '资源查看收入'. PlatformFacade::config('gold_name');
        }
        if ($that->type === 19) {
            return '经济人收入奖励';
        }
        if ($that->type === 20) {
            return '经济人充值奖励';
        }
        if ($that->type === 50) {
            return '邀请人收入奖励';
        }
        if ($that->type === 51) {
            return '邀请人充值奖励';
        }
        if ($that->type === 52) {
            return '微信查看收入';
        }
        if ($that->type === 53) {
            return '微信查看支出';
        }
        if ($that->type === 56) {
            return '注册获得'.PlatformFacade::config('cash_name');
        }
        if ($that->type === 55) {
            return '邀请注册获得'.PlatformFacade::config('cash_name').'奖励';
        }
        if ($that->type === 21) {
            return  PlatformFacade::config('gold_name').'提现支出';
        }
        if ($that->type === 22) {
            return  PlatformFacade::config('gold_name').'打赏支出';
        }
        if ($that->type === 23) {
            return PlatformFacade::config('gold_name').'充值支出';
        }
        if ($that->type === 24) {
            return 'VIP购买支出';
        }
        if ($that->type === 31) {
            return PlatformFacade::config('gold_name').'充值收入';
        }
        if ($that->type === 32) {
            return '语音视频收入';
        }
        if ($that->type === 33) {
            return '文本聊天收入';
        }
        if ($that->type === 34) {
            return '资源查看收入';
        }
        if ($that->type === 35) {
            return '礼物接收收入';
        }
        if ($that->type === 36) {
            return '邀请注册'.PlatformFacade::config('gold_name').'奖励';
        }
        if ($that->type === 37) {
            return '邀请消费'.PlatformFacade::config('gold_name').'奖励';
        }
        if ($that->type === 38) {
            return '聊天解锁收入';
        }
        if ($that->type === 39) {
            return '首次注册获得'.PlatformFacade::config('gold_name');
        }
        if ($that->type === 41) {
            return '语音视频支出';
        }
        if ($that->type === 43) {
            return '文本聊天支出';
        }
        if ($that->type === 44) {
            return '资源查看支出';
        }
        if ($that->type === 45) {
            return '礼物赠送支出';
        }
        if ($that->type === 46) {
            return  PlatformFacade::config('gold_name').'兑换支出';
        }
        if ($that->type === 47) {
            return '聊天解锁支出';
        }
        return '未知';
    }
}
