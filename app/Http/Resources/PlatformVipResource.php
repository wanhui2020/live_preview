<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * //
 * Class PlatformVipResource
 * @package App\Http\Resources
 */
class PlatformVipResource extends JsonResource
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
            'gold_recharge' => $this->gold_recharge,//能量充值数量
            'online_duration' => $this->online_duration,//在线时长
            'call_minutes' => $this->call_minutes,//通话时长
            'like_count' => $this->like_count,//点赞数
            'gift_count' => $this->gift_count,//礼物数
            'price' => $this->price,//直接升级价格
        ];
    }
}
