<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

//支付账户
class PlatformPaymentResource extends JsonResource
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
            'type' => $this->type,//支付方式
            'vip_min_grade' => $this->vip_min_grade,//VIP最低等级
            'min_money' => $this->min_money,//最小金额
            'max_money' => $this->max_money,//最大金额
            'begin_time' => $this->begin_time,//开始时间
            'end_time' => $this->end_time,//结束时间
            'cost_rate' => $this->cost_rate,//成本费率
            'channel' => $this->channel,//支付通道
        ];
    }
}
