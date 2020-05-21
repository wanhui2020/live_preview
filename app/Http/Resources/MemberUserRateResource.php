<?php

namespace App\Http\Resources;

use App\Facades\PlatformFacade;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 会员费率
 * Class MemberUserResource
 * @package App\Http\Resources
 */
class MemberUserRateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $wechatPayMoney = $this->wechat_pay_money?$this->wechat_pay_money:PlatformFacade::config('wechat_pay_money');
        $goldName = PlatformFacade::config('gold_name');
        return [
            'id' => $this->id, //ID
            'text_fee' => $this->text_fee . $goldName.'/条', //普通消息收费
            'voice_fee' => $this->voice_fee .  $goldName.'/分钟', //语音消息收费
            'video_fee' => $this->video_fee . $goldName. '/分钟',//视频消息收费
            'view_picture_fee' => $this->view_picture_fee .  $goldName.'/分钟',//视频消息收费
            'view_video_fee' => $this->view_video_fee . $goldName. '/分钟',//视频消息收费
            'wechat_pay_money' => $wechatPayMoney .  $goldName.'',//微信查看花费金币
        ];
    }

}
