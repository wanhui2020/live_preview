<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

//会员的礼物
class MemberGiftResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if (isset($this->gift)){
            return [
                'id' => $this->id, //ID
                'no' => $this->no, //订单编号
                'name' => $this->name,//礼物名称
                'quantity' => $this->quantity,//礼物数量
                'price' => $this->price,//礼物单价
                'ico' => $this->gift->ico,//图标
                'thumb' => $this->gift->thumb,//缩略图
                'cartoon' => $this->gift->cartoon,//动画地址

            ];
        }

    }
}
