<?php

namespace App\Http\Resources;

use App\Facades\PayFacade;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

//充值记录
class WalletRechargeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id, //ID
            'no' => $this->no, //编号
            'member_id' => $this->member_id, //所属会员
            'payment_id' => $this->payment_id, //所属支付通道
            'money' => $this->money,//申请金额
            'cost_rate' => $this->cost_rate,//成本费率
            'cost_commission' => $this->cost_commission,//成本佣金
            'pay_time' => $this->pay_time,//支付时间
            'pay_status' => $this->pay_status,//支付状态0成功1失败2取消9支付中
            'payment' => new PlatformPaymentResource($this->payment) ,//支付通道

        ];
        return $data;
    }
}
