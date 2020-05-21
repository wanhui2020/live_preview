<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 支付通道
 * Class PlatformPaymentChannelResource
 * @package App\Http\Resources
 */
class PlatformPaymentChannelResource extends JsonResource
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
            'name' => $this->name,//通道名称
            'code' => $this->code,//通道标识
            'icon' => url($this->icon),//支付图标
        ];
    }
}
