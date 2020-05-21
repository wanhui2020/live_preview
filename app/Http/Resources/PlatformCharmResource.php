<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 魅力信息
 * Class PlatformCharmResource
 * @package App\Http\Resources
 */
class PlatformCharmResource extends JsonResource
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
            'name' => $this->name,//名称
            'grade' => $this->grade,//等级
            'icon' => $this->icon,//图标
            'online_duration' => $this->online_duration,//在线时长
            'call_minutes' => $this->call_minutes,//通话时长
            'like_count' => $this->like_count,//点赞数
            'gift_count' => $this->gift_count,//礼物数
            'text_fee' => $this->text_fee,//普通消息收费
            'voice_fee' => $this->voice_fee,//语音消息收费
            'video_fee' => $this->video_fee,//视频消息收费
            'view_picture_fee' => $this->view_picture_fee,//颜照库收费
            'view_video_fee' => $this->view_video_fee,//视频库收费
            'gift_rate' => $this->gift_rate,//礼物平台分成占比
            'chat_rate' => $this->chat_rate,//聊天解锁分成占比
            'text_rate' => $this->text_rate,//通消息收费分成占比
            'voice_rate' => $this->voice_rate,//语音通话消费分成占比
            'video_rate' => $this->video_rate,//视频通话消费分成占比
            'view_picture_rate' => $this->view_picture_rate,//图片查看分成占比
            'view_video_rate' => $this->view_video_rate,//视频查看分成占比
        ];
    }
}
