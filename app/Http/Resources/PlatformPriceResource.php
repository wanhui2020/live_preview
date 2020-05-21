<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 价格项
 * Class PlatformPriceResource
 * @package App\Http\Resources
 */
class PlatformPriceResource extends JsonResource
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
            'money' => $this->money,//充值金额
            'gold' => floor($this->money * $this->rate * env('PLATFORM_EXCHANGE_RATE', 100)),//能量数
            'vip_give' => $this->when($this->vip_give,null),//会员赠送
        ];
    }
}
