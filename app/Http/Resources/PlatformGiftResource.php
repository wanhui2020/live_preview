<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

//礼物配置
class PlatformGiftResource extends JsonResource
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
            'name' => $this->name,//礼物名称
            'gold' => $this->gold,//金币价格
            'ico' => $this->ico,//图标
            'cartoon' => $this->cartoon??$this->ico,//动画地址
        ];
    }
}
