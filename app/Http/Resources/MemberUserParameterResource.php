<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 会员参数
 * Class MemberUserResource
 * @package App\Http\Resources
 */
class MemberUserParameterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'member_id' => $this->member_id, //会员编号
            'is_disturb' => $this->is_disturb,//勿扰
            'is_location' => $this->is_location,//是否开启定位
            'is_stranger' => $this->is_stranger,//陌生人信息
            'is_text' => $this->is_text,//文本信息
            'is_voice' => $this->is_voice,//语音信息
            'is_video' => $this->is_video,//视频信息
            'greeting' => $this->greeting,//问候语
            'wechat_view' => $this->wechat_view,//微信查看 0允许 1不允许
            'is_screencap' => $this->is_screencap,
            'is_answer_host_phonep' => $this->is_answer_host_phonep,
        ];
    }

}
